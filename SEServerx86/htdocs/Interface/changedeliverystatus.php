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
            alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("��� ��� ������ �ݿ����� ���Ͽ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        
    }
    else if($json['type'] === 'end_delivery')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_DELIVERING))
        {
            alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("��� �Ϸ� ������ �ݿ����� ���Ͽ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
    }
    else
    {
        alertandredirect("�߸��� �����Դϴ�.",true,"/Interface/index.php");
    }
?>