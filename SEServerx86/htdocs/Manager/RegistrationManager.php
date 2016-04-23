<?php
    require_once("config.php");
    require_once("RegisteredUserInfo.php");
    require_once("DBTransactionManager.php");
/**
 * RegistrationManager short summary.
 *
 * RegistrationManager description.
 *
 * @version 1.0
 * @author sbh
 */
    class RegistrationManager
    {
        private static $m_Instance = __CLASS__;
    
        private function __construct()
        {
        
        }
    
        static function GetInstance()
        {
            if(!apc_exists(RegistrationManager::$m_Instance))
            {
                apc_add(RegistrationManager::$m_Instance,new RegistrationManager());
            }
            return apc_fetch(RegistrationManager::$m_Instance);
        }
        function RequestRegistration($json)
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
                if($dbmgr->CheckUserIdDuplicated($email))
                {
                    return REG_ERR_DUP_ID;
                }
                
                $userinfo = new RegisteredUserInfo($name,$phonenumber,$address,$creditcard,$email,$password);
                
                if($dbmgr->SetUserInfo($userinfo,OPER_TYPE_ADD))
                {
                    return REG_OK;
                }
                else
                {
                    return REG_ERR_DB;   
                }           
            }
            else
            {
                return REG_ERR_EMPTY;   
            }    
        }
    }

?>