<!DOCTYPE html>
<html lang='ko'>
	<head>
		<meta charset='utf-8'/>

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />

		<title>Welcome to Mr.Mat's Restaurant</title>
		<meta name='description' content='' />
		<meta name='author' content='T1000' />

		<!-- 반응형 UI -->
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
    	<!-- <link href='assets/css/bootstrap-responsive.css' rel='stylesheet'> -->
    	
    	<!-- CDN 으로 링크하기. 왜 안되는지 모르겠음.. http://www.bootstrapcdn.com/
    		bootstrap.min.css 사용법 찾아보기 > 부트스트랩2.0 > 3.0 클래스 이름과 정의가 많이 바뀜
    	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css'>
    	<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
    	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'></script>

    	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css'> -->
    	
    	<!-- Custom CSS-->
    	<link href='css/customized_order.css' rel='stylesheet'>
    	
    	<!-- set bootstrap, font, style
    	<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>-->
    	<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/bootstrap-theme.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/font-awesome.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
	   
	    <!--<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>-->
	    <!--<script src='http://getbootstrap.com/js/bootstrap.js'></script>-->

    <!--[if lt IE 9]>
    <script src='http://html5shiv.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->

	</head>
	
	
	<script type="text/javascript">
	
		var httpReq = new XMLHttpRequest();
		var params = 'type=order';
		
		httpReq.open('POST','/Interface/requestjsondata.php',false);
		
		httpReq.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		
		try {
			httpReq.send(params);
		}
		catch(e){
			alert(e.message);
		}
		
		var jsonobject = httpReq.responseText;


		jsonobject = JSON.parse(jsonobject);
		
		//var jsonobject = '{"menulist":[{"dbkey":0,"name":"어니언 트위스트","Ingredient_list":[{"dbkey":0,"name":"감자","val":440},{"dbkey":1,"name":"양파","val":500}],"description":"유기농 감자와 양파가 어우러진 맛있는 어니언 트위스트입니다.","cost":14400},{"dbkey":1,"name":"서로인 스테이크","Ingredient_list":[{"dbkey":3,"name":"쇠고기_서로인","val":380},{"dbkey":2,"name":"브로콜리","val":200},{"dbkey":7,"name":"양송이버섯","val":100}],"description":"호주산 소고기의 최고급 부위인 서로인을 사용하여 Mr.Mat 특제 시즈닝을 첨가하여 직화로 구워낸 스테이크 입니다. 가니쉬로 브로콜리와 양송이가 제공됩니다.","cost":25000},{"dbkey":2,"name":"닭가슴살 샐러드","Ingredient_list":[{"dbkey":6,"name":"닭고기_닭가슴살","val":400},{"dbkey":8,"name":"양상추","val":280},{"dbkey":4,"name":"당근","val":280}],"description":"신선한 닭가슴살과 양상추, 그리고 각종 야채가 어우러진 풍성한 샐러드입니다.","cost":8900},{"dbkey":3,"name":"양송이 슾","Ingredient_list":[{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":50}],"description":"양송이와 양파가 들어간 풍부한 맛을 자랑하는 슾입니다.","cost":4500},{"dbkey":4,"name":"쿠카부라 파이어드 레그프레스","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":400}],"description":"풍부한 육질을 자랑하는 쿠카부라 레그! 직화구이로 그 맛을 한층 더 깊게 합니다.","cost":11900},{"dbkey":5,"name":"샤또 레오 드 뽕떼","Ingredient_list":[{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800}],"description":"감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!","cost":32000},{"dbkey":6,"name":"꾸브작 쌩때밀리옹","Ingredient_list":[{"dbkey":10,"name":"꾸브작 쌩때밀리옹","val":1000}],"description":"나폴레옹이 즐겨 마셨다는, 보르도 산 특별 와인! 당신의 품격을 높혀드립니다.","cost":45000},{"dbkey":7,"name":"쇠고기 안심 스테이크","Ingredient_list":[{"dbkey":13,"name":"쇠고기_안심","val":350},{"dbkey":14,"name":"파프리카","val":200},{"dbkey":7,"name":"양송이버섯","val":150}],"description":"호주산 1등급 쇠고기 안심을 엄선하여 직화로 구운 스테이크입니다.","cost":32000},{"dbkey":8,"name":"찹 스테이크","Ingredient_list":[{"dbkey":11,"name":"돼지고기_서로인","val":150},{"dbkey":12,"name":"돼지고기_안심","val":150},{"dbkey":1,"name":"양파","val":100},{"dbkey":14,"name":"파프리카","val":100}],"description":"돼지고기 안심과 등심을 한입 크기로 손질하여, 특제 앙념과 함께 구워낸 맛있는 요리입니다.","cost":15000},{"dbkey":9,"name":"에스빠뇰 스튜","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":120},{"dbkey":11,"name":"돼지고기_서로인","val":120},{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":4,"name":"당근","val":50}],"description":"에스빠뇰 스타일의 스튜로, 닭고기와 돼지고기가 함께 들어가는것이 특징입니다.","cost":18900},{"dbkey":10,"name":"코코뱅","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":200},{"dbkey":6,"name":"닭고기_닭가슴살","val":200},{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800},{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":100}],"description":"프랑스에서 일요일마다 먹는 닭 요리로, 샤또 레오 드 뽕떼 와인이 한 병 통째로 들어갑니다.","cost":50000}],"courselist":[{"dbkey":0,"name":"아메리칸 슈퍼바이저 컨펌드 디너","main_menu_list":[{"dbkey":1,"name":"서로인 스테이크","Ingredient_list":[{"dbkey":3,"name":"쇠고기_서로인","val":380},{"dbkey":2,"name":"브로콜리","val":200},{"dbkey":7,"name":"양송이버섯","val":100}],"description":"호주산 소고기의 최고급 부위인 서로인을 사용하여 Mr.Mat 특제 시즈닝을 첨가하여 직화로 구워낸 스테이크 입니다. 가니쉬로 브로콜리와 양송이가 제공됩니다.","cost":25000}],"sub_menu_list":[{"dbkey":0,"name":"어니언 트위스트","Ingredient_list":[{"dbkey":0,"name":"감자","val":440},{"dbkey":1,"name":"양파","val":500}],"description":"유기농 감자와 양파가 어우러진 맛있는 어니언 트위스트입니다.","cost":14400},{"dbkey":2,"name":"닭가슴살 샐러드","Ingredient_list":[{"dbkey":6,"name":"닭고기_닭가슴살","val":400},{"dbkey":8,"name":"양상추","val":280},{"dbkey":4,"name":"당근","val":280}],"description":"신선한 닭가슴살과 양상추, 그리고 각종 야채가 어우러진 풍성한 샐러드입니다.","cost":8900},{"dbkey":5,"name":"샤또 레오 드 뽕떼","Ingredient_list":[{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800}],"description":"감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!","cost":32000}],"style_list":["Simple","Deluxe"],"description":"당신의 상사가 승인한 아메리칸 스타일 디너!!","totalprice":80300},{"dbkey":1,"name":"치킨 앤 샤또","main_menu_list":[{"dbkey":2,"name":"닭가슴살 샐러드","Ingredient_list":[{"dbkey":6,"name":"닭고기_닭가슴살","val":400},{"dbkey":8,"name":"양상추","val":280},{"dbkey":4,"name":"당근","val":280}],"description":"신선한 닭가슴살과 양상추, 그리고 각종 야채가 어우러진 풍성한 샐러드입니다.","cost":8900},{"dbkey":4,"name":"쿠카부라 파이어드 레그프레스","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":400}],"description":"풍부한 육질을 자랑하는 쿠카부라 레그! 직화구이로 그 맛을 한층 더 깊게 합니다.","cost":11900}],"sub_menu_list":[{"dbkey":5,"name":"샤또 레오 드 뽕떼","Ingredient_list":[{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800}],"description":"감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!","cost":32000},{"dbkey":5,"name":"샤또 레오 드 뽕떼","Ingredient_list":[{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800}],"description":"감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!","cost":32000}],"style_list":["Simple","Deluxe","Grand"],"description":"샤또 레오 드 뽕떼와 치킨은 최고의 파트너!","totalprice":84800},{"dbkey":2,"name":"코코뱅 앤 데얼 프렌즈","main_menu_list":[{"dbkey":10,"name":"코코뱅","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":200},{"dbkey":6,"name":"닭고기_닭가슴살","val":200},{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800},{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":100}],"description":"프랑스에서 일요일마다 먹는 닭 요리로, 샤또 레오 드 뽕떼 와인이 한 병 통째로 들어갑니다.","cost":50000},{"dbkey":9,"name":"에스빠뇰 스튜","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":120},{"dbkey":11,"name":"돼지고기_서로인","val":120},{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":4,"name":"당근","val":50}],"description":"에스빠뇰 스타일의 스튜로, 닭고기와 돼지고기가 함께 들어가는것이 특징입니다.","cost":18900}],"sub_menu_list":[{"dbkey":6,"name":"꾸브작 쌩때밀리옹","Ingredient_list":[{"dbkey":10,"name":"꾸브작 쌩때밀리옹","val":1000}],"description":"나폴레옹이 즐겨 마셨다는, 보르도 산 특별 와인! 당신의 품격을 높혀드립니다.","cost":45000},{"dbkey":3,"name":"양송이 슾","Ingredient_list":[{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":50}],"description":"양송이와 양파가 들어간 풍부한 맛을 자랑하는 슾입니다.","cost":4500},{"dbkey":2,"name":"닭가슴살 샐러드","Ingredient_list":[{"dbkey":6,"name":"닭고기_닭가슴살","val":400},{"dbkey":8,"name":"양상추","val":280},{"dbkey":4,"name":"당근","val":280}],"description":"신선한 닭가슴살과 양상추, 그리고 각종 야채가 어우러진 풍성한 샐러드입니다.","cost":8900}],"style_list":["Simple","Deluxe"],"description":"코코뱅과 잘 어울리는 메뉴만 엄선한 코코뱅과 그의 친구들!","totalprice":127300},{"dbkey":3,"name":"올-인원 프리페어 포 굿 파티","main_menu_list":[{"dbkey":10,"name":"코코뱅","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":200},{"dbkey":6,"name":"닭고기_닭가슴살","val":200},{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800},{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":100}],"description":"프랑스에서 일요일마다 먹는 닭 요리로, 샤또 레오 드 뽕떼 와인이 한 병 통째로 들어갑니다.","cost":50000},{"dbkey":1,"name":"서로인 스테이크","Ingredient_list":[{"dbkey":3,"name":"쇠고기_서로인","val":380},{"dbkey":2,"name":"브로콜리","val":200},{"dbkey":7,"name":"양송이버섯","val":100}],"description":"호주산 소고기의 최고급 부위인 서로인을 사용하여 Mr.Mat 특제 시즈닝을 첨가하여 직화로 구워낸 스테이크 입니다. 가니쉬로 브로콜리와 양송이가 제공됩니다.","cost":25000},{"dbkey":7,"name":"쇠고기 안심 스테이크","Ingredient_list":[{"dbkey":13,"name":"쇠고기_안심","val":350},{"dbkey":14,"name":"파프리카","val":200},{"dbkey":7,"name":"양송이버섯","val":150}],"description":"호주산 1등급 쇠고기 안심을 엄선하여 직화로 구운 스테이크입니다.","cost":32000}],"sub_menu_list":[{"dbkey":0,"name":"어니언 트위스트","Ingredient_list":[{"dbkey":0,"name":"감자","val":440},{"dbkey":1,"name":"양파","val":500}],"description":"유기농 감자와 양파가 어우러진 맛있는 어니언 트위스트입니다.","cost":14400},{"dbkey":4,"name":"쿠카부라 파이어드 레그프레스","Ingredient_list":[{"dbkey":5,"name":"닭고기_닭다리","val":400}],"description":"풍부한 육질을 자랑하는 쿠카부라 레그! 직화구이로 그 맛을 한층 더 깊게 합니다.","cost":11900},{"dbkey":3,"name":"양송이 슾","Ingredient_list":[{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":50}],"description":"양송이와 양파가 들어간 풍부한 맛을 자랑하는 슾입니다.","cost":4500},{"dbkey":3,"name":"양송이 슾","Ingredient_list":[{"dbkey":7,"name":"양송이버섯","val":100},{"dbkey":1,"name":"양파","val":50}],"description":"양송이와 양파가 들어간 풍부한 맛을 자랑하는 슾입니다.","cost":4500},{"dbkey":6,"name":"꾸브작 쌩때밀리옹","Ingredient_list":[{"dbkey":10,"name":"꾸브작 쌩때밀리옹","val":1000}],"description":"나폴레옹이 즐겨 마셨다는, 보르도 산 특별 와인! 당신의 품격을 높혀드립니다.","cost":45000},{"dbkey":5,"name":"샤또 레오 드 뽕떼","Ingredient_list":[{"dbkey":9,"name":"샤또 레오 드 뽕떼","val":800}],"description":"감미로운 향을 자랑하는 샤또 레오 드 뽕떼는 스테이크 요리와 환상의 궁합을 자랑하는 레드와인 입니다!","cost":32000}],"style_list":["Simple","Grand"],"description":"즐거운 파티를 위한 선결 조건! 이 코스요리로 모든 걱정을 해결하십시오.","totalprice":219300}]}';
		//jsonobject = JSON.parse(jsonobject);
		
		/*
		 * jsonobject['menulist'] -> 모든 메뉴 리스트
		 * 		jsonobject['menulist'].length -> 메뉴의 개수
		 * 		jsonobject['menulist'][i] -> 하나의 메뉴
		 * 				jsonobject['menulist'][i]['dbkey'] -> 메뉴에 대응되는 dbkey
		 * 				jsonobject['menulist'][i]['name'] -> 메뉴 이름
		 * 				jsonobject['menulist'][i]['ingredient_list'] -> 메뉴에 포함되는 원재료 리스트
		 * 					jsonobject['menulist'][i]['ingredient_list']['dbkey'] -> 원재료의 dbkey
		 * 					jsonobject['menulist'][i]['ingredient_list']['name'] -> 원재료의 이름
		 * 					jsonobject['menulist'][i]['ingredient_list']['val'] -> 메뉴에 필요한 원재료의 수치
		 * 				jsonobject['menulist'][i]['description'] -> 메뉴 설명
		 * 				jsonobject['menulist'][i]['cost'] -> 메뉴 가격
		 * 
		 * jsonobject['courselist'] -> 모든 코스 리스트
		 * 		jsonobject['courselist'].length -> 코스 메뉴의 개수
		 * 		jsonobject['courselist'][i] -> 하나의 코스요리
		 * 				jsonobject['courselist'][i]['dbkey'] -> 코스 요리의 dbkey
		 * 				jsonobject['courselist'][i]['name'] -> 코스 요리의 이름
		 * 				jsonobject['courselist'][i]['main_menu_list'] -> 메인 메뉴 리스트
		 * 					jsonobject['courselist'][i]['main_menu_list'].length -> 메인 메뉴 개수
		 * 				jsonobject['courselist'][i]['sub_menu_list'] -> 서브 메뉴 리스트
		 * 					jsonobject['courselist'][i]['sub_menu_list'] -> 서브 메뉴 개수
		 * 				jsonobject['courselist'][i]['style_list'] -> 가능한 스타일 리스트
		 * 				jsonobject['courselist'][i]['description'] -> 코스 요리 설명
		 * 				jsonobject['courselist'][i]['totalprice'] -> 코스 요리의 가격
		 */

		//모든 메뉴들의 디비키에 대한 메뉴 객체
		var menuinfo = [];
		
		for (var i = 0; i < jsonobject['menulist'].length; i++)
		{
			var dbkey = jsonobject['menulist'][i]['dbkey'];
			var cost = jsonobject['menulist'][i]['cost'];
			var name = jsonobject['menulist'][i]['name'];

			menuinfo[dbkey] = jsonobject['menulist'][i];

			
		}

		//모든 코스들의 디비키에 대한 코스요리 객체
        var courseinfo = [];
 
        for (var i = 0; i < jsonobject['courselist'].length; i++) 
        {
            var dbkey = jsonobject['courselist'][i]['dbkey'];

            courseinfo[dbkey] = jsonobject['courselist'][i];
        }

        // 주문 추가 개수
        var yourchoiceinfo=[];

        // 주문 정보
        var orderinginfo = {};

        // 주문 선택 라디오 버튼 아이디

        var radiobtnid = '-1';

        orderinginfo.customizedcoursedishlist = [];


        window.onload = function () {
            for (id = 0; id < jsonobject['courselist'].length; id++) {
                var menuname, price, source, dbkey, oper = '+';

                menuname = jsonobject['courselist'][id]['name'];
                cost = jsonobject['courselist'][id]['totalprice'];
                dbkey = jsonobject['courselist'][id]['dbkey'];

                source = "<a class='list-group-item'>\
							        " + menuname + "\
    							    <button id='" + dbkey + "'type='submit' onclick='onClickedFromSelectCourseMenu(this.id)' class='btn btn-default btn-xs pull-right'>\
								    " + cost + "\&nbsp;<i class='fa fa-arrow-right'></i></button></a>";

                document.getElementById('courseMenu').innerHTML += source;
            }
            // 추가 가능한 메뉴 추가
            document.getElementById('additionalOptions').innerHTML = "";

            for (var i in jsonobject['menulist']) {
                var dbkey = jsonobject['menulist'][i]['dbkey'];
                var cost = jsonobject['menulist'][i]['cost'];
                var name = jsonobject['menulist'][i]['name'];

                document.getElementById('additionalOptions').innerHTML += getAddtionalOptionSource(name, cost, dbkey);
            }
        };

         // 여기는 뷰 업데이트에 관련된 함수
         function updateYourChoicesView() {
            var idx;
            document.getElementById('courseChoice').innerHTML = "";
            for(idx in yourchoiceinfo)
            {
                var name, count, id;
                name = yourchoiceinfo[idx].name;
                count = yourchoiceinfo[idx].count+1;
                id = yourchoiceinfo[idx].id;

                var yourchoicesview_source = "<li class='list-group-item'>\
									<form class='form-inline' role='form' onsubmit='return false;'>\
										<button id ='" + id + "'type='submit' class='btn btn-default btn-xs pull-left' onclick='onClickedFromYourChoices(this.id)'><i class='fa fa-minus'></i></button>\
										&nbsp" + name + "\
										<div class='input-group input-group-sm pull-right' style='width:100px; margin-top:-5px;'>\
											<input type='number' class='form-control' placeholder='" + count + "' readonly>\
											<span class='input-group-addon'>EA</span>\
										</div>\
									</form>\
								</li>";
                document.getElementById('courseChoice').innerHTML += yourchoicesview_source;
            }
        }
        // 이것은 사용자가 추가한 주문 뷰 업데이트에 관한 함수
        function updateCustomizedCourse() {
            // 라디오 버튼 선택 초기화
            radiobtnid = '-1';
            var idx, courseobj;
            document.getElementById('selectedCourse').innerHTML = "";

            for (idx in orderinginfo.customizedcoursedishlist) {
                var name, main_list, sub_list,style_list;
                courseobj = orderinginfo.customizedcoursedishlist[idx];

                name = orderinginfo.customizedcoursedishlist[idx].name;
                main_list = courseobj.main_menu_list;
                sub_list = courseobj.sub_menu_list;
                style_list = courseobj.style_list;

                var panelview = getSelectedCourseSource(idx, courseobj);
                document.getElementById('selectedCourse').innerHTML += panelview;

                // 스타일 부분을 추가
                document.getElementById('style' + idx).innerHTML = "";
                for (var i in style_list) {
                    var stylename = style_list[i];

                    document.getElementById('style' + idx).innerHTML += "<option>" + stylename + "</option>"
                }

                // 기본 스타일은 첫 스타일로
                courseobj.style = style_list[0];

                // 메인 메뉴 부분을 추가
                document.getElementById('main' + idx).innerHTML = "";
                for (var i in main_list) {
                    var menuname, menucost;
                    menuname = main_list[i].name;
                    menucost = main_list[i].cost;

                    var mainmenuview = getMainMenuSource(menuname, menucost);

                    document.getElementById('main' + idx).innerHTML += mainmenuview;
                }

                // 서브 메뉴 부분을 추가
                document.getElementById('sub' + idx).innerHTML = "";

                for (var i in sub_list) {
                    var menuname, menucost;
                    menuname = sub_list[i].name;
                    menucost = sub_list[i].cost;

                    var submenuview = getSubMenuSource(menuname, menucost, idx + '-' + i);

                    document.getElementById('sub' + idx).innerHTML += submenuview;
                }

            }
        }

        // 컨펌 뷰 함수
        function updateConfirmOrder() {
            // 라디오 버튼 선택 초기화
            var idx, courseobj;
            document.getElementById('confirmorder').innerHTML = "";

            for (idx in orderinginfo.customizedcoursedishlist) {
                var name, main_list, sub_list;
                courseobj = orderinginfo.customizedcoursedishlist[idx];

                name = orderinginfo.customizedcoursedishlist[idx].name;
                main_list = courseobj.main_menu_list;
                sub_list = courseobj.sub_menu_list;
                style = courseobj.style;
                totalprice = courseobj.totalprice;

                var panelview = getConfirmOrderSource(idx,name,style,totalprice);
                
                document.getElementById('confirmorder').innerHTML += panelview;

                // 메인 메뉴 부분을 추가
                document.getElementById('menus'+idx).innerHTML = "";
                for (var i in main_list) {
                    var menuname, menucost;
                    menuname = main_list[i].name;
                    menucost = main_list[i].cost;

                    var mainmenuview = getConfirmOrderMainMenu(menuname);

                    document.getElementById('menus'+idx).innerHTML += mainmenuview;
                }

                // 서브 메뉴 부분을 추가
                for (var i in sub_list) {
                    var menuname, menucost;
                    menuname = sub_list[i].name;
                    menucost = sub_list[i].cost;

                    var submenuview = getConfirmOrderSubMenu(menuname);

                    document.getElementById('menus'+idx).innerHTML += submenuview;
                }

            }
        }

        // 여기서부터는 HTML 소스 얻는 함수

        // 추가 메뉴에 관련된 소스
        function getAddtionalOptionSource(name, cost, menuKey) {
            var source_additional = "\
								<a class='list-group-item'>\
									<button type='submit' onclick='onClickedFromCustomizedSubMenu_Included(this.id)' id='" + menuKey + "' class='btn btn-default btn-xs '><i class='fa fa-plus'></i></button>\
									" + name + "\
									<span style='float:right; color:red;'>" + cost + " \\" + "</span>\
								</a>";
            return source_additional;
        }
        // 주문 정보 확인 소스
        function getConfirmOrderSubMenu(name) {
            var source = "<a class='list-group-item'><span class='badge pull-left' style='margin-right:10px;'>-</span>" + name + "</a>";
            return source;
        }

        function getConfirmOrderMainMenu(name) {
            var source = "<li class='list-group-item'>" + name + "</li>";
            return source;
        }

        function getConfirmOrderSource(id,coursename,style,totalprice) {
            var source = "<div class='col-md-6'>\
                    			<div class='panel panel-default' style='margin:3%;'>\
    								<div class='panel-heading'>\
                                    <b><i class='fa fa-check'></i>"+coursename+"</b>\
			    						    <b style='color:red;margin:5%'>"+style+"</b>\
			    						    <b style='color:red; float:right'>"+totalprice+" \\</b>\
    								</div>\
    								<div class='panel-body'>\
    									<div class='list-group' id='menus"+id+"'>\
										</div>\
    								</div>\
    							</div>\
                            </div>";

            return source;
        }

        
        // 코스 요리
        function getSelectedCourseSource(id,courseobj) {
            var name = courseobj.name;
            var radio_id = "radio" + id ;
            var source_option = "\
    				<div class='panel panel-default' style='margin:3%;'>\
    					<div class='panel-heading' style='padding-bottom:0'>\
    							<b style='font-size: 20px;'>" + name + "</b>\
    						<p>\
    						<div class='radio pull-right'>\
  									<label>\
    									<input type='radio' name='optionsRadios' id='" + radio_id + "' onchange='onChangedFromCustomizedCourseDishRadioSelection(this)' value='option'>Add Here!\
  									</label>\
								</div>\
    							<select class='form-control input-sm' id='style" + id + "' onchange='onChangedFromCustomizedCourseDishStyle(this)' style='width:150px; margin-top:10px; margin-bottom:0;'>\
								</select>\
    						</p>\
    					</div>\
    					<div class='panel-body'>\
    						<div class='list-group'>\
    							<div id='main" + id + "'>\
    								<!-- javascript : 동적 추가-->\
								</div>\
								<div id='sub" + id + "'>\
									<!-- javascript : 동적 추가-->\
								</div>\
							</div>\
    					</div> <!-- panel-body -->\
    				</div> <!-- panel -->";

            return source_option;
        }
        // 코스 요리의 주 메뉴
        function getMainMenuSource(name, cost) {
            var source_option_main = "<li class='list-group-item'>\
										<b>" + name + "<b>\
										<span style='float:right; color:red; font-weight:bold'>" + cost + " \\" + "</span>\
									</li>";

            return source_option_main;
        }
        // 코스 요리의 부 메뉴
        function getSubMenuSource(name, cost, menuKey) {
            var source_option_sub = "<a class='list-group-item'>\
										<button type='submit' id='" + menuKey + "'class='btn btn-default btn-xs' onclick='onClickedFromCustomizedSubMenu_Excluded(this.id)'><i class='fa fa-minus'></i></button>\
										" + name + "\
										<span style='float:right; color:red;'>" + cost + " \\" + "</span>\
									</a>";

            return source_option_sub;
        }

        // 메뉴 추가를 위한 함수
        function onClickedFromCustomizedSubMenu_Included(id) {
            if (radiobtnid === '-1') {
                alert("메뉴를 추가할 코스요리를 선택해 주세요!");

            }
            else {
                var menudbkey = id;
                var targetccd = orderinginfo.customizedcoursedishlist[radiobtnid];
                var targetccd_main_menu = targetccd['main_menu_list'];

                for(var i in targetccd_main_menu) {
                    var menuobj = targetccd_main_menu[i];

                    if (menuobj.dbkey == menudbkey) {
                        alert("메인 메뉴에 포함된 메뉴는 추가할 수 없습니다.");
                        return;
                    }
                }

                var targetccd_sub_menu = targetccd['sub_menu_list'];
                // 깊은 복사 필요
                var additionalmenu = jQuery.extend(true, {}, menuinfo[id]);

                // 메뉴 추가
                targetccd_sub_menu.push(additionalmenu);

                // 가격 반영

                targetccd.totalprice = (targetccd.totalprice*1 + additionalmenu.cost*1)+"";

                updateCustomizedCourse();
                updateConfirmOrder();
            }
        }
       
        // 코스 요리 라디오버튼 선택을 확인하는 함수
        function onChangedFromCustomizedCourseDishRadioSelection(obj) {
            var ccdid = obj.id;
            ccdid = ccdid.replace('radio', '');

            radiobtnid = ccdid;
        }

        // 각 코스요리 별 스타일 결정을 위한 함수

        function onChangedFromCustomizedCourseDishStyle(obj) {
            var ccdid = obj.id;

            ccdid = ccdid.replace('style', '');

            orderinginfo.customizedcoursedishlist[ccdid].style = obj.value;
            updateConfirmOrder();

        }
        // 각 코스요리 별 메뉴 삭제를 위한 함수
        function onClickedFromCustomizedSubMenu_Excluded(id) {
            var splited = id.split("-");
            var ccd_id = splited[0];
            var ccd_sub_id = splited[1];

            // 목표가 되는 커스터마이즈드 코스 요리
            var targetccd = orderinginfo.customizedcoursedishlist[ccd_id];

            // 그 코스 요리의 서브메뉴
            var targetsubmenulist = targetccd.sub_menu_list;
            // 그 서브메뉴의 가격
            var menucost = targetsubmenulist[ccd_sub_id].cost;

            // 제거
            delete targetsubmenulist[ccd_sub_id];

            // 가격변동
            targetccd.totalprice = targetccd.totalprice = (targetccd.totalprice * 1 - menucost * 1) + "";

            updateCustomizedCourse();
            updateConfirmOrder();
        }


        // Select Course Dishes 에서 선택한 코스 메뉴 버튼 처리 이벤트
        function onClickedFromSelectCourseMenu(id) {
                // 아이디는 코스 요리의 dbkey
                if (!(id in yourchoiceinfo)) {
                    yourchoiceinfo[id] = {};
                    yourchoiceinfo[id].count = 0;
                    yourchoiceinfo[id].name = courseinfo[id]['name'];
                    yourchoiceinfo[id].id = id;
                }
                else {
                    yourchoiceinfo[id].count++;
                }
                // deep copied needed
                var copiedobj = jQuery.extend(true,{}, courseinfo[id]);
                orderinginfo.customizedcoursedishlist.push(copiedobj);
                updateYourChoicesView();
                updateCustomizedCourse();
                updateConfirmOrder();
                
        }
        // Your Choices 버튼 처리 이벤트
        function onClickedFromYourChoices(id) {
            // id는 코스요리의 dbkey
            if (yourchoiceinfo[id].count === 0) {
                
                delete yourchoiceinfo[id];                
            }
            else {
                yourchoiceinfo[id].count--;
            }
            // ccd에서 뒤에서 첫번째로 dbkey와 일치하는 코스요리 삭제
            var searchccdlist = orderinginfo.customizedcoursedishlist;
            var delidx;

            for (var idx in searchccdlist) {
                var courseobj = searchccdlist[idx];

                if (courseobj.dbkey == id) {
                    delidx = idx;
                }
            }

            delete searchccdlist[delidx];
            updateYourChoicesView();
            updateCustomizedCourse();
            updateConfirmOrder();
        }
		
	</script>
	
	<body>   		
		<!-- icon .fa 클래스 display 오버라이딩-->
		<style>.fa{display:inline;}</style>
		<a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'></i></a>
		<nav id='sidebar-wrapper' class='active'>
	    	<ul class='sidebar-nav'>
   	        	<a id='menu-close' href='#' class='btn btn-light btn-lg pull-right toggle'><i class='fa fa-times'></i></a>
   	        	<li class='sidebar-brand'>
                	<a href='#top'>Order</a>
           		</li>
            	<li>
            		<a href='index.php'><i class='fa fa-home'></i>&nbsp;Home</a> 
            	</li>
            	<li>
            		<a href='#top'><i class='fa fa-arrow-circle-up'></i>&nbsp;Top</a> 
            	</li>
            	<li>
            		<a href='#select'><i class='fa fa-cutlery'></i>&nbsp;Select Course Menu</a> 
            	</li>
            	<li>
            		<a href='#options'><i class='fa fa-cutlery'></i>&nbsp;Additional Options</a> 
            	</li>
            	<li>
            		<a href='#confirm'><i class='fa fa-cutlery'></i>&nbsp;Confirm Order</a> 
            	</li>
            	<!-- 구분선 <li class='divider' style='background-color: white;'></li> -->
            	<li><!-- 로그인 모달 붙이기-->
            	    <a href='index.php'><i class='fa fa-check-square-o'></i>&nbsp;Log In</a>
            	</li>
            	<li><!-- 회원가입페이지 fa-slideshare -->
            	    <a href='register.php'><i class='fa fa-users'></i>&nbsp;Register</a>
            	</li>
        	</ul>
    	</nav>
    <header id='top' class='header'>
    	<div class='text-vertical-center'>
			<h1 style='color:rgba(255,130,0,1); font-size:700%;'>
				<i class='fa fa-cutlery fa-fw' ></i> <b>Order</b>
			</h1>
			<div style='height:50px'></div>
			<a href='#select' class='btn btn-link' style='font-size:40px; color:orange; font-weight: bold'>Start!</a>
		</div>
    </header>
   <!-- 1. Select Course Dishes -->
    <section id='select' class='select'>
    	<!-- text-shadow: 3px 3px 5px orange; -->
    	<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
    		<b>1. Select Course Dishes</b>
    	</h1>
        <div class='container'>
        	<div class='row' style='margin-right:100px;'>
        		<!-- 왼쪽 메뉴 선택창 -->
        		<div class='col-md-6'>			
					<!-- 패널 속 리스트 -->
					<div class='panel panel-default'>
        				<div class='panel-heading'>
	    					<b style='font-size: 20px;'>Select Course Menu</b>
    					</div>
	    			
    					<div class='panel-body'>
							<div class='list-group' id='courseMenu'>
								
								
								<!-- Javascript: onload()-->
								
								
							</div>
						</div> <!-- /.panel-body -->
					</div> <!-- panel -->	           		
	    		</div> <!-- /.col-md-6 -->
	    		<!-- 오른쪽 메뉴 사이드바 -->
        		<div class='col-md-6' style='padding-right:0;'>
        			<div class='panel panel-default'>
        				<div class='panel-heading'>
	    					<b style='font-size: 20px;'>Your Choices</b>
    					</div>
    					<div class='panel-body'>
	        				<ul class='list-group' id='courseChoice'>					
								
								
								<!-- Javascript: onClickedMenuAddition(), onClickedMenuSubtraction() -->
								
								
							</ul>
						</div><!-- /.panel-body -->
					</div><!-- /.panel -->
        		</div><!-- /.col-md-6 -->
        	</div>
        	
        	<div class='col-md-12' align='center' style='padding:50px 0;'>
            	<a href='#options' class='btn btn-link' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
            		Next <i class='fa fa-angle-double-down'></i></a>
           	</div>
        </div><!-- /.container -->
    </section>
    
    <!-- 2. Additional Options -->
    <section id='options' class='options'>
    	<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
    		<b>2. Customize Yourself!</b>
    	</h1>
        <div class='container'>
    		<!-- 하나의 코스요리에 대한 옵션 선택창 -->
    		<div class='row' style='background-color:rgba(200,200,200,0.7); border-radius: 30px; margin-right:100px'>
    			<div class="col-md-6" id='selectedCourse'>
    				
    				
    				<!-- 동적 변화 -->
    				
    				
    			</div>
    			
    			<div class="col-md-6">
    				<div class='panel panel-default' style='margin:3%;'>
    					<div class='panel-heading'>
    						<b style='font-size: 20px;'>Additional Options</b>
    					</div>
    					<div class='panel-body' id='additionalOptions'>
    						<ul class='list-group'>
    							
    							
    							<!-- 동적 변화 -->
    							
    							
    						</ul>
    					</div>
    				</div>
    			</div>	
    		</div> <!-- /.row -->
    		<div class='col-md-12' align='center' style='padding:50px 0;'>
            	<a href='#confirm' class='btn btn-link' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
            		Next <i class='fa fa-angle-double-down'></i></a>
           	</div>
        </div><!-- /.container -->
    </section>
    
    <!-- 3. Confirm Order -->
    <section id='confirm' class='confirm'>
    	<h1 align='center' style='color:orange; font-size:500%;'>
    		<b>3. Confirm Order</b>
    	</h1>
    	
        <div class='container'>
    		<div class='row' style='background-color:rgba(200,200,200,0.7); border-radius: 30px; margin-right:100px;' >
    			<!-- 1. 주문 정보 -->
    			<div class='col-md-12'>
    				<!-- 주문정보가 출력되는 전체 패널 -->
    				<div class='panel panel-default' style='margin:3%;'>
    					<div class='panel-heading'>
    						<b style='font-size: 20px;'>
    							<i class='fa fa-cutlery'></i> Your Order</b>
    					</div>
    					<!-- 각각의 코스요리 정보 패널을 담는 패널바디 -->
    					<div id='confirmorder' class='panel-body'>
    						<!-- 첫번째 코스요리 정보 패널 -->
    						<!-- 첫번째 코스요리 정보 -->
    					<!-- 바디 내용 끝 -->
    					</div><!-- /.panel-body -->
    				</div><!-- /.panel -->
    			</div><!-- /.주문정보 -->
    			    			
    			<!-- 2. 고객 정보 -->
    			<div class='col-md-12'>
    				<div class='panel panel-default' style='margin:3%;'>
    					<div class='panel-heading'>
    						<b style='font-size: 20px;'>
    							<i class='fa fa-user'></i> Customer Information
    						</b>
    					</div>
    					<div class='panel-body'>
    						<div class='col-md-6' style='padding:10px;'>
    							<form class='form-inline' role='form' >
    								<b style='margin-top:5px;'>
    									<i class='fa fa-check'></i> Name : 
    								</b>
    								<input type='text' class='form-control input-sm pull-right' id='nameInput' placeholder='JINSEONG KIM' disabled
    									style='margin:-5px 30px 0 0;'>
    							</form>
    						</div>
    						<div class='col-md-6' style='padding:10px;'>
    							<form class='form-inline' role='form' >
    								<b style='margin-top:5px;'>
    									<i class='fa fa-check'></i> Address : 
    								</b>
    								<input type='text' class='form-control input-sm pull-right' id='addressInput' placeholder='서울시립대 정기관'
    									style='margin:-5px 30px 0 0;'>
    							</form>
    						</div>
    						<div class='col-md-6' style='padding:10px;'>
    							<form class='form-inline' role='form' >
    								<b style='margin-top:5px;'>
    									<i class='fa fa-check'></i> Phone Number : 
    								</b>
    								<input type='text' class='form-control input-sm pull-right' id='phoneInput' placeholder='010-7584-7896'
    									style='margin:-5px 30px 0 0;'>
    							</form>
    						</div>
    						<div class='col-md-6' style='padding:10px;'>
    							<form class='form-inline' role='form' >
    								<b style='margin-top:5px;'>
    									<i class='fa fa-check'></i> Creaditcard Number : 
    								</b>
    								<input type='text' class='form-control input-sm pull-right' id='creditcardInput' placeholder='1234-5678-9876-1234'
    									style='margin:-5px 30px 0 0;'>
    							</form>
    						</div>
							
    					</div>
    				</div>
    			</div>
    		</div> <!-- /.row -->
    		
    		<!-- next page : order complete page -->
    		<div class='col-md-12' style='padding:50px 0;'>
            	<a href='#confirm' class='btn btn-link pull-right' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
            		Order <i class='fa fa-angle-double-right'></i></a>
           	</div>
    		
        </div>
	</section>
   	
    <script src='js/jquery.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <!-- WoW animations -->
    <script src='js/customized/wow.min.js'></script>
    <!-- Easing for image animations -->
    <script src='js/customized/jquery.easing.min.js'></script>
    
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>
	</body>
</html>




