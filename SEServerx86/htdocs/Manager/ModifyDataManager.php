<?php
require_once("config.php");
require_once("Ingredient.php");
require_once("Menu.php");
require_once("CourseDish.php");
require_once("DiscountPolicy.php");
require_once("DBTransactionManager.php");
/**
 * ModifyDataManager short summary.
 *
 * ModifyDataManager description.
 *
 * @version 1.0
 * @author sbh
 */
    class ModifyDataManager
    {
        private static $m_Instance = __CLASS__;
        
        private function __construct()
        {
            
        }
        
        static function GetInstance()
        {
            if(!apc_exists(ModifyDataManager::$m_Instance))
            {
                apc_add(ModifyDataManager::$m_Instance,new ModifyDataManager());
            }
            return apc_fetch(ModifyDataManager::$m_Instance);
        }
        function RequestModifyRegisterationInfo($json)
        {
            // 디비트랜젝션매니저
            $dbmgr = DBTransactionManager::GetInstance();
                
            $name = $json['name'];
            $email = $json['email'];
            $password = $json['password'];
        
            $address = $json['address'];
            $creditcard = $json['creditcard'];
            $phonenumber = $json['phonenumber'];
            
            if(!empty($name)&&!empty($email)&&!empty($password)&&!empty($address)&&!empty($creditcard)&&!empty($phonenumber))
            {
                $userinfo = new RegisteredUserInfo($name,$phonenumber,$address,$creditcard,$email,$password);
                
                if($dbmgr->SetUserInfo($userinfo,OPER_TYPE_MOD))
                {
                    return true;
                }
                return false;
            }
            else
            {
                return false;
            }
        }
        function RequestModifyDiscountPolicy($json)
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $targetcount = $json['ordertime'];
            $discountpercent = $json['discountpercent'];
            
            $newdiscountpolicy = new DiscountPolicy($targetcount,$discountpercent);
            
            if($dbmgr->SetDiscountPolicy($newdiscountpolicy))
            {
                return true;
            }
            return false;            
        }
        function RequestModifyCourseDishInfo($json)
        {
            // 코스 요리 추가
            $oper = $json['type'];
            $dbmgr = DBTransactionManager::GetInstance();
            
            // 메뉴 맵을 만듬
            $menumap = array();
            
            $menulist = $dbmgr->GetMenuInfoList(null);
            
            foreach($menulist as $val)
            {
                $menumap[$val->GetDBKey()] = $val;
            }               
            
            switch($oper)
            {
                case 'courseadd':
                    $name = $json['name'];
                    $description = $json['description'];
                    
                    $newcourse = new CourseDish(0,$name);
                    
                    $newcourse->SetDescription($description);
                    
                    $coursestylelist = $json['coursetype'];
                    
                    // 코스 스타일 추가
                    foreach($coursestylelist as $val)
                    {
                        switch($val)
                        {
                        case MENU_TYPE_SIMPLE:
                            $newcourse->AddAvailableStyle(MENU_TYPE_SIMPLE);
                            break;
                        case MENU_TYPE_DELUXE:
                            $newcourse->AddAvailableStyle(MENU_TYPE_DELUXE);
                            break;
                        case MENU_TYPE_GRAND:
                            $newcourse->AddAvailableStyle(MENU_TYPE_GRAND);
                            break;
                        }
                    }
                    
                    // 코스 메뉴 추가
                    $menulist = $json['menulist'];
                    
                    foreach($menulist as $val)
                    {
                        $dbkey = $val['aid'];
                        $type = $val['atype'];
                        
                        switch($type)
                        {
                        case 'main':
                            $newcourse->AddMainMenu($menumap[$dbkey]);
                            break;
                        case 'sub':
                            $newcourse->AddSubMenu($menumap[$dbkey]);
                            break;
                        }
                        
                    }
                    
                    if($dbmgr->SetCourseDish($newcourse,OPER_TYPE_ADD))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                    break;
                case 'coursedel':
                    if($dbmgr->SetCourseDish(new CourseDish($json['courseinfo'],""),OPER_TYPE_DEL))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                    break;
            }
        }
        function RequestModifyMenuInfo($json)
        {
            $oper = $json['type'];
            $dbmgr = DBTransactionManager::GetInstance();
            
            $ingredientlist = $dbmgr->GetSuppliesInfoList();
            $ingredientmap = array();
            
            foreach ($ingredientlist as $val)
            {
                $ingredientmap[$val->GetDBKey()] = $val;
            }
            
            switch($oper)
            {
            case 'menuadd':
                $name = $json['name'];
                $description = $json['description'];
                $price = $json['price'];
                $newcandidate = $json['ingredientlist'];
                
                $newmenu = new Menu(0,$name,$price);
                
                $newmenu->SetDescription($description);
                
                
                foreach($newcandidate as $val)
                {
                    $newmenu->AddIngredientList(new Ingredient($val['dbkey'],$ingredientmap[$val['dbkey']]->GetName(),$val['amount']));
                }
                if($dbmgr->SetMenu($newmenu,OPER_TYPE_ADD))
                {
                    return true;
                }
                else
                {
                    return false;
                }   
                break;
            case 'menudel':
                if($dbmgr->SetMenu(new Menu($json['menuinfo'],"",0),OPER_TYPE_DEL))
                {
                    return true;
                }
                else
                {
                    return false;
                }
                break;
            }
        }
        function RequestModifyIngredientInfo($json)
        {
            $oper = $json['oper'];
            $dbmgr = DBTransactionManager::GetInstance();
            
            switch($oper)
            {
                case OPER_TYPE_ADD:
                    $newing = new Ingredient(0,$json['name'],$json['quantity']);
                    
                    if($dbmgr->SetIngredient($newing,$oper))
                    {
                        return true;   
                    }
                    else
                    {
                        return false;
                    }                    
                    break;
                case OPER_TYPE_DEL:
                case OPER_TYPE_MOD:
                    $modinfo = json_decode($json['modinfo'],true);
                    
                    $dbkey = $modinfo['id'];
                    $name = $modinfo['name'];
                    $quantity = $json['quantity'];
                    
                    // 수정할 때는 증감값
                    
                    
                    $deltarget = new Ingredient($dbkey,$name,$quantity);
                    
                    if($dbmgr->SetIngredient($deltarget,$oper))
                    {
                        return true;   
                    }
                    else
                    {
                        return false;
                    }
                    
                    break;
            }
        }
    }
?>