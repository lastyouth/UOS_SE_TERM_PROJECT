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
    	
    	<!-- Custom CSS-->
    	<link href='css/customized_order.css' rel='stylesheet'>
    	
    	<!-- set bootstrap, font, style
    	<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>-->
    	<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/bootstrap-theme.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/font-awesome.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
	   
	   	<!-- for datetimepicker -->
	  	<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" media="all" type="text/css" href="css/jquery-ui-timepicker-addon.css" />

	    <!-- Script -->
		
		<script src='js/jquery.js'></script>
		<script src='js/bootstrap.min.js'></script>
    	<!-- WoW animations -->
    	<script src='js/customized/wow.min.js'></script>
    	<!-- Easing for image animations -->
    	<script src='js/customized/jquery.easing.min.js'></script>

	    <!-- Load jQuery and datetimepicker scripts -->	    
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
	    <script type="text/javascript" src="js/jquery-ui-sliderAccess.js"></script>
        <script type="text/javascript" src="js/config.js"></script>
		
    <!--[if lt IE 9]>
    <script src='http://html5shiv.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->

	</head>

	<script type="text/javascript">

	    // 전역 자료 변수
	    var menuinfo = [];
	    var courseinfo = [];
	    // 주문 추가 개수
	    var yourchoiceinfo = [];

	    // 스타일 별 가산 가격
	    var style_add_rate = [];

	    style_add_rate['Simple'] = 1.0;
	    style_add_rate['Deluxe'] = 1.2; // 디럭스 20%
	    style_add_rate['Grand'] = 1.5; // 그랜드 50%

	    // 주문 정보
	    var orderinginfo = {};

	    // 주문 선택 customizedorder 아이디
	    var ccdorderid = '-1';
	    // 주문 정보의 커스터마이즈드 코스 요리 리스트
	    orderinginfo.customizedcoursedishlist = [];

	    // 할인 정보
	    var discountpercent;
	    var remainordercount;
	    var discountable = false;

	    function onClickedModalButton(id) {
	        ccdorderid = id.replace('button','');
	        $('#myModal-options').modal();
        }

        
    
    
        // Scrolls to the selected menu item on the page
        $(function () {
            $('a[href*=#]:not([href=#])').click(function () {
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

            // Closes the sidebar menu
            $("#menu-close").click(function (e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("active");
            });

            // Opens the sidebar menu
            $("#menu-toggle").click(function (e) {
                e.preventDefault();
                $("#sidebar-wrapper").toggleClass("active");
            });
            $('#myModal-options').on('hidden.bs.modal', function () {

            });
            $("#myModal-options").draggable({
                handle: ".modal-header"
            });
            $("#checkdiscountstatusbtn").click(function (e) {
                if (orderinginfo.type === "unregistered") {
                    var not_discount = "<b>Unregistered user cannot get our discount services.</b>";

                    $("#discountstatus").html(not_discount);
                }
                else {
                    // 회원만 가능
                    var available_source = '<b>You can get a ' + discountpercent + '% total price discount!</b>';
                    var unavailable_source = '<b>You need ' + remainordercount + ' more order to get ' + discountpercent + '% total price discount!</b>';

                    if (discountable) {
                        $("#discountstatus").html(available_source);
                    }
                    else {
                        $("#discountstatus").html(unavailable_source);
                    }
                }
                $("#myModal-discount").modal();
            });

            // 주문 정보 보내기
            $("#sendorderinfo").click(function (e) {

                // 데이터 검증
                var name, address, phonenumber, creditcard;
                name = $("#name").val();
                if (orderinginfo.customizedcoursedishlist.length === 0) {
                    alert("아무것도 주문하지 않으셨습니다.");
                    return;
                }
                address = $("#address").val();

                if (address === "") {
                    alert("배달 주소가 없습니다.");
                    return;
                }
                creditcard = $("#creditcard").val();

                if (creditcard === "") {
                    alert("신용카드 번호가 없습니다.");
                    return;
                }

                phonenumber = $("#phonenumber").val();

                if (phonenumber === "") {
                    alert("휴대전화번호가 없습니다.");
                    return;
                }

                var time = $('#datetimepicker').val();

                if (time === "") {
                    alert("배달 시간을 선택하지 않으셨습니다.");
                    return;
                }

                var destdate = new Date(time);
                var hour = destdate.getHours();
                var chour = new Date();

                chour.setHours(chour.getHours() + 1, chour.getMinutes(), 0, 0);


                if (hour < 15 || hour > 21) {
                    alert("주문 예약은 오후 3시부터 10시까지만 가능합니다.");
                    return;
                }
                if (chour.getTime() > destdate.getTime()) {
                    alert("주문 예약은 현재 시간보다 적어도 1시간 뒤여야 합니다.");
                    return;
                }
                // ok..

                if (confirm("이대로 주문을 진행할까요?")) {
                    orderinginfo.requestordertime = destdate.getTime();
                    orderinginfo.name = name;
                    orderinginfo.address = address;
                    orderinginfo.phonenumber = phonenumber;
                    orderinginfo.creditcard = creditcard;

                    var form = document.createElement('form');

                    form.setAttribute("method", 'POST');
                    form.setAttribute("action", '/Interface/ordermenu.php');

                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", "jsondata");
                    hiddenField.setAttribute("value", JSON.stringify(orderinginfo));
                    form.appendChild(hiddenField);
                    form.submit();
                }
            });

        });
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

        var jsonobject = requestserver('order');

        if (jsonobject === 'error') {
            alert("정보를 받아오는 데 문제가 있습니다.")
            window.location.href = '/Interface/index.php';
        }


        jsonobject = JSON.parse(jsonobject);


       
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
		 * 				jsonobject['menulist'][i]['discription'] -> 메뉴 설명
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
		 * 				jsonobject['courselist'][i]['discription'] -> 코스 요리 설명
		 * 				jsonobject['courselist'][i]['totalprice'] -> 코스 요리의 가격
		 */

        window.onload = function () {
            sessioncheck('ordermenuForm.php');
		    generate();
		    // datepicker
		    $('#datetimepicker').datetimepicker(
            {
                /*
                timeFormat
                Default: "HH:mm",
                A Localization Setting - String of format tokens to be replaced with the time.
                */
                timeFormat: "hh:mm tt",
                /*
                hourMin
                Default: 0,
                The minimum hour allowed for all dates.
                */
                hourMin: 15,
                /*
                hourMax
                Default: 23, 
                The maximum hour allowed for all dates.
                */
                hourMax: 21,
                /*
                numberOfMonths
                jQuery DatePicker option
                that will show two months in datepicker
                */
                numberOfMonths: 2,
                //timezone: "+0900",
                /*
                minDate
                jQuery datepicker option 
                which set today date as minimum date
                */
                minDate: 0,
                parse: 'loose',
                /*
                maxDate
                jQuery datepicker option 
                which set 30 days later date as maximum date
                */
                maxDate: 30,
            });
		};
        // 페이지 생성
        function generate() {
            
            // 모든 메뉴의 디비키에 대한 메뉴 객체
            for (var i = 0; i < jsonobject['menulist'].length; i++) {
                var dbkey = jsonobject['menulist'][i]['dbkey'];

                menuinfo[dbkey] = jsonobject['menulist'][i];
            }

            //모든 코스들의 디비키에 대한 코스요리 객체


            for (var i = 0; i < jsonobject['courselist'].length; i++) {
                var dbkey = jsonobject['courselist'][i]['dbkey'];

                courseinfo[dbkey] = jsonobject['courselist'][i];
            }
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
             // 회원 정보 세팅

             

             if(jsonobject['userinfo'].hasOwnProperty('email'))
             {
                // 회원
                 orderinginfo.type = 'registered';
                 orderinginfo.email = jsonobject['userinfo']['email'];
             }
             else
             {
                //비회원
                orderinginfo.type = 'unregistered';
             }

             document.getElementById('name').setAttribute("value", jsonobject['userinfo']['name']);
             document.getElementById('phonenumber').setAttribute("value", jsonobject['userinfo']['phonenumber']);
             document.getElementById('creditcard').setAttribute("value", jsonobject['userinfo']['creditcardnum']);
             document.getElementById('address').setAttribute("value", jsonobject['userinfo']['address']);

             // 할인율 세팅
             var discount = jsonobject['discountpolicy'];
             var targetordercount = discount['target_ordercount'];
             discountpercent = discount['discount_percent'];
             var currentordercount = jsonobject['ordercount'];
             remainordercount = targetordercount - (currentordercount % targetordercount);
             discountable = false;

             if (currentordercount != 0 && currentordercount % targetordercount == 0) {
                 document.getElementById('discounttype').setAttribute('value', discountpercent+'%');
                 discountable = true;
                 orderinginfo.discount = true;
             }
             else {
                 document.getElementById('discounttype').setAttribute('value', '0%');
                 orderinginfo.discount = false;
             }
         }

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
            // 코스 요리 버튼 선택 id 초기화
            ccdorderid = '-1';
            var idx, courseobj;
            document.getElementById('selectedCourse').innerHTML = "";

            for (idx in orderinginfo.customizedcoursedishlist) {
                var name, main_list, sub_list,style_list;
                courseobj = orderinginfo.customizedcoursedishlist[idx];

                name = orderinginfo.customizedcoursedishlist[idx].name;
                main_list = courseobj.main_menu_list;
                sub_list = courseobj.sub_menu_list;
                style_list = courseobj.style_list;

                // 기본 스타일은 첫 스타일로 (정의되어 있지 않은 경우
                if (courseobj.style === undefined) {
                    courseobj.style = style_list[0];
                }
                var panelview = getSelectedCourseSource(idx, courseobj);
                document.getElementById('selectedCourse').innerHTML += panelview;

                // 스타일 부분을 추가
                document.getElementById('style' + idx).innerHTML = "";
                for (var i in style_list) {
                    var stylename = style_list[i];

                    if (stylename === courseobj.style) {
                        document.getElementById('style' + idx).innerHTML += "<option selected='selected'>" + stylename + "</option>"
                    }
                    else {
                        document.getElementById('style' + idx).innerHTML += "<option>" + stylename + "</option>"
                    }
                }

                
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
            var idx, courseobj,overallprice = 0;
            document.getElementById('confirmorder').innerHTML = "";

            for (idx in orderinginfo.customizedcoursedishlist) {
                var name, main_list, sub_list;
                courseobj = orderinginfo.customizedcoursedishlist[idx];

                name = orderinginfo.customizedcoursedishlist[idx].name;
                main_list = courseobj.main_menu_list;
                sub_list = courseobj.sub_menu_list;
                style = courseobj.style;
                totalprice = courseobj.totalprice * style_add_rate[style];

                overallprice += totalprice;

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
            // 토탈 가격을 세팅
            var pd = 1;
            if (discountable) {
                pd = (100 - discountpercent) / 100.0;
            }
            overallprice*=pd;
            document.getElementById('totalprice').setAttribute('value', overallprice);
            orderinginfo.overallprice = overallprice;
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
            var source = "<a class='list-group-item'>" + name + "</a>";
            return source;
        }

        function getConfirmOrderMainMenu(name) {
            var source = "<li class='list-group-item'><b><span class='badge pull-left' style='margin-right:10px;'>main</span>" + name + "</b></li>";
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
            var button_id = "button" + id ;
            var source_option = "\
            	<div class='col-md-6'>\
    				<div class='panel panel-default' style='margin:3%;'>\
    					<div class='panel-heading' >\
    						<b style='font-size: 20px;'>" + name + "</b>\
    					</div>\
    					<div class='panel-body'>\
    						<div class='col-md-6' style='height:34px;'>\
    							<select class='form-control input-sm' id='style" + id + "' onchange='onChangedFromCustomizedCourseDishStyle(this)' style='width:150px;'>\
								</select>\
    						</div>\
    						<div class='col-md-6'>\
    							<button class='btn btn-default btn-sm pull-right' name='optionsButton' id='" + button_id + "' onclick='onClickedModalButton(this.id)'\
    								value='option'>Add Here!\
    						</div>\
    					</div>\ <!-- panel-body -->\
    					<ul class='list-group'>\
    						<div id='main" + id + "'>\
    							<!-- javascript : 동적 추가-->\
							</div>\
							<div id='sub" + id + "'>\
								<!-- javascript : 동적 추가-->\
							</div>\
						</ul>\
    				</div> <!-- panel -->\
    			</div>";

            return source_option;
        }
        // 코스 요리의 주 메뉴
        function getMainMenuSource(name, cost) {
            var source_option_main = "<li class='list-group-item'>\
											<b><span class='badge' style=' pull-right'>main</span> " + name + "<b>\
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
            $('#myModal-options').modal('hide');

            if (ccdorderid === '-1' || id === '-1') {
                alert('그렇게 추가할 수 없습니다.');
                return;
            }
            var menudbkey = id;
            var targetccd = orderinginfo.customizedcoursedishlist[ccdorderid];
            var targetccd_main_menu = targetccd['main_menu_list'];
            for (var i in targetccd_main_menu) {
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

            targetccd.totalprice = (targetccd.totalprice * 1 + additionalmenu.cost * 1) + "";

            updateCustomizedCourse();
            updateConfirmOrder();
        }
       

        // 각 코스요리 별 스타일 결정을 위한 함수

        function onChangedFromCustomizedCourseDishStyle(obj) {
            var ccdid = obj.id;
            var msg;
            ccdid = ccdid.replace('style', '');

            msg = obj.value + " 스타일은 " + "코스 요리 가격이 "+style_add_rate[obj.value]+"배 가산됩니다.";

            alert(msg);

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
		<!-- Customizing button -->
		<style>	.btn-custom {border:1px solid orange; color:orange;}
				.btn-custom2 {border:1px solid black; color:black;}
		</style>
		<!-- icon .fa 클래스 display 오버라이딩-->
		<style>.fa{display:inline;}</style>
		<a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'></i> navi</a>
		<nav id='sidebar-wrapper' class=''>
	    	<ul class='sidebar-nav'>
   	        	<a id='menu-close' href='#' class='btn btn-light btn-lg pull-right toggle'><i class='fa fa-times'></i></a>
   	        	<li class='sidebar-brand'>
                	<a href='#top'>Navigator</a>
           		</li>
            	<li>
            		<a href='/Interface/index.php'><i class='fa fa-home'></i>&nbsp;Home</a> 
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
            	<li>
            	    <a href='/Interface/logout.php'><i class='fa fa-unlock'></i>&nbsp;Log Out</a>
            	</li>
        	</ul>
    	</nav>
    <header id='top' class='header'>
    	<div class='text-vertical-center'>
			<h1 style='color:rgba(255,130,0,1); font-size:700%;'>
				<i class='fa fa-cutlery fa-fw' ></i> <b>Order</b>
			</h1>
			<div style='height:50px'></div>
			<a href='#select' class='btn btn-custom' style='font-size:40px; color:orange; font-weight: bold'>Start!</a>
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
            	<a href='#options' class='btn btn-custom' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
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
    		<div class='row' style='background-color:rgba(200,200,200,0.7); border-radius: 30px; margin-right:100px' id='selectedCourse'>

    				
    				<!-- 동적 변화 -->
    				

    			
    		</div> <!-- /.row -->
    		
    		
    		<div class="modal fade" id="myModal-options" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  					<div class="modal-dialog">
    					<div class="modal-content">
      						<div class="modal-header">
        						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        							<h4 class="modal-title">Additional Options</h4>
      						</div>
      						<div class="modal-body">
      							<div class="row">      								      					      							
    								<div class='panel panel-default' style='margin:3%;'>
    									<div class='panel-heading' >
    										<b style='font-size: 20px;'>Additional Options</b>
    									</div>
	    								<ul class='list-group' id = 'additionalOptions'>
	    							    							
    										<!-- 동적 변화 -->
    									   								
    									</ul>
    								</div>	
        						</div>
      						</div>
      						<div class="modal-footer">
        						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      						</div>
    					</div><!-- /.modal-content -->
  					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
    		
    		<div class='col-md-12' align='center' style='padding:50px 0;'>
            	<a href='#confirm' class='btn btn-custom' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
            		Next <i class='fa fa-angle-double-down'></i></a>
           	</div>
        </div><!-- /.container -->
    </section>
    
    <!-- 3. Confirm Order -->
    <section id='confirm' class='confirm'>
    	<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
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
    						<!-- 코스요리 정보 패널 -->
    						
    						
    						
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
							<div class='col-md-3' style='padding:10px;'>
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Name : 
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>		
    							<input type='text' class='form-control input-sm' id='name' disabled="" style='margin:-5px 30px 0 0;'>
    							
    						</div>
    						<div class='col-md-3' style='padding:10px;'>
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Address : 
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>		
    							<input type='text' class='form-control input-sm' id='address' style='margin:-5px 30px 0 0;'>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>	
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Phone Number : 
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>	
    							<input type='text' class='form-control input-sm' id='phonenumber' style='margin:-5px 30px 0 0;'>
    							
    						</div>
    						<div class='col-md-3' style='padding:10px;'>
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Creaditcard Number : 
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>		
    								<input type='text' class='form-control input-sm' id='creditcard' style='margin:-5px 30px 0 0;'>	
    						</div>
    						<!-- Calender -->
    						<div class='col-md-3' style='padding:10px;'>
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Reservation Date & Time :
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>
              					<input type='text' class='form-control input-sm' id='datetimepicker' placeholder='Click here to select time' readonly style='margin:-5px 30px 0 0;'>
    						</div>
    						<div class='col-md-3' style='padding:5px;'>
    							<b style='margin-top:5px;'>
    								&nbsp;<i class='fa fa-check'></i> Discount :
    								<a class="btn btn-custom2 btn-sm" id="checkdiscountstatusbtn">
										(Check my status!)
									</a>	
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>		
    							<input type='text' class='form-control input-sm' id='discounttype' style='margin:-5px 30px 0 0;' disabled="">	
    						</div>
    							
    							<!-- Moddal -->
    							<div class="modal fade" id="myModal-discount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  									<div class="modal-dialog">
    									<div class="modal-content">
      										<div class="modal-header">
        										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        											<h4 class="modal-title">Notice</h4>
      										</div>
      										<div class="modal-body">
      											<div id="discountstatus" class="row" align='center'>    								      					      							
        										</div>
      										</div>
      										<div class="modal-footer">
        										<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      										</div>
    									</div><!-- /.modal-content -->
  									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->

    						
    						<div class='col-md-3' style='padding:10px;'>
    							<b style='margin-top:5px;'>
    								<i class='fa fa-check'></i> Total Price :
    							</b>
    						</div>
    						<div class='col-md-3' style='padding:10px;'>		
    								<input type='text' class='form-control input-sm' id='totalprice' placeholder=''
    									style='margin:-5px 30px 0 0;' disabled="">	
    						</div>
 							
    					</div>
    				</div> <!-- /.panel -->
    			</div>
    		</div> <!-- /.row -->
    		
    		<!-- next page : order complete page -->
    		<div class='col-md-12' style='padding:50px 0;'>
            	<a href='javascript:void(0);' class='btn btn-custom pull-right' id='sendorderinfo' style='font-size:40px; color:orange; font-weight: bold; margin-right:100px;'>
            		Order <i class='fa fa-angle-double-right'></i></a>
           	</div>
    		
        </div>
	</section>
	</body>
</html>




