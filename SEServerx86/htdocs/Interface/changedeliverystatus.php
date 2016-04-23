<?php
    require_once('config.php');
    require_once('OrderingProcessManager.php');
    
    $json = $_POST['jsondata'];
    
    $json = json_decode($json,true);
    $ordermgr = OrderingProcessManager::GetInstance();
    
    if($json['type'] === 'start_delivery')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_PREV_DELIVER))
        {
            alertandredirect("반영되었습니다.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("배달 출발 정보가 반영되지 못하였습니다.",true,"/Interface/cookingstatusForm.php");
        }
        
    }
    else if($json['type'] === 'end_delivery')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_DELIVERING))
        {
            alertandredirect("반영되었습니다.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("배달 완료 정보가 반영되지 못하였습니다.",true,"/Interface/cookingstatusForm.php");
        }
    }
    else
    {
        alertandredirect("잘못된 정보입니다.",true,"/Interface/index.php");
    }
?>