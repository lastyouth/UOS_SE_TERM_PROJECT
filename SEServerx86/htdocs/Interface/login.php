<?php
    require_once("config.php");
    require_once('SessionManager.php');
    
    // get information type
    $type = $_POST['type'];
    
    // session check
    session_start();
    $sessid = session_id();
    $sessmgr = SessionManager::GetInstance();
    header( 'Content-Type: text/html; charset=euc-kr' );
    if($type === 'registered')
    {
        $id = $_POST['email'];
        $pw = $_POST['password'];
            
        if($sessmgr->RequestLogin($id,$pw))
        {
            alertandredirect("환영합니다.",true,"/Interface/index.php");
        }
        else
        {
            alertandredirect("회원 정보가 없거나 올바르지 않습니다.",true,"/Interface/index.php");
        }
            
    }else if($type === 'unregistered')
    {
            
        if($sessmgr->RequestUnregisteredLogin($_POST))
        {
            alertandredirect("환영합니다. 비회원 주문만 가능합니다.",true,"/Interface/index.php");
        }
        else
        {
            alertandredirect("올바르지 않은 정보입니다.",true,"/Interface/index.php");
        }
    }else
    {
        alertandredirect("올바르지 않은 타입입니다.",true,"/Interface/index.php");
    }
?>