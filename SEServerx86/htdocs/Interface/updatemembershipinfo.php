<?php
    require_once("config.php");
    require_once("ModifyDataManager.php");
    
    $json = $_POST['jsondata'];
    $json = json_decode($json,true);
    $modmgr = ModifyDataManager::GetInstance();
    
    if($modmgr->RequestModifyRegisterationInfo($json))
    {
         alertandredirect("반영되었습니다.",true,"/Interface/index.php");
    }
    else
    {
         alertandredirect("데이터베이스 처리 중 오류가 발생했습니다.",true,"/Interface/index.php");
    }
?>