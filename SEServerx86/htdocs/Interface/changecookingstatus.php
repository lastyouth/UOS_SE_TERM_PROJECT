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
            alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("�丮 ���� ������ �ݿ����� ���Ͽ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        
    }
    else if($json['type'] === 'end_cooking')
    {
        $dbkey = $json['dbkey'];
        
        if($ordermgr->UpdateOrderingInfo($dbkey,ORDER_INFO_COOKING))
        {
            alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
        else
        {
            alertandredirect("�丮 �Ϸ� ������ �ݿ����� ���Ͽ����ϴ�.",true,"/Interface/cookingstatusForm.php");
        }
    }
    else
    {
        alertandredirect("�߸��� �����Դϴ�.",true,"/Interface/index.php");
    }
?>