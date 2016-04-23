<?PHP
    require_once("config.php");
    require_once("ModifyDataManager.php");

    $json = json_decode($_POST['jsondata'],true);
    $modtype = $json['type'];
    
    $modifymgr = ModifyDataManager::GetInstance();
    
    
    switch($modtype)
    {
        case 'courseadd':
            // �ڽ� �߰�  
            $json['name'] = $_POST['name'];
            $json['description'] = $_POST['description'];
            
            if($modifymgr->RequestModifyCourseDishInfo($json))
            {
                alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                alertandredirect("�ڽ� �߰��� �����߽��ϴ�. ���߿� �ٽ� �õ��Ͻʽÿ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
        case 'menuadd':
            // �޴� �߰�
            $json['name'] = $_POST['name'];
            $json['description'] = $_POST['description'];
            $json['price'] = $_POST['price'];
            
            if($modifymgr->RequestModifyMenuInfo($json))
            {
                alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                alertandredirect("�޴� �߰��� �����߽��ϴ�. ���߿� �ٽ� �õ��Ͻʽÿ�.",true,"/Interface/updatemenuinfoForm.php");
            }           
            break;
        case 'coursedel':
            // �ڽ� ����
            
            if($modifymgr->RequestModifyCourseDishInfo($json))
            {
                 alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                 alertandredirect("�ڽ� ���� ������ �����߽��ϴ�. ���߿� �ٽ� �õ��Ͻʽÿ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
        case 'menudel':
            // �޴� ����
            if($modifymgr->RequestModifyMenuInfo($json))
            {
                 alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                 alertandredirect("�޴� ���� ������ �����߽��ϴ�. ���߿� �ٽ� �õ��Ͻʽÿ�.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
    }
?>