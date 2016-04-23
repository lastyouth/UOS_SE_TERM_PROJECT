<?PHP
    require_once("config.php");
    require_once("ModifyDataManager.php");
   
    // 재료 추가 수정 삭제를 요청 합니다.
    // 이 페이지는 
    
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
                alertandredirect("반영되었습니다.",true,"/Interface/updatesuppliesinfoForm.php");
            }
            else
            {
                alertandredirect("삭제할 수 없습니다.",true,"/Interface/updatesuppliesinfoForm.php");
            }
        }
        else
        {
            $json['oper'] = OPER_TYPE_MOD;
            
            if($modifymgr->RequestModifyIngredientInfo($json))
            {
                alertandredirect("반영되었습니다.",true,"/Interface/updatesuppliesinfoForm.php");
            }
            else
            {
                alertandredirect("그렇게 수정할 수 없습니다.",true,"/Interface/updatesuppliesinfoForm.php");
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
            alertandredirect("반영되었습니다.",true,"/Interface/updatesuppliesinfoForm.php");
        }
        else
        {
            alertandredirect("중복된 재료입니다.",true,"/Interface/updatesuppliesinfoForm.php");
        }        
    }
    //redirect("/Interface/updatesuppliesinfoForm.php");
?>