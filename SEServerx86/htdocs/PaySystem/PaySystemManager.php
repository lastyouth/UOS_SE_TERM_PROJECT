<?php
//require_once('config.php');
/**
 * PaySystemManager short summary.
 *
 * PaySystemManager description.
 *
 * @version 1.0
 * @author sbh
 */
    class PaySystemManager
    {
        private static $m_Instance = __CLASS__;
        
        private function __construct()
        {
            
        }
        
        static function GetInstance()
        {
            if(!apc_exists(PaySystemManager::$m_Instance))
            {
                apc_add(PaySystemManager::$m_Instance,new PaySystemManager());
            }
            return apc_fetch(PaySystemManager::$m_Instance);
        }
        function RequestPayment($cardnum,$cost)
        {
            return true;
        }
        function RequestCancelPayment($cardnum)
        {
            return true;
        }
    }
?>
