<?php
require_once('config.php');
require_once('SessionObject.php');
require_once('DefaultUserInfo.php');
/**
 * UnregisteredSession short summary.
 *
 * UnregisteredSession description.
 *
 * @version 1.0
 * @author sbh
 */
    class UnregisteredSession extends SessionObject
    {
        private $info;
        
        function __construct($sessid,$a,$b,$c,$d)
        {
            $this->sessionid = $sessid;
            $this->info = new DefaultUserInfo($a,$b,$c,$d);
            
            $this->type = SESSION_TYPE_UNREGISTERED;
        }
        
        function GetType()
        {
            return $this->type;
        }
        function GetDefaultUserInfo()
        {
            return $this->info;
        }
    }
?>