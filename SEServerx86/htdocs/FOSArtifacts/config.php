<?php
    // session types
    define("SESSION_TYPE_UNREGISTERED",0x100000);
    define("SESSION_TYPE_REGISTERED",0x100001);
    define("SESSION_TYPE_EMP_DELIVERED",0x100002);
    define("SESSION_TYPE_EMP_COOKED",0x100003);
    define("SESSION_TYPE_MAT",0x100004);
    define("SESSION_TYPE_WRONG",0x100005);
    
    // menu styles
    define("MENU_TYPE_SIMPLE","Simple");
    define("MENU_TYPE_DELUXE","Deluxe");
    define("MENU_TYPE_GRAND","Grand");
    
    // add, mod, del
    define("OPER_TYPE_ADD",0x300000);
    define("OPER_TYPE_MOD",0x300001);
    define("OPER_TYPE_DEL",0x300002);
    
    // registration
    define("REG_ERR_DUP_ID",0x400000);
    define("REG_ERR_DB",0x400001);
    define("REG_ERR_EMPTY",0x400002);
    define("REG_OK",0x400003);
    
    // order
    define("ORDER_ERR_NO_INGREDIENT",0x500000);
    define("ORDER_ERR_PAYMENT",0x500001);
    define("ORDER_ERR_DATABASE",0x500001);
    
    // orderinfotype
    define("ORDER_INFO_PREV_COOK",0x600000);
    define("ORDER_INFO_COOKING",0x600001);
    define("ORDER_INFO_PREV_DELIVER",0x600002);
    define("ORDER_INFO_DELIVERING",0x600003);
   
    
    // format
    define("DATE_FORMAT","Y-m-d H:i:s");
    
    // timezone
    
    date_default_timezone_set('Asia/Seoul');
    
    function alertandredirect($msg,$isredirect,$dest)
    {
        ?>
        <script type="text/javascript">
            alert('<?PHP echo $msg;?>');
            <?PHP
            if($isredirect === true)
            {
            ?>
                window.location.href = '<?PHP echo $dest;?>';
            <?PHP
            }
            ?>
        </script>
        <?PHP
    }
    function redirect($dest)
    {
        ?>
            <script type="text/javascript">
                window.location.href = '<?PHP echo $dest;?>';
            </script>
        <?PHP
    }
?>