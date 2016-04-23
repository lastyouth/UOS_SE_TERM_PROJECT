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
            // ȸ�� ���� ����
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
            // Ordering Info ��ü ����
            $neworder = $this->CreateOrderingInfo($jsondata);
            
            // dbƮ����� �Ŵ��� ��ȯ
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
                // �ϳ��� ccd�� ������
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
                    // ���� ��� ������, �� �ڽ��丮�� ������ ���ϴ� ���
                    $ret = array();
                    
                    $ret['status'] = ORDER_ERR_NO_INGREDIENT;
                    $ret['name'] = $ccdname;
                    $ret['message'] = $dbmgr->GetErrorMessage();
                    
                    return $ret;
                }
                
                // �� ��� �ش� �丮�� �����Ǿ���
                $coursedish = new CustomizedCourseDishInfo($ccd['style'],$ccd['name'],$ccd['dbkey'],$ccd['totalprice']);
                
                $coursedish->SetMenuNameList($ccdmenulist);
                
                $neworder->AddCustomizedCourseDish($coursedish);                
            }
            // ���� �õ�
            $paymgr = PaySystemManager::GetInstance();
            if(!($paymgr->RequestPayment($jsondata['creditcard'],$jsondata['overallprice'])))
            {
                return ORDER_ERR_PAYMENT;
            }
            // ��� ����
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
            // �ֹ� ��Ұ� �����Ѱ�?
            $orderinginfoobj = $this->GetOrderingInfo($key);
            
            if($orderinginfoobj === null)
            {
                return false;
            }
            // �丮 ������ �Ǿ����� ��� �Ұ�
            if($orderinginfoobj->GetCookingStartTime() !== null)
            {
                return false;
            }
            // ��� ����
            // ȯ��
            $userinfo = $orderinginfoobj->GetUserInfo();
            $creditcardnum = $userinfo->GetCreditcardNum();
            
            $paymgr = PaySystemManager::GetInstance();
            if(!($paymgr->RequestCancelPayment($creditcardnum)))
            {
                // ȯ�� ����
                return false;
            }
            // dbƮ����� �Ŵ��� ��ȯ
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
            // ��� ������� ���
            if(!($dbmgr->SetOrderingInfo($obj,OPER_TYPE_MOD,$dbkey)))
            {
                return false;
            }
            
            if($type === ORDER_INFO_DELIVERING)
            {
                // ����� ���� ���
                // ���� �ֹ� ������ ����
                $this->DeleteOrderingInfo($key);
            }
            else
            {
                // ������� ���� ��, �ݿ�
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