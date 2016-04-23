<?php
require_once("DiscountPolicy.php");
require_once("DBTransactionManager.php");

/**
 * JSONOutputManager short summary.
 *
 * JSONOutputManager description.
 *
 * @version 1.0
 * @author sbh
 */
    class JSONOutputManager
    {
        private static $m_Instance = __CLASS__;
        
        private function __construct()
        {
            
        }
        
        static function GetInstance()
        {
            if(!apc_exists(JSONOutputManager::$m_Instance))
            {
                apc_add(JSONOutputManager::$m_Instance,new JSONOutputManager());
            }
            return apc_fetch(JSONOutputManager::$m_Instance);
        }
        function RequestGetDiscountPolicy()
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $discountpolicy = $dbmgr->GetDiscountPolicy();
            
            
            return $discountpolicy->GetJsonData();
        }
        function RequestGetSuppliesInfoList()
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $ingredientlist = $dbmgr->GetSuppliesInfoList(null);
            
            $ret = array();     
                        
            foreach($ingredientlist as $v)
            {
                array_push($ret,$v->GetJsonData());
            }
            
            return $ret;
        }
        function RequestGetMenuInfoList()
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $menulist = $dbmgr->GetMenuInfoList(null);
            
            $ret = array();     
            
            foreach($menulist as $v)
            {
                array_push($ret,$v->GetJsonData());
            }
            
            return $ret;
        }
        function RequestGetCourseDishInfoList()
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $menulist = $dbmgr->GetCourseDishList();
            
            $val = $dbmgr->GetErrorMessage();
            
            $ret = array();     
            
            foreach($menulist as $v)
            {
                array_push($ret,$v->GetJsonData());
            }
            
            return $ret;
        }
        function RequestGetOrderingInfoList($id,$count)
        {
            $dbmgr = DBTransactionManager::GetInstance();
                
            $orderedlist = $dbmgr->GetOrderingInfoList($id,$count);
            
            $ret = array();
            
            foreach($orderedlist as $v)
            {
                array_push($ret,$v->GetJsonData());
            }
            
            return $ret;
        }
        function RequestGetUserInfo($id)
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $userinfo = $dbmgr->GetUserInfo($id);
            
                       
            return $userinfo;
        }
        function RequestGetUserOrderingCount($id)
        {
            $dbmgr = DBTransactionManager::GetInstance();
            
            $ordercount = $dbmgr->GetOrderingCount($id);
            
                        
            return $ordercount;            
        }
    }
?>