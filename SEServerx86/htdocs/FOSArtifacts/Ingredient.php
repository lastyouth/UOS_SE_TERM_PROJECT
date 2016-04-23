<?php

/**
 * Ingredient short summary.
 *
 * Ingredient description.
 *
 * @version 1.0
 * @author sbh
 */
    class Ingredient
    {
        private $dbkey;
        private $name;
        private $val;
        function __construct($dbkey,$name,$val = 0)
        {
            if($dbkey instanceof Ingredient)
            {
                $this->dbkey = $dbkey->GetDBKey();
                $this->name = $dbkey->GetName();
                $this->val = $name;   
            }
            else
            {
                $this->dbkey = $dbkey;
                $this->name = $name;
                $this->val = $val;
            }
        }
        function GetDBKey()
        {
            return $this->dbkey;
        }
        function GetName()
        {
            return $this->name;
        }
        function GetVal()
        {
            return $this->val;
        }
        
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