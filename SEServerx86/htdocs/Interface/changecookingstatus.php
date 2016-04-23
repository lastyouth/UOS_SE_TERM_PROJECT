<?php
    require_once('config.php');
    require_once('OrderingProcessManager.php');
    
    $json = $_POST['jsondata'];
    
    $json = json_decode($json,true);
    $ordermgr = OrderingProcessManager::GetInstance();
    
    if($json['type'] === 'start_cooking')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_PREV_COOK))
        {
            alertandredirect("반영되었습니다.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("요리 시작 정보가 반영되지 못하였습니다.",true,"/Interface/cookingstatusForm.php");
        }
        
    }
    else if($json['type'] === 'end_cooking')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_COOKING))
        {
            alertandredirect("반영되었습니다.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("요리 완료 정보가 반영되지 못하였습니다.",true,"/Interface/cookingstatusForm.php");
        }
    }
    else
    {
        alertandredirect("잘못된 정보입니다.",true,"/Interface/index.php");
    }
?>