<?php
    require_once('config.php');
    require_once('ModifyDataManager.php');
    
    $modmgr = ModifyDataManager::GetInstance();
    
    
    
    if($modmgr->RequestModifyDiscountPolicy($_POST))
    {
        alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/discountpolicyForm.php");
    }
    else
    {
        alertandredirect("���� ��å�� �������� ���߽��ϴ�.",true,"/Interface/discountpolicyForm.php");
    }
?>