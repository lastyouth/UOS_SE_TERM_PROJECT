<?php
    require_once("config.php");
    require_once("RegistrationManager.php");
    
    $json = $_POST['jsondata'];
    $json = json_decode($json,true);
    $regmgr = RegistrationManager::GetInstance();
    $ret = $regmgr->RequestRegistration($json);
    
    switch($ret)
    {
        case REG_ERR_DUP_ID:
            alertandredirect("���̵� �ߺ��˴ϴ�.",true,"/Interface/registerForm.php");
            break;
        case REG_ERR_DB:
            alertandredirect("�����ͺ��̽� ó�� �� ������ �߻��߽��ϴ�.",true,"/Interface/index.php");
            break;
        case REG_OK:
            alertandredirect("ȸ�������� �Ϸ�Ǿ����ϴ�.",true,"/Interface/index.php");
            break;
        case REG_ERR_EMPTY:
            alertandredirect("������ �����մϴ�.",true,"/Interface/registerForm.php");
            break;
    }
?>