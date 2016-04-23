<?php
    require_once('config.php');
    require_once('ModifyDataManager.php');
    
    $modmgr = ModifyDataManager::GetInstance();
    
    
    
    if($modmgr->RequestModifyDiscountPolicy($_POST))
    {
        alertandredirect("반영되었습니다.",true,"/Interface/discountpolicyForm.php");
    }
    else
    {
        alertandredirect("할인 정책을 적용하지 못했습니다.",true,"/Interface/discountpolicyForm.php");
    }
?>