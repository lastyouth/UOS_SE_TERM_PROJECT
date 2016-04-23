<?php
    require_once('CustomizedCourseDishInfo.php');
    require_once('DefaultUserInfo.php');
/**
 * OrderingInfo short summary.
 *
 * OrderingInfo description.
 *
 * @version 1.0
 * @author sbh
 */
    class OrderingInfo
    {
        private $ccdlist = array();
        private $userinfo;
        private $order_request_time = null;
        private $cooking_start_time = null;
        private $cooking_end_time = null;
        private $delivery_start_time = null;
        private $delivery_end_time = null;
        private $requested_delivered_time;
        
        private $dbkey;
        private $totalcost;
        private $isdiscounted;
        
        function __construct($userinfo,$totalcost,$isdiscounted,$order_request_time,$request_delivery_time)
        {
            $this->userinfo = $userinfo;
            
            $this->totalcost = $totalcost;
            $this->isdiscounted = $isdiscounted;
            $this->requested_delivered_time = $request_delivery_time;
            
            $this->order_request_time = $order_request_time;
        }
        
        // setter
        function SetCookingStartTime($date)
        {
            if($this->cooking_start_time === null)
            {
                $this->cooking_start_time = $date;
                return true;
            }
            return false;
        }
        function SetCookingEndTime($date)
        {
            if($this->cooking_end_time === null)
            {
                $this->cooking_end_time = $date;
                return true;
            }
            return false;
        }
        function SetDeliveryStartTime($date)
        {
            if($this->delivery_start_time === null)
            {
                $this->delivery_start_time = $date;
                return true;
            }
            return false;
        }
        function SetDeliveryEndTime($date)
        {
            if($this->delivery_end_time === null)
            {
                $this->delivery_end_time = $date;
                return true;
            }
            return false;
        }
        function SetDBKey($key)
        {
            if(is_string($key))
            {
                $this->dbkey = $key;
                return true;
            }
            return false;
        }
        // getter
        function GetOrderRequestTime()
        {
            return $this->order_request_time;
        }
        function GetRequestDeliveryTime()
        {
            return $this->requested_delivered_time;
        }
        function GetCookingStartTime()
        {
            return $this->cooking_start_time;
        }
        function GetCookingEndTime()
        {
            return $this->cooking_end_time;
        }
        function GetDeliveryStartTime()
        {
            return $this->delivery_start_time;
        }
        function GetDeliveryEndTime()
        {
            return $this->delivery_end_time;
        }
        function GetDBKey()
        {
            return $this->dbkey;
        }
        function GetTotalCost()
        {
            return $this->totalcost;
        }
        function GetIsDiscounted()
        {
            return $this->isdiscounted;
        }
        function GetCustomizedCourseDishList()
        {
            return $this->ccdlist;
        }
        function GetUserInfo()
        {
            return $this->userinfo;
        }
        
        // method
        
        function AddCustomizedCourseDish($dish)
        {
            if($dish instanceof CustomizedCourseDishInfo)
            {
                array_push($this->ccdlist,$dish);
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