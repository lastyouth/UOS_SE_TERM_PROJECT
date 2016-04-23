<?PHP
    require_once('config.php');
    require_once('OrderingProcessManager.php');
    
    $json = $_POST['jsondata'];
    
    //header('Content-Type: text/html; charset=utf-8');
    
    $json = json_decode($json,true);
    
    //print_r($json);
    
    $json['requestordertime']/= 1000;
    
    $ordermgr = OrderingProcessManager::GetInstance();
    
    $ret = $ordermgr->RequestOrdering($json);
    
    if(is_array($ret))
    {
        // �� ��� ���� ����� ����.
        
        if($ret['status'] === ORDER_ERR_NO_INGREDIENT)
        {
            $ccdname = $ret['name'];
            $msg = sprintf("�˼��մϴ�. ���� �ڽ��丮�� ��ᰡ �����Ͽ� ���� �� �����ϴ�. [%s] [%s]",$ccdname,$ret['message']);
            
            // ��ᰡ ���� �ù�   
            alertandredirect($msg,true,"/Interface/index.php");
        }
        else if($ret['status'] === ORDER_ERR_DATABASE)
        {
            $msg = sprintf("�����ͺ��̽��� ������ �ֽ��ϴ�. �����ڿ��� �����ϼ���. [%s]",$ret['message']);
            alertandredirect($msg,true,"/Interface/index.php");
        }
    }
    else
    {
        if($ret === ORDER_ERR_PAYMENT)
        {
            alertandredirect("������ �����߽��ϴ�.",true,"/Interface/index.php");
        }
        else if($ret === true)
        {
            redirect("/Interface/ordercompleteForm.php");
        }
        else
        {
            alertandredirect("�ֹ��� �ùٸ��� �������� �ʾҽ��ϴ�. ���߿� �ٽ� �õ��ϼ���",true,"/Interface/index.php");
        }
    }
?>