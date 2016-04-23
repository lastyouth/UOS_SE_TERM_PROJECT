<?php
require_once('DefaultUserInfo.php');
/**
 * RegisteredUserInfo short summary.
 *
 * RegisteredUserInfo description.
 *
 * @version 1.0
 * @author sbh
 */
    class RegisteredUserInfo extends DefaultUserInfo
    {
        private $email;
        private $password;
        
        function __construct($a,$b,$c,$d,$email,$password)
        {
            $this->name = $a;
            $this->phonenumber = $b;
            $this->address = $c;
            $this->creditcardnum = $d;
            $this->email = $email;
            $this->password = $password;
        }
        
        function GetEmail()
        {
            return $this->email;
        }
        function GetPassword()
        {
            return $this->password;
        }
        function IsRegistered()
        {
            return true;
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