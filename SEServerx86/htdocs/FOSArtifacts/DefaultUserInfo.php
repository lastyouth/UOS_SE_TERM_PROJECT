<?php
/**
 * DefaultUserInfo short summary.
 *
 * DefaultUserInfo description.
 *
 * @version 1.0
 * @author sbh
 */
    class DefaultUserInfo
    {
        protected $name;
        protected $phonenumber;
        protected $address;
        protected $creditcardnum;
        
        function __construct($name,$phonenumber,$address,$creaditcardnum)
        {
            $this->name = $name;
            $this->phonenumber = $phonenumber;
            $this->address = $address;
            $this->creditcardnum = $creaditcardnum;
        }
        
        function IsRegistered()
        {
            return false;
        }
        
        function GetName()
        {
            return $this->name;
        }
        function GetPhoneNumber()
        {
            return $this->phonenumber;
        }
        function GetAddress()
        {
            return $this->address;            
        }
        function GetCreditcardNum()
        {
            return $this->creditcardnum;
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