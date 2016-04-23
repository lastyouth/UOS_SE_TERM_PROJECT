<?php
    require_once('config.php');
    require_once('OrderingInfo.php');
    require_once('CustomizedCourseDishInfo.php');
    require_once('DefaultUserInfo.php');
    require_once('RegisteredUserInfo.php');
    require_once('DBTransactionManager.php');
    require_once('PaySystemManager.php');
    
    
    class OrderingProcessManager
    {
        private static $m_Instance = __CLASS__;
        private $cur_OI;
        
        private function __construct()
        {
            $this->cur_OI = array();
        }
        private function CreateOrderingInfo($json)
        {
            // 회원 정보 생성
            $type = $json['type'];
            $name = $json['name'];
            $phonenumber = $json['phonenumber'];
            $address = $json['address'];
            $creditcard = $json['creditcard'];
            
            $userinfo = null;
            
            if($type === 'registered')
            {
                $email = $json['email'];
                
                $userinfo = new RegisteredUserInfo($name,$phonenumber,$address,$creditcard,$email,"");
                
            }
            else
            {
                $userinfo = new DefaultUserInfo($name,$phonenumber,$address,$creditcard);
            }
            
            $neworderinginfo = new OrderingInfo($userinfo,$json['overallprice'],$json['discount'],date(DATE_FORMAT),date(DATE_FORMAT,$json['requestordertime']));
            
            
            return $neworderinginfo;
        }
        private function AddOrderingInfo($info)
        {
            array_push($this->cur_OI,$info);
            apc_store(OrderingProcessManager::$m_Instance,$this);
        }
        private function DeleteOrderingInfo($key)
        {
            foreach($this->cur_OI as $dbkey=>$orderinfo)
            {
                $objdbkey = $orderinfo->GetDBKey();
                
                if($objdbkey === $key)
                {
                    unset($this->cur_OI[$dbkey]);
                    apc_store(OrderingProcessManager::$m_Instance,$this);
                    return true;
                }

            }
            return false;
        }
        
        static function GetInstance()
        {             
            if(!apc_exists(OrderingProcessManager::$m_Instance))
            {
                apc_add(OrderingProcessManager::$m_Instance,new OrderingProcessManager());
            }
            return apc_fetch(OrderingProcessManager::$m_Instance);
        }
        
        function RequestOrdering($jsondata)
        {
            // Ordering Info 객체 생성
            $neworder = $this->CreateOrderingInfo($jsondata);
            
            // db트랜잭션 매니저 소환
            $dbmgr = DBTransactionManager::GetInstance();
            
            $ccdlist = $jsondata['customizedcoursedishlist'];
            
            foreach($ccdlist as $ccd)
            {
                if($ccd === null)
                {
                    continue;
                }
                $ccdkey = $ccd['dbkey'];
                $ccdname = $ccd['name'];
                
                $ccdmenulist = array();
                
                $ccdmainmenu = $ccd['main_menu_list'];
                $ccdsubmenu = $ccd['sub_menu_list'];
                // 하나의 ccd를 검증함
                foreach($ccdmainmenu as $mainmenu)
                {
                    if($mainmenu === null)
                    {
                        continue;
                    }
                    array_push($ccdmenulist,$mainmenu['dbkey']);
                }
                foreach($ccdsubmenu as $submenu)
                {
                    if($submenu === null)
                    {
                        continue;
                    }
                    array_push($ccdmenulist,$submenu['dbkey']);
                }
                
                if(!($dbmgr->CheckMenuValidate($ccdmenulist)))
                {
                    // 남은 재료 수량이, 이 코스요리를 만들지 못하는 경우
                    $ret = array();
                    
                    $ret['status'] = ORDER_ERR_NO_INGREDIENT;
                    $ret['name'] = $ccdname;
                    $ret['message'] = $dbmgr->GetErrorMessage();
                    
                    return $ret;
                }
                
                // 이 경우 해당 요리는 검증되었음
                $coursedish = new CustomizedCourseDishInfo($ccd['style'],$ccd['name'],$ccd['dbkey'],$ccd['totalprice']);
                
                $coursedish->SetMenuNameList($ccdmenulist);
                
                $neworder->AddCustomizedCourseDish($coursedish);                
            }
            // 결제 시도
            $paymgr = PaySystemManager::GetInstance();
            if(!($paymgr->RequestPayment($jsondata['creditcard'],$jsondata['overallprice'])))
            {
                return ORDER_ERR_PAYMENT;
            }
            // 디비에 저장
            $dbkey = null;
            if($dbmgr->SetOrderingInfo($neworder,OPER_TYPE_ADD,$dbkey))
            {
                $neworder->SetDBKey($dbkey);
                
                $this->AddOrderingInfo($neworder);
                
                return true;
            }
            else
            {
                $ret = array();
                $ret['status'] = ORDER_ERR_DATABASE;
                $ret['message'] = $dbmgr->GetErrorMessage();
                
                return $ret;
            }
        }
        
        function RequestCencelOrdring($key)
        {
            // 주문 취소가 가능한가?
            $orderinginfoobj = $this->GetOrderingInfo($key);
            
            if($orderinginfoobj === null)
            {
                return false;
            }
            // 요리 시작이 되었으면 취소 불가
            if($orderinginfoobj->GetCookingStartTime() !== null)
            {
                return false;
            }
            // 취소 가능
            // 환불
            $userinfo = $orderinginfoobj->GetUserInfo();
            $creditcardnum = $userinfo->GetCreditcardNum();
            
            $paymgr = PaySystemManager::GetInstance();
            if(!($paymgr->RequestCancelPayment($creditcardnum)))
            {
                // 환불 실패
                return false;
            }
            // db트랜잭션 매니저 소환
            $dbmgr = DBTransactionManager::GetInstance();
            $ret = null;
            if($dbmgr->SetOrderingInfo($orderinginfoobj,OPER_TYPE_DEL,$ret))
            {
                $this->DeleteOrderingInfo($key);
                return true;
            }
            return false;
        }
        
        function UpdateOrderingInfo($key,$type)
        {
            $target_idx= null;
            foreach($this->cur_OI as $idx => $val)
            {
                if($val->GetDBKey() === $key)
                {
                    $target_idx = $idx;
                    break;
                }
            }
            if($target_idx === null)
            {
                return false;
            }
            $obj = $this->cur_OI[$target_idx];
            switch($type)
            {
                case ORDER_INFO_PREV_COOK:
                    $obj->SetCookingStartTime(date(DATE_FORMAT));
                    break;
                case ORDER_INFO_COOKING:
                    $obj->SetCookingEndTime(date(DATE_FORMAT));
                    break;
                case ORDER_INFO_PREV_DELIVER:
                    $obj->SetDeliveryStartTime(date(DATE_FORMAT));
                    break;
                case ORDER_INFO_DELIVERING:
                    $obj->SetDeliveryEndTime(date(DATE_FORMAT));
                    break;
            }
            
            $dbmgr = DBTransactionManager::GetInstance();
            $dbkey = null;
            // 디비에 변경사항 기록
            if(!($dbmgr->SetOrderingInfo($obj,OPER_TYPE_MOD,$dbkey)))
            {
                return false;
            }
            
            if($type === ORDER_INFO_DELIVERING)
            {
                // 배달이 끝난 경우
                // 현재 주문 정보를 삭제
                $this->DeleteOrderingInfo($key);
            }
            else
            {
                // 변경사항 저장 후, 반영
                $this->cur_OI[$target_idx] = $obj;
            }
            apc_store(OrderingProcessManager::$m_Instance,$this);
            return true;
        }
        function GetOrderingInfo($key)
        {
            foreach($this->cur_OI as $val)
            {
                if($val->GetDBKey() === $key)
                {
                    return $val;
                }
            }
            return null;
        }
        function GetCurrentOrderingInfoList($type)
        {
            $ret = array();
            
            switch($type)
            {
                case ORDER_INFO_PREV_COOK:
                    foreach($this->cur_OI as $val)
                    {
                        $cooking_start_time = $val->GetCookingStartTime();
                        
                        if($cooking_start_time === null)
                        {
                            array_push($ret,$val->GetJsonData());
                        }
                    }
                    break;
                case ORDER_INFO_COOKING:
                    foreach($this->cur_OI as $val)
                    {
                        $cooking_start_time = $val->GetCookingStartTime();
                        $cooking_end_time = $val->GetCookingEndTime();
                        
                        if($cooking_start_time !== null && $cooking_end_time === null)
                        {
                            array_push($ret,$val->GetJsonData());
                        }
                    }
                    break;
                case ORDER_INFO_PREV_DELIVER:
                    foreach($this->cur_OI as $val)
                    {
                        $cooking_start_time = $val->GetCookingStartTime();
                        $cooking_end_time = $val->GetCookingEndTime();
                        $delivery_start_time = $val->GetDeliveryStartTime();
                        
                        if($cooking_start_time !== null && $cooking_end_time !== null && $delivery_start_time === null)
                        {
                            array_push($ret,$val->GetJsonData());
                        }
                    }
                    break;
                case ORDER_INFO_DELIVERING:
                    foreach($this->cur_OI as $val)
                    {
                        $cooking_start_time = $val->GetCookingStartTime();
                        $cooking_end_time = $val->GetCookingEndTime();
                        $delivery_start_time = $val->GetDeliveryStartTime();
                        $delivery_end_time = $val->GetDeliveryEndTime();
                        
                        if($cooking_start_time !== null && $cooking_end_time !== null && $delivery_start_time !== null && $delivery_end_time === null)
                        {
                            array_push($ret,$val->GetJsonData());
                        }
                    }
                    break;
            }
            return $ret;
        }
    }
?>