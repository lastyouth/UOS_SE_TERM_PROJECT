<?PHP
    require_once('config.php');
    require_once('OrderingProcessManager.php');
    
    $json = $_POST['jsondata'];
    
    //header('Content-Type: text/html; charset=utf-8');
    
    $json = json_decode($json,true);
    
    
    $ordermgr = OrderingProcessManager::GetInstance();
    
    $oper = $json['oper']; // 주문 취소인가? 재주문인가?    
    
    if($oper === 'reorder')
    {
        $json['requestordertime']/= 1000;
        $ret = $ordermgr->RequestOrdering($json);
        
        if(is_array($ret))
        {
            // 이 경우 뭔가 사단이 났다.
            
            if($ret['status'] === ORDER_ERR_NO_INGREDIENT)
            {
                $ccdname = $ret['name'];
                $msg = sprintf("죄송합니다. 다음 코스요리를 재료가 부족하여 만들 수 없습니다. [%s] [%s]",$ccdname,$ret['message']);
                
                // 재료가 없다 시밤   
                alertandredirect($msg,true,"/Interface/index.php");
            }
            else if($ret['status'] === ORDER_ERR_DATABASE)
            {
                $msg = sprintf("데이터베이스에 문제가 있습니다. 관리자에게 문의하세요. [%s]",$ret['message']);
                alertandredirect($msg,true,"/Interface/index.php");
            }
        }
        else
        {
            if($ret === ORDER_ERR_PAYMENT)
            {
                alertandredirect("결제에 실패했습니다.",true,"/Interface/index.php");
            }
            else if($ret === true)
            {
                redirect("/Interface/ordercompleteForm.php");
            }
            else
            {
                alertandredirect("주문이 올바르게 접수되지 않았습니다. 나중에 다시 시도하세요",true,"/Interface/index.php");
            }
        }
    }
    else
    {
        // 이경우 주문 취소
        
        if($ordermgr->RequestCencelOrdring($json['dbkey']))
        {
            alertandredirect("반영되었습니다.",true,"/Interface/orderedmenulistForm.php");
        }
        else
        {
            alertandredirect("주문을 취소할 수 없었습니다.",true,"/Interface/orderedmenulistForm.php");
        }
    }
?>