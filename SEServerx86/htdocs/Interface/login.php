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
            alertandredirect("ȯ���մϴ�.",true,"/Interface/index.php");
        }
        else
        {
            alertandredirect("ȸ�� ������ ���ų� �ùٸ��� �ʽ��ϴ�.",true,"/Interface/index.php");
        }
            
    }else if($type === 'unregistered')
    {
            
        if($sessmgr->RequestUnregisteredLogin($_POST))
        {
            alertandredirect("ȯ���մϴ�. ��ȸ�� �ֹ��� �����մϴ�.",true,"/Interface/index.php");
        }
        else
        {
            alertandredirect("�ùٸ��� ���� �����Դϴ�.",true,"/Interface/index.php");
        }
    }else
    {
        alertandredirect("�ùٸ��� ���� Ÿ���Դϴ�.",true,"/Interface/index.php");
    }
?>