<?PHP
    require_once("config.php");
    require_once("ModifyDataManager.php");

    $json = json_decode($_POST['jsondata'],true);
    $modtype = $json['type'];
    
    $modifymgr = ModifyDataManager::GetInstance();
    
    
    switch($modtype)
    {
        case 'courseadd':
            // 코스 추가  
            $json['name'] = $_POST['name'];
            $json['description'] = $_POST['description'];
            
            if($modifymgr->RequestModifyCourseDishInfo($json))
            {
                alertandredirect("반영되었습니다.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                alertandredirect("코스 추가에 실패했습니다. 나중에 다시 시도하십시오.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
        case 'menuadd':
            // 메뉴 추가
            $json['name'] = $_POST['name'];
            $json['description'] = $_POST['description'];
            $json['price'] = $_POST['price'];
            
            if($modifymgr->RequestModifyMenuInfo($json))
            {
                alertandredirect("반영되었습니다.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                alertandredirect("메뉴 추가에 실패했습니다. 나중에 다시 시도하십시오.",true,"/Interface/updatemenuinfoForm.php");
            }           
            break;
        case 'coursedel':
            // 코스 삭제
            
            if($modifymgr->RequestModifyCourseDishInfo($json))
            {
                 alertandredirect("반영되었습니다.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                 alertandredirect("코스 정보 삭제에 실패했습니다. 나중에 다시 시도하십시오.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
        case 'menudel':
            // 메뉴 삭제
            if($modifymgr->RequestModifyMenuInfo($json))
            {
                 alertandredirect("반영되었습니다.",true,"/Interface/updatemenuinfoForm.php");
            }
            else
            {
                 alertandredirect("메뉴 정보 삭제에 실패했습니다. 나중에 다시 시도하십시오.",true,"/Interface/updatemenuinfoForm.php");
            }
            break;
    }
?>