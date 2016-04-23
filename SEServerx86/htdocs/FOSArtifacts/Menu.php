<?php
require_once('Ingredient.php');
/**
 * Menu short summary.
 *
 * Menu description.
 *
 * @version 1.0
 * @author sbh
 */
    class Menu
    {
        private $dbkey;
        private $name;
        private $Ingredient_list;
        private $description = null;
        private $cost;
        
        function __construct($dbkey,$name,$cost)
        {
            $this->dbkey = $dbkey;
            $this->name = $name;
            $this->cost = $cost;
            $this->Ingredient_list = array();
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
        function GetIngredientList()
        {
            return $this->Ingredient_list;
        }
        function GetDescription()
        {
            return $this->description;
        }
        function GetCost()
        {
            return $this->cost;
        }
        // setter
        public function SetDescription($dis)
        {
            if(is_string($dis))
            {
                $this->description = $dis;
                return true;
            }
            return false;
        }
        
        // method
        
        function AddIngredientList($ingredient)
        {
            if($ingredient instanceof Ingredient)
            {
                array_push($this->Ingredient_list,$ingredient);
                return true;
            }
            return false;
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