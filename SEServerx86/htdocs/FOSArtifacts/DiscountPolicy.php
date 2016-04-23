<?php

/**
 * DiscountPolicy short summary.
 *
 * DiscountPolicy description.
 *
 * @version 1.0
 * @author sbh
 */
    class DiscountPolicy
    {
        private $target_ordercount;
        private $discount_percent;
        
        function __construct($to,$dp)
        {
            $this->target_ordercount = $to;
            $this->discount_percent = $dp;
        }
        
        function GetTargetOrderCount()
        {
            return $this->target_ordercount;
        }
        function GetDiscountPercent()
        {
            return $this->discount_percent;
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