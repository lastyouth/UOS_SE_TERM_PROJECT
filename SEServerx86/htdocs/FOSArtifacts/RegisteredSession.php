<?php
    require_once('config.php');
    require_once('SessionObject.php');
/**
 * RegisteredSession short summary.
 *
 * RegisteredSession description.
 *
 * @version 1.0
 * @author sbh
 */
    class RegisteredSession extends SessionObject
    {
        private $email;
        
        function __construct($sessionid,$email,$type)
        {
            $this->sessionid = $sessionid;
            $this->email = $email;
            $this->type = $type;
        }
        function GetEmail()
        {
            return $this->email;
        }
    }

?>
