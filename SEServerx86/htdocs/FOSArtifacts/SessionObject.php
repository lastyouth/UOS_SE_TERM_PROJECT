<?php

/**
 * SessionObject short summary.
 *
 * SessionObject description.
 *
 * @version 1.0
 * @author sbh
 */
class SessionObject
{
    protected $type;
    protected $sessionid;
    
    protected function __construct()
    {
    }
    function GetType()
    {
        return $this->type;
    }
    function GetSessionId()
    {
        return $this->sessionid;
    }
}
?>
