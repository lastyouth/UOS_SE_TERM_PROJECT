<?php
    require_once("config.php");
    require_once("ModifyDataManager.php");
    
    $json = $_POST['jsondata'];
    $json = json_decode($json,true);
    $modmgr = ModifyDataManager::GetInstance();
    
    if($modmgr->RequestModifyRegisterationInfo($json))
    {
         alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/index.php");
    }
    else
    {
         alertandredirect("�����ͺ��̽� ó�� �� ������ �߻��߽��ϴ�.",true,"/Interface/index.php");
    }
?>