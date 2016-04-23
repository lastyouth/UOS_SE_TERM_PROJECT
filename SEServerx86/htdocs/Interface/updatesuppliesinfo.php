<?PHP
    require_once("config.php");
    require_once("ModifyDataManager.php");
   
    // ��� �߰� ���� ������ ��û �մϴ�.
    // �� �������� 
    
    $modifymgr = ModifyDataManager::GetInstance();
    
    if(isset($_POST['modinfo']))
    {
        $json = array();
        
        $json['modinfo'] = $_POST['modinfo'];
        $json['quantity'] = $_POST['mod-num'];
        
        if(isset($_POST['deletecheck']))
        {
            //delete
            $json['oper'] = OPER_TYPE_DEL;
            if($modifymgr->RequestModifyIngredientInfo($json))
            {
                alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
            }
            else
            {
                alertandredirect("������ �� �����ϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
            }
        }
        else
        {
            $json['oper'] = OPER_TYPE_MOD;
            
            if($modifymgr->RequestModifyIngredientInfo($json))
            {
                alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
            }
            else
            {
                alertandredirect("�׷��� ������ �� �����ϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
            }
        }
    }
    else
    {
        $json = array();
        $json['name'] = $_POST['supply-name'];
        $json['quantity'] = $_POST['supply-quantity'];
        
        $json['oper'] = OPER_TYPE_ADD;
        
        if($modifymgr->RequestModifyIngredientInfo($json))
        {
            alertandredirect("�ݿ��Ǿ����ϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
        }
        else
        {
            alertandredirect("�ߺ��� ����Դϴ�.",true,"/Interface/updatesuppliesinfoForm.php");
        }        
    }
    //redirect("/Interface/updatesuppliesinfoForm.php");
?>