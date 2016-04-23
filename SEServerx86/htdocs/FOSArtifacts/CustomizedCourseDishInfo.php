<?php

/**
 * CustomizedCourseDishInfo short summary.
 *
 * CustomizedCourseDishInfo description.
 *
 * @version 1.0
 * @author sbh
 */
    class CustomizedCourseDishInfo
    {
        private $stylename;
        private $menu_names;
        private $origin_course_name;
        private $dbkey;
        
        function __construct($style,$origin_course_name,$dbkey)
        {
            $this->stylename = $style;
            $this->origin_course_name = $origin_course_name;
            $this->dbkey = $dbkey;
        }
        
        function GetDBKey()
        {
            return $this->dbkey;
        }
        function GetMenuNameList()
        {
            return $this->menu_names;
        }
        function GetOriginCourseName()
        {
            return $this->origin_course_name;
        }
        function SetMenuNameList($list)
        {
            if(is_array($list))
            {
                $this->menu_names = $list;
                return true;
            }
            return false;
        }
        function GetStyle()
        {
            return $this->stylename;
        }
        //method
        function GetJsonData()
        {
            $array = get_object_vars($this);
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