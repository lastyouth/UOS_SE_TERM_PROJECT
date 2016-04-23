<?php
    require_once('config.php');
    require_once('OrderingInfo.php');
    require_once('SessionManager.php');
    require_once('JSONOutputManager.php');
    require_once('OrderingProcessManager.php');

    $type = $_POST['type']; 
    $sessmgr = SessionManager::GetInstance();
    $jsonmgr = JSONOutputManager::GetInstance();
    
    $response = array();
    if($type === 'order')
    {
        session_start();
        $sessid = session_id();
        
        $sessobj = $sessmgr->GetSessionObject($sessid);
        
        $userinfo = null;
        
        if($sessobj === null)
        {
            // something wrong
            echo 'error';
        }
        else
        {
            $response['menulist'] = $jsonmgr->RequestGetMenuInfoList();
            
            $response['courselist'] = $jsonmgr->RequestGetCourseDishInfoList();
            
            if($sessobj->GetType() === SESSION_TYPE_REGISTERED)
            {
                $userid = $sessobj->GetEmail();
                
                $userinfo = $jsonmgr->RequestGetUserInfo($userid);
                $response['ordercount'] = $jsonmgr->RequestGetUserOrderingCount($userid);
            }
            else if($sessobj->GetType() === SESSION_TYPE_UNREGISTERED)
            {
                $userinfo = $sessobj->GetDefaultUserInfo();
                $response['ordercount'] = -1;
            }
            
            $response['userinfo'] = $userinfo->GetJsonData();
            
            $response['discountpolicy'] = $jsonmgr->RequestGetDiscountPolicy();
            
            
            echo json_encode($response,JSON_UNESCAPED_UNICODE);
        }                
    }
    else if($type ==='reorder')
    {
        $response['orderedlist'] = array();
        
        session_start();
        $sessid = session_id();
        
        $sessobj = $sessmgr->GetSessionObject($sessid);
        
        $userinfo = null;
        
        if($sessobj === null)
        {
            // something wrong
            echo 'error';
        }
        else
        {
            if($sessobj->GetType() !== SESSION_TYPE_REGISTERED)
            {
                echo 'error';
            }
            else
            {
                $id = $sessobj->GetEmail();
                
                $response['orderedlist'] = $jsonmgr->RequestGetOrderingInfoList($id,0);
                $response['ordercount'] = $jsonmgr->RequestGetUserOrderingCount($id);
                $response['menulist'] = $jsonmgr->RequestGetMenuInfoList();
                $response['discountpolicy'] = $jsonmgr->RequestGetDiscountPolicy();
                $userinfo = $jsonmgr->RequestGetUserInfo($id);
                $response['userinfo'] = $userinfo->GetJsonData();
                
                echo json_encode($response,JSON_UNESCAPED_UNICODE);
            }
        }
    }else if($type === 'ingredient')
    {
        /*session_start();
        
        if($sessmgr->CheckCurrentSessionValidate(session_id()))
        {
            $obj = $sessmgr->GetSessionObject(session_id());
            
            if($obj->GetType() != SESSION_TYPE_MAT)
            {
                echo "error";
            }
            else
            {
                echo json_encode($response,JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            echo "error";
        }     */
        
        $response['ingredientlist'] = $jsonmgr->RequestGetSuppliesInfoList(); 
        
               
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }else if($type === 'menu')
    {
        $response['menulist'] = $jsonmgr->RequestGetMenuInfoList();
        
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }else if($type === 'course')
    {
        $response['courselist'] = $jsonmgr->RequestGetCourseDishInfoList();
        
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }else if($type === 'sessioncheck')
    {
        session_start();
        
        $sessmgr = SessionManager::GetInstance();
        $sessid = session_id();
        
        if($sessmgr->CheckCurrentSessionValidate($sessid))
        {
            $sessobj = $sessmgr->GetSessionObject($sessid);
            
            $page = $_POST['page'];
            
            switch($sessobj->GetType())
            {
                case SESSION_TYPE_EMP_COOKED:
                    echo 'cook';
                    break;
                case SESSION_TYPE_EMP_DELIVERED:
                    echo 'deliver';
                    break;
                case SESSION_TYPE_MAT:
                    echo 'mat';
                    break;
                case SESSION_TYPE_UNREGISTERED:
                    echo 'unregistered';
                    break;
                case SESSION_TYPE_REGISTERED:
                    echo 'registered';
                    break;
            }
        }
        else
        {
            // session expired
            echo 'unlogined';
        }
    }else if($type ==='cook')
    {
        $ordermgr = OrderingProcessManager::GetInstance();
        // 요리 정보
        $response['prev_cooking'] = $ordermgr->GetCurrentOrderingInfoList(ORDER_INFO_PREV_COOK);
        $response['cooking'] = $ordermgr->GetCurrentOrderingInfoList(ORDER_INFO_COOKING);
        
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }else if($type === 'delivery')
    {
        $ordermgr = OrderingProcessManager::GetInstance();
        // 요리 정보
        $response['prev_delivering'] = $ordermgr->GetCurrentOrderingInfoList(ORDER_INFO_PREV_DELIVER);
        $response['delivering'] = $ordermgr->GetCurrentOrderingInfoList(ORDER_INFO_DELIVERING);
        
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }else if($type === 'updateuserinfo')
    {
        session_start();
        
        $sessmgr = SessionManager::GetInstance();
        $sessid = session_id();
        
        $sessobj = $sessmgr->GetSessionObject($sessid);
        
        if($sessobj === null)
        {
            echo "error";
        }
        else
        {
            if($sessobj->GetType() === SESSION_TYPE_REGISTERED)
            {
                $id = $sessobj->GetEmail();
                
                $userinfo = $jsonmgr->RequestGetUserInfo($id);
                
                $response['userinfo'] = $userinfo->GetJsonData();
                
                echo json_encode($response,JSON_UNESCAPED_UNICODE);
            }
            else
            {
                // 비회원이 접근한경우
                echo "error";
            }
        }
    }else if($type ==='discountpolicy')
    {
        $response['discountpolicy'] = $jsonmgr->RequestGetDiscountPolicy();
        
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }
?>