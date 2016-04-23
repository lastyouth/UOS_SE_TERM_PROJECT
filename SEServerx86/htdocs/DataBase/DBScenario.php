<?PHP
    require_once("config.php");
    require_once("DBTransactionManager.php");
    $dbt = DBTransactionManager::GetInstance();
    
    $dbt->dropAlltables();
    $dbt->createDefaultTables();
    
    $print = $dbt->CheckIpValidate('192.123.0.1');
    print(var_export($print));
    
   
    $potato = new Ingredient(1,"감자",12800);
    $dbt->SetIngredient($potato,OPER_TYPE_ADD);
    $onion = new ingredient(2,"양파",12800);
    $dbt->SetIngredient($onion,OPER_TYPE_ADD);
    $broccoli = new Ingredient(3,"브로콜리",12800);
    $dbt->SetIngredient($broccoli,OPER_TYPE_ADD);
    $beef_sirloin = new Ingredient(4,"쇠고기_서로인",30000);
    $dbt->SetIngredient($beef_sirloin,OPER_TYPE_ADD);
    $carrot = new Ingredient(5,"당근",12800);
    $dbt->SetIngredient($carrot,OPER_TYPE_ADD);
    $chicken_leg = new Ingredient(6,"닭고기_닭다리",30000);
    $dbt->SetIngredient($chicken_leg,OPER_TYPE_ADD);
    $chicken_breast = new Ingredient(7,"닭고기_닭가슴살",30000);
    $dbt->SetIngredient($chicken_breast,OPER_TYPE_ADD);
    $mashroom = new Ingredient(8,"양송이버섯",12800);
    $dbt->SetIngredient($mashroom,OPER_TYPE_ADD);
    $lettuce = new Ingredient(9,"양상추",12800);
    $dbt->SetIngredient($lettuce,OPER_TYPE_ADD);
    $wine_shaddo = new Ingredient(10,"샤또 레오 드 뽕떼",30000);
    $dbt->SetIngredient($wine_shaddo,OPER_TYPE_ADD);
    $wine_kkubzak = new Ingredient(11,"꾸브작 쌩때밀리옹",40000);
    $dbt->SetIngredient($wine_kkubzak,OPER_TYPE_ADD);
    $pork_sirloin = new Ingredient(12,"돼지고기_서로인",30000);
    $dbt->SetIngredient($pork_sirloin,OPER_TYPE_ADD);
    $pork_tenderloin = new Ingredient(13,"돼지고기_안심",30000);
    $dbt->SetIngredient($pork_tenderloin,OPER_TYPE_ADD);
    $beef_tenderloin = new Ingredient(14,"쇠고기_안심",30000);
    $dbt->SetIngredient($beef_tenderloin,OPER_TYPE_ADD);
    $paprika = new Ingredient(15,"파프리카",40000);
    $dbt->SetIngredient($paprika,OPER_TYPE_ADD);
    
    
    $temp = array();
    $temp = $dbt->GetSuppliesInfoList();
    print($temp);
    
    
    $onion_potato_menu = new Menu(1,"어니언 트위스트",14400);
    
    $onion_potato_menu->SetDescription("유기농 감자와 양파가 어우러진 맛있는 어니언 트위스트입니다.");
    
    $onion_potato_menu->AddIngredientList(new Ingredient($potato,440));
    $onion_potato_menu->AddIngredientList(new Ingredient($onion,500));
    
    $dbt->SetMenu($onion_potato_menu,OPER_TYPE_ADD);
    
    $seroin_stake_menu = new Menu(2,"서로인 스테이크",25000);
    
    $seroin_stake_menu->SetDescription("호주산 소고기의 최고급 부위인 서로인을 사용하여 Mr.Mat 특제 시즈닝을 첨가하여 직화로 구워낸 스테이크 입니다. 가니쉬로 브로콜리와 양송이가 제공됩니다.");
    
    $seroin_stake_menu->AddIngredientList(new Ingredient($beef_sirloin,380));
    $seroin_stake_menu->AddIngredientList(new Ingredient($broccoli,200));
    $seroin_stake_menu->AddIngredientList(new Ingredient($mashroom,100));
    
    $dbt->SetMenu($seroin_stake_menu,OPER_TYPE_ADD);
    
    
    $chicken_breast_salad_menu = new Menu(3,"닭가슴살 샐러드",8900);
    
    $chicken_breast_salad_menu->SetDescription("신선한 닭가슴살과 양상추, 그리고 각종 야채가 어우러진 풍성한 샐러드입니다.");
    
    $chicken_breast_salad_menu->AddIngredientList(new Ingredient($chicken_breast,400));
    $chicken_breast_salad_menu->AddIngredientList(new Ingredient($lettuce,280));
    $chicken_breast_salad_menu->AddIngredientList(new Ingredient($carrot,280));
    
    $dbt->SetMenu($chicken_breast_salad_menu,OPER_TYPE_ADD);
    
    $mashroom_soup_menu = new Menu(4,"양송이 슾",4500);
    $mashroom_soup_menu->SetDescription("양송이와 양파가 들어간 풍부한 맛을 자랑하는 슾입니다.");
    $mashroom_soup_menu->AddIngredientList(new Ingredient($mashroom,100));
    $mashroom_soup_menu->AddIngredientList(new Ingredient($onion,50));
    
    $dbt->SetMenu($mashroom_soup_menu,OPER_TYPE_ADD);

    //$dbt->SetMenu($mashroom_soup_menu,OPER_TYPE_MOD);
    //$dbt->SetMenu($mashroom_soup_menu,OPER_TYPE_DEL);

    $chicken_leg_fired_menu = new Menu(5,"쿠카부라 파이어드 레그프레스",11900);
    $chicken_leg_fired_menu->SetDescription("풍부한 육질을 자랑하는 쿠카부라 레그! 직화구이로 그 맛을 한층 더 깊게 합니다.");
    $chicken_leg_fired_menu->AddIngredientList(new Ingredient($chicken_leg,400));
    
    $dbt->SetMenu($chicken_leg_fired_menu,OPER_TYPE_ADD);
    
    $wine_shaddo_menu = new Menu(6,"샤또 레오 드 뽕떼",32000);
    $wine_shaddo_menu->SetDescription("감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!");
    $wine_shaddo_menu->AddIngredientList(new Ingredient($wine_shaddo,800));
    
    $dbt->SetMenu($wine_shaddo_menu,OPER_TYPE_ADD);
    
    $wine_kkubzak_menu = new Menu(7,"꾸브작 쌩때밀리옹",45000);
    $wine_kkubzak_menu->SetDescription("나폴레옹이 즐겨 마셨다는, 보르도 산 특별 와인! 당신의 품격을 높혀드립니다.");
    $wine_kkubzak_menu->AddIngredientList(new Ingredient($wine_kkubzak,1000));
    
    $dbt->SetMenu($wine_kkubzak_menu,OPER_TYPE_ADD);
    
    $beef_tenderloin_menu = new Menu(8,"쇠고기 안심 스테이크",32000);
    $beef_tenderloin_menu->SetDescription("호주산 1등급 쇠고기 안심을 엄선하여 직화로 구운 스테이크입니다.");
    $beef_tenderloin_menu->AddIngredientList(new Ingredient($beef_tenderloin,350));
    $beef_tenderloin_menu->AddIngredientList(new Ingredient($paprika,200));
    $beef_tenderloin_menu->AddIngredientList(new Ingredient($mashroom,150));
    
    $dbt->SetMenu($beef_tenderloin_menu,OPER_TYPE_ADD);
    
    $pork_chop_stake_menu = new Menu(9,"찹 스테이크",15000);
    $pork_chop_stake_menu->SetDescription("돼지고기 안심과 등심을 한입 크기로 손질하여, 특제 앙념과 함께 구워낸 맛있는 요리입니다.");
    $pork_chop_stake_menu->AddIngredientList(new Ingredient($pork_sirloin,150));
    $pork_chop_stake_menu->AddIngredientList(new Ingredient($pork_tenderloin,150));
    $pork_chop_stake_menu->AddIngredientList(new Ingredient($onion,100));
    $pork_chop_stake_menu->AddIngredientList(new Ingredient($paprika,100));
    
    $dbt->SetMenu($pork_chop_stake_menu,OPER_TYPE_ADD);
    
    $espanol_stew_menu = new Menu(10,"에스빠뇰 스튜",18900);
    $espanol_stew_menu->SetDescription("에스빠뇰 스타일의 스튜로, 닭고기와 돼지고기가 함께 들어가는것이 특징입니다.");
    $espanol_stew_menu->AddIngredientList(new Ingredient($chicken_leg,120));
    $espanol_stew_menu->AddIngredientList(new Ingredient($pork_sirloin,120));
    $espanol_stew_menu->AddIngredientList(new Ingredient($mashroom,100));
    $espanol_stew_menu->AddIngredientList(new Ingredient($carrot,50));
    
    $dbt->SetMenu($espanol_stew_menu,OPER_TYPE_ADD);
    
    $cocobang_menu = new Menu(11,"코코뱅",50000);
    $cocobang_menu->SetDescription("프랑스에서 일요일마다 먹는 닭 요리로, 샤또 레오 드 뽕떼 와인이 한 병 통째로 들어갑니다.");
    $cocobang_menu->AddIngredientList(new Ingredient($chicken_leg,200));
    $cocobang_menu->AddIngredientList(new Ingredient($chicken_breast,200));
    $cocobang_menu->AddIngredientList(new Ingredient($wine_shaddo,800));
    $cocobang_menu->AddIngredientList(new Ingredient($mashroom,100));
    $cocobang_menu->AddIngredientList(new Ingredient($onion,100));
    
    $dbt->SetMenu($cocobang_menu,OPER_TYPE_ADD);
    
    
    $american_supervised_course = new CourseDish(1,"아메리칸 슈퍼바이저 컨펌드 디너");
    $american_supervised_course->AddAvailableStyle(MENU_TYPE_SIMPLE);
    $american_supervised_course->AddAvailableStyle(MENU_TYPE_DELUXE);
    
    $american_supervised_course->AddMainMenu($seroin_stake_menu);
    $american_supervised_course->AddSubMenu($onion_potato_menu);
    $american_supervised_course->AddSubMenu($chicken_breast_salad_menu);
    $american_supervised_course->AddSubMenu($wine_shaddo_menu);
    $american_supervised_course->SetDescription("당신의 상사가 승인한 아메리칸 스타일 디너!!");
    
    
    $dbt->SetCourseDish($american_supervised_course,OPER_TYPE_ADD);
    
    $simple_wine_dinner_course = new CourseDish(2,"치킨 앤 샤또");
    $simple_wine_dinner_course->AddAvailableStyle(MENU_TYPE_SIMPLE);
    $simple_wine_dinner_course->AddAvailableStyle(MENU_TYPE_DELUXE);
    $simple_wine_dinner_course->AddAvailableStyle(MENU_TYPE_GRAND);
    
    $simple_wine_dinner_course->AddMainMenu($chicken_breast_salad_menu);
    $simple_wine_dinner_course->AddMainMenu($chicken_leg_fired_menu);
    $simple_wine_dinner_course->AddSubMenu($wine_shaddo_menu);
    $simple_wine_dinner_course->AddSubMenu($wine_shaddo_menu);
    
    $simple_wine_dinner_course->SetDescription("샤또 레오 드 뽕떼와 치킨은 최고의 파트너!");
    
    $dbt->SetCourseDish($simple_wine_dinner_course,OPER_TYPE_ADD);
    
    
    $cocobang_with_friends_course = new CourseDish(3,"코코뱅 앤 데얼 프렌즈");
    $cocobang_with_friends_course->AddAvailableStyle(MENU_TYPE_SIMPLE);
    $cocobang_with_friends_course->AddAvailableStyle(MENU_TYPE_DELUXE);
    
    $cocobang_with_friends_course->AddMainMenu($cocobang_menu);
    $cocobang_with_friends_course->AddMainMenu($espanol_stew_menu);
    
    $cocobang_with_friends_course->AddSubMenu($wine_kkubzak_menu);
    $cocobang_with_friends_course->AddSubMenu($mashroom_soup_menu);
    $cocobang_with_friends_course->AddSubMenu($chicken_breast_salad_menu);
    
    $cocobang_with_friends_course->SetDescription("코코뱅과 잘 어울리는 메뉴만 엄선한 코코뱅과 그의 친구들!");
    
    
    $dbt->SetCourseDish($cocobang_with_friends_course,OPER_TYPE_ADD);
    
    
    $all_in_one_party_course = new CourseDish(4,"올-인원 프리페어 포 굿 파티");
    $all_in_one_party_course->AddAvailableStyle(MENU_TYPE_SIMPLE);
    $all_in_one_party_course->AddAvailableStyle(MENU_TYPE_GRAND);
    
    $all_in_one_party_course->AddMainMenu($cocobang_menu);
    $all_in_one_party_course->AddMainMenu($seroin_stake_menu);
    $all_in_one_party_course->AddMainMenu($beef_tenderloin_menu);
    
    $all_in_one_party_course->AddSubMenu($onion_potato_menu);
    $all_in_one_party_course->AddSubMenu($chicken_leg_fired_menu);
    $all_in_one_party_course->AddSubMenu($mashroom_soup_menu);
    $all_in_one_party_course->AddSubMenu($mashroom_soup_menu);
    $all_in_one_party_course->AddSubMenu($wine_kkubzak_menu);
    $all_in_one_party_course->AddSubMenu($wine_shaddo_menu);
    
    $all_in_one_party_course->SetDescription("즐거운 파티를 위한 선결 조건! 이 코스요리로 모든 걱정을 해결하십시오.");
    
    $dbt->SetCourseDish($all_in_one_party_course,OPER_TYPE_ADD);
    
    $vv = array();
    $vv = $dbt->GetCourseDishList();
    print($vv);
   
    
    $user1 = new RegisteredUserInfo("아무개","010-1001-1111","서울시","12345","abc@mail.com","1234");
    $dbt->SetUserInfo($user1,OPER_TYPE_ADD);
    
    $user2 = new RegisteredUserInfo("가나다","112-1020-1011","경기도","40403-1030","naver@naver.com","1234");
    $dbt->SetUserInfo($user2,OPER_TYPE_ADD);
    
    $dish1 = new CustomizedCourseDishInfo(MENU_TYPE_DELUXE,"아메리칸 슈퍼바이즈 컨펌드 디너","1");
    $dish1_list = array("2","6","1","1","3","4","4");
    $dish1->SetMenuNameList($dish1_list);
    
    $dish2 = new CustomizedCourseDishInfo(MENU_TYPE_GRAND,"치킨 앤 샤또","2");
    $dish2_list = array("4","4","1","5","5");
    $dish2->SetMenuNameList($dish1_list);
    
    
    $order = new OrderingInfo($user1,"140000",false,date(DATE_FORMAT),"2014-10-10 11:11:11");
    $order->AddCustomizedCourseDish($dish1);
    $order->AddCustomizedCourseDish($dish2);
    
    $dbt->SetOrderingInfo($order,OPER_TYPE_ADD,$new_key);
    $order->SetDBKey($new_key);
    
    print($dbt->GetOrderingCount("abc@mail.com"));
    
    $temp_array = array();
    $temp_array = $dbt->GetOrderingInfoList("abc@mail.com",5);
    
    
    $dish3 = new CustomizedCourseDishInfo(MENU_TYPE_GRAND,"코코뱅 앤 데얼 프렌즈","3");
    $dish3_list = array("4","3","1","3","1");
    $dish3->SetMenuNameList($dish3_list);
    
    
    $dish4 = new CustomizedCourseDishInfo(MENU_TYPE_GRAND,"코코뱅 앤 데얼 프렌즈","3");
    $dish4_list = array("4","1","1");
    $dish4->SetMenuNameList($dish4_list);
    
    $dish5 = new CustomizedCourseDishInfo(MENU_TYPE_GRAND,"올-인원 프리페어 포 굿 파티","4");
    $dish5_list = array("7","3","1","3","1");
    $dish5->SetMenuNameList($dish5_list);
    
    
    $order2 = new OrderingInfo($user2,"1800000",true,date(DATE_FORMAT),"2015-10-10 12:32:00");
    $order2->AddCustomizedCourseDish($dish3);
    $order2->AddCustomizedCourseDish($dish4);
    $order2->AddCustomizedCourseDish($dish5);
    
    $dbt->SetOrderingInfo($order2,OPER_TYPE_ADD,$new_key);
    $order2->SetDBKey($new_key);
    
    print($dbt->GetOrderingCount("naver@naver.com"));
    
    $temp_array = array();
    $temp_array = $dbt->GetOrderingInfoList("naver@naver.com",5);
    
    
    $dish6 = new CustomizedCourseDishInfo(MENU_TYPE_GRAND,"코코뱅 앤 데얼 프렌즈","2");
    $dish6_list = array("4","7","7","3","1");
    $dish6->SetMenuNameList($dish6_list);
    
    $order2->AddCustomizedCourseDish($dish6);
    
    $dbt->SetOrderingInfo($order2,OPER_TYPE_MOD);
    
    
    $temp_array = $dbt->GetOrderingInfoList("naver@naver.com",5);
    print($temp_array);

?>