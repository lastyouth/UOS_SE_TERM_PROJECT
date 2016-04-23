<?php
require_once('config.php');
require_once('Menu.php');
/**
 * CourseDish short summary.
 *
 * CourseDish description.
 *
 * @version 1.0
 * @author sbh
 */
    class CourseDish
    {
        private $dbkey;
        private $name;
        private $main_menu_list;
        private $sub_menu_list;
        private $style_list;
        private $description = null;
        
        function __construct($dbkey,$name)
        {
            $this->dbkey = $dbkey;
            $this->name = $name;
            $this->main_menu_list = array();
            $this->sub_menu_list = array();
            $this->style_list = array();
        }
        
        
        // getter
        function GetDBKey()
        {
            return $this->dbkey;
        }
        function GetName()
        {
            return $this->name;
        }
        function GetMainMenuList()
        {
            return $this->main_menu_list;
        }
        function GetSubMenuList()
        {
            return $this->sub_menu_list;
        }
        function GetDescription()
        {
            return $this->description;            
        }
        function GetStyleList()
        {
            return $this->style_list;
        }
        function GetTotalCost()
        {
            $ret = 0;
            
            foreach($this->main_menu_list as $mm)
            {
                $ret+= $mm->GetCost();
            }
            foreach($this->sub_menu_list as $sm)
            {
                $ret+= $sm->GetCost();
            }
            return $ret;
        }
        function SetDescription($dis)
        {
            if(is_string($dis))
            {
                $this->description = $dis;
                return true;
            }
            return false;
        }
        // method
        
        function AddMainMenu($menu)
        {
            if($menu instanceof Menu)
            {
                array_push($this->main_menu_list,$menu);
                return true;
            }
            return false;
        }
        function AddSubMenu($menu)
        {
            if($menu instanceof Menu)
            {
                array_push($this->sub_menu_list,$menu);
                return true;
            }
            return false;
        }
        function AddAvailableStyle($name)
        {
            if($name === MENU_TYPE_SIMPLE || $name === MENU_TYPE_DELUXE || $name === MENU_TYPE_GRAND)
            {
                array_push($this->style_list,$name);
                return true;
            }
            return false;
        }
        function GetJsonData()
        {
            $array = get_object_vars($this);
            $array['totalprice'] = $this->GetTotalCost();
            unset($array['_parent'], $array['_index']);
            array_walk_recursive($array, function(&$property, $key){
                if(is_object($property)
                && method_exists($property, 'GetJsonData')){
                    $property = $property->GetJsonData();
                }
            });
            return $array;
        }
    }
?>