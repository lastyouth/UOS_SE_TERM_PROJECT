<?PHP
require_once('config.php');
require_once('SessionManager.php');
    session_start();
    
    $sessmgr = SessionManager::GetInstance();
    $sessid = session_id();
    
    if($sessmgr->CheckCurrentSessionValidate($sessid))
    {
        $sessmgr->DeleteSessionObject($sessid);
    }
    redirect("/Interface/index.php");
?>