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
            alertandredirect("아이디가 중복됩니다.",true,"/Interface/registerForm.php");
            break;
        case REG_ERR_DB:
            alertandredirect("데이터베이스 처리 중 오류가 발생했습니다.",true,"/Interface/index.php");
            break;
        case REG_OK:
            alertandredirect("회원가입이 완료되었습니다.",true,"/Interface/index.php");
            break;
        case REG_ERR_EMPTY:
            alertandredirect("공란이 존재합니다.",true,"/Interface/registerForm.php");
            break;
    }
?>