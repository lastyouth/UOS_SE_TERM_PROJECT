<?PHP
require_once('config.php');
require_once("SessionManager.php");
require_once("OrderingProcessManager.php");
$sessmgr = SessionManager::GetInstance();
$ordermgr = OrderingProcessManager::GetInstance();
    //var_dump($sessmgr->temp_dbg_getSessionObjList());
header('Content-Type: text/html; charset=utf-8');
var_dump($ordermgr->GetCurrentOrderingInfoList(ORDER_INFO_PREV_COOK));
?>