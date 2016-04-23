<?php
    require_once('config.php');
    require_once('DBTransactionManager.php');
    require_once('RegisteredSession.php');
    require_once('UnregisteredSession.php');
 /**
 * SessionManager short summary.
 *
 * SessionManager description.
 *
 * @version 1.0
 * @author sbh
 */
    class SessionManager
    {
        private static $m_Instance = __CLASS__;
        private $curSessObj;
        
        private function __construct()
        {
            $this->curSessObj = array();
        }
        
        private function AddSessionObject($sessobj)
        {
            array_push($this->curSessObj,$sessobj);
            apc_store(SessionManager::$m_Instance,$this);
        }
        
        // default as public
        
        static function GetInstance()
        {             
            if(!apc_exists(SessionManager::$m_Instance))
            {
                apc_add(SessionManager::$m_Instance,new SessionManager());
            }
            return apc_fetch(SessionManager::$m_Instance);
        }
        
        function RequestUnregisteredLogin($data)
        {
            $name = $data['name'];   
            $address = $data['address'];
            $creditcard = $data['creditcard'];
            $phonenumber = $data['phonenumber'];
            $sessionid = session_id();
            
            if(!empty($name) && !empty($address) && !empty($creditcard) && !empty($phonenumber))
            {
                $newsession = new UnregisteredSession($sessionid,$name,$phonenumber,$address,$creditcard);
                
                $this->AddSessionObject($newsession);
                return true;
            }
            else
            {
                return false;
            }
        }
        function RequestLogin($id,$pw)
        {
            $dbmgr = DBTransactionManager::GetInstance();
            $sessionid = session_id();
            $ip = $_SERVER['REMOTE_ADDR'];
            // validate
            if($id === null || $pw === null)
            {
                return false;
            }
            $employee_ret = $dbmgr->CheckEmployeeValidate($id,$pw);
            if($employee_ret !== SESSION_TYPE_WRONG)
            {
                if($dbmgr->CheckIpValidate('192.168.0.1'))
                {
                    $this->AddSessionObject(new RegisteredSession($sessionid,$id,$employee_ret));       
                    
                    return true;
                }
                else
                {
                    return false;
                }
            }            
            if($dbmgr->CheckUserValidate($id,$pw))
            {
                $this->AddSessionObject(new RegisteredSession($sessionid,$id,SESSION_TYPE_REGISTERED));       
                
                return true;
            }
            return false;
        }
        function GetSessionObject($sessionid)
        {
            foreach($this->curSessObj as $sessobj)
            {
                $sessid = $sessobj->GetSessionId();
                
                if($sessid === $sessionid)
                {
                    return $sessobj;
                }
            }
            return null;
        }
        function DeleteSessionObject($sessionid)
        {
            foreach($this->curSessObj as $key=>$sessobj)
            {
                $sessid = $sessobj->GetSessionId();
                
                if($sessid === $sessionid)
                {
                    unset($this->curSessObj[$key]);
                    apc_store(SessionManager::$m_Instance,$this);
                    return true;
                }

            }
            return false;
        }
        function CheckCurrentSessionValidate($sessionid)
        {
            foreach($this->curSessObj as $sessobj)
            {
                $sessid = $sessobj->GetSessionId();
 
                if($sessid === $sessionid)
                {
                    return true;
                }
            }
            return false;
        }
        // debug
        function temp_dbg_getSessionObjList()
        {
            return $this->curSessObj;
        }
    }
?>