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
    	
    	<!-- Custom CSS-->
    	<link href='css/customized_order.css' rel='stylesheet'>
    	
    	<!-- set bootstrap, font, style -->
    	<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/bootstrap-theme.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
    	<link rel='stylesheet' href='css/font-awesome.min.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
		<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
        <link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" media="all" type="text/css" href="css/jquery-ui-timepicker-addon.css" />

	   
	    <link rel="stylesheet" href="css/bootstrap-table.css">
	   
	   	<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-table.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
	    <script type="text/javascript" src="js/jquery-ui-sliderAccess.js"></script>
    	<!-- WoW animations -->
    	<script src='js/customized/wow.min.js'></script>
    	<!-- Easing for image animations -->
    	<script src='js/customized/jquery.easing.min.js'></script>
        <script src='js/config.js'></script>
	    
	</head>
	
	<script type="text/javascript">
	    $orderedlisttable = null;
        // 디비키에 대한 메뉴
	    var menumap = [];
	    // 스타일 별 가산 가격
	    var style_add_rate = [];

	    // 오더 리스트
	    var orderedlist;

	    // 주문자 정보
	    var userinfo;

	    style_add_rate['Simple'] = 1.0;
	    style_add_rate['Deluxe'] = 1.2; // 디럭스 20%
	    style_add_rate['Grand'] = 1.5; // 그랜드 50%
	    // 재 주문시 할인 여부
	    var discountable = false;
	    var discountpercent;
	    var discountstatus;

	    // orderinginfo 객체 - 재주문을 위한 것
	    var orderinginfo = {};

	    // table 대리 키
	    var tableidx = 0;

	    function getOrderStatus(orderinfo) {
	        var cook_started = orderinfo.cooking_start_time === null ? false:true;
	        var cook_ended = orderinfo.cooking_end_time === null ? false : true;
	        var delivery_started = orderinfo.delivery_start_time === null ? false : true;
	        var delivery_ended = orderinfo.delivery_end_time === null ? false : true;

	        if (!cook_started && !cook_ended && !delivery_started && !delivery_ended) {
	            return "<b style='color:red'>요리 시작 전</b>";
            }
	        if (cook_started && !cook_ended && !delivery_started && !delivery_ended) {
	            return "요리 중";
	        }
	        if (cook_started && cook_ended && !delivery_started && !delivery_ended) {
	            return "요리 완료 / 배달 대기";
	        }
	        if (cook_started && cook_ended && delivery_started && !delivery_ended) {
	            return "배달 중";
	        }
	        if (cook_started && cook_ended && delivery_started && delivery_ended) {
	            return "<b style='color:green'>배달 완료</b>";
	        }
        }


	    function generate() {
	        var obj = { date: '', name: '', price: '', status: '' };
	        $orderedlisttable = $("#orderedmenulist").bootstrapTable({ data: obj
	        }).on('all.bs.table', function (e, name, args) {
	            //alert('Event:'+name+ ', data:'+ args);
	            inittableoperation();
	        }).on('click-row.bs.table', function (e, row, $element) {
	            inittableoperation();
	        }).on('sort.bs.table', function (e, name, order) {
	            inittableoperation();
            }).on('page-change.bs.table', function (e, size, number) {
                inittableoperation();
	        }).on('search.bs.table', function (e, text) {
	            inittableoperation();
	        });

	        var jsontext = requestserver('reorder');

            if(jsontext === 'error') {
                alert("잘못된 정보입니다.");
                window.location.href = "/Interface/index.php";
            }
            jsontext = JSON.parse(jsontext);

            orderedlist = jsontext['orderedlist'];

            menulist = jsontext['menulist'];

            // dbkey to menu object
            for (var i in menulist) {
                var obj = menulist[i];

                var dbkey = obj.dbkey;

                menumap[dbkey] = obj;
            }
            // 할인 여부 확인
            var discount = jsontext['discountpolicy'];
            var targetordercount = discount['target_ordercount'];
            discountpercent = discount['discount_percent'];
            var currentordercount = jsontext['ordercount'];

            // 할인 받기 까지 남은 주문량
            remainordercount = targetordercount - (currentordercount % targetordercount);

            discountable = false;

            if (currentordercount != 0 && currentordercount % targetordercount == 0) {
                document.getElementById('discounttype').setAttribute('value', discountpercent + '%');
                discountstatus = "You can get a " + discountpercent + "% discount!";
                discountable = true;
            }
            else {
                discountstatus = "You need " + remainordercount + " more ordering to get a discount!";
                document.getElementById('discounttype').setAttribute('value', '0%');
            }
            // 테이블 세팅
            for (var i in orderedlist) 
            {
                var orderobj = orderedlist[i];
                var orderkey = orderobj['dbkey'];
                var orderdate = orderobj['order_request_time'];
                var isdiscount = orderobj['isdiscounted'];
                // 가격 초기화
                orderobj.totalcost = 0;


                var coursestring = "";
                var detailstring = "";

                var ccdlist = orderobj['ccdlist'];
                var totalprice = 0;
                
                // 각 코스별 가격 계산
                for(var j in ccdlist) {
                    var courseobj = ccdlist[j];
                    var style = courseobj.stylename;
                    var coursename = courseobj.origin_course_name;
                    var menulist = courseobj['menu_names'];
                    var courseprice = 0;

                    coursestring += "/ " + coursename + " - " + style + "";
                    detailstring += "<b>" + coursename + " - " + style + "</b><br><br>";

                    for(var k in menulist) {
                        var menudbkey = menulist[k];
                        var menuprice = menumap[menudbkey].cost;
                        var menuname = menumap[menudbkey].name;

                        // 디테일 샷
                        detailstring += "&nbsp&nbsp&nbsp" + menuname + "<br>";

                        courseprice += menuprice*1;
                    }
                    // 코스 가격은 스타일 별로 가산됨
                    courseprice *= style_add_rate[style];

                    totalprice += courseprice;
                }
                // 이 주문이 할인 받았을 경우 환불용 가격으로 세팅
                if (isdiscount) {
                    pd = (100 - discountpercent) / 100.0;
                    totalprice *= pd;
                }
                orderobj.totalcost = totalprice; // 이건 환불용 가격

                $orderedlisttable.bootstrapTable('append', { date: orderdate, name: coursestring, price: totalprice, status: getOrderStatus(orderobj), idx: orderkey, tidx: tableidx++ ,detail:detailstring,orderinfo:orderobj});

            }

            
            // 기본 회원 정보
            userinfo = jsontext['userinfo'];


            $("#name").val(userinfo['name']);
            $("#address").val(userinfo['address']);
            $("#phonenumber").val(userinfo['phonenumber']);
            $("#creditcard").val(userinfo['creditcardnum']);
        }
        function getConfirmOrderMenu(name) {
            var source = "<li class='list-group-item'><b>" + name + "</b></li>";
            return source;
        }

        function getConfirmOrderSource(id, coursename, style, totalprice) {
            var source = "<div class='col-md-6'>\
                    			<div class='panel panel-default' style='margin:3%;'>\
    								<div class='panel-heading'>\
                                    <b><i class='fa fa-check'></i>" + coursename + "</b>\
			    						    <b style='color:red;margin:5%'>" + style + "</b>\
			    						    <b style='color:red; float:right'>" + totalprice + " \\</b>\
    								</div>\
    								<div class='panel-body'>\
    									<div class='list-group' id='menus" + id + "'>\
										</div>\
    								</div>\
    							</div>\
                            </div>";

            return source;
        }
        // confirm order modal setting
        function updateConfirmOrder() {
            // 라디오 버튼 선택 초기화
            var idx, courseobj, overallprice = 0;
            document.getElementById('confirmorder').innerHTML = "";

            for (idx in orderinginfo.customizedcoursedishlist) {
                var name, main_list, sub_list;
                courseobj = orderinginfo.customizedcoursedishlist[idx];

                name = orderinginfo.customizedcoursedishlist[idx].name;
                main_list = courseobj.main_menu_list;
                style = courseobj.style;

                totalprice = courseobj.totalprice * style_add_rate[style];

                overallprice += totalprice;

                var panelview = getConfirmOrderSource(idx, name, style, totalprice);

                document.getElementById('confirmorder').innerHTML += panelview;

                // 메인 메뉴 부분을 추가
                document.getElementById('menus' + idx).innerHTML = "";
                for (var i in main_list) {
                    var menuname, menucost;
                    menuname = main_list[i].name;
                    menucost = main_list[i].cost;

                    var mainmenuview = getConfirmOrderMenu(menuname);

                    document.getElementById('menus' + idx).innerHTML += mainmenuview;
                }
            }
            // 토탈 가격을 세팅
            var pd = 1;
            if (discountable) {
                pd = (100 - discountpercent) / 100.0;
            }
            overallprice *= pd;
            document.getElementById('totalprice').setAttribute('value', overallprice);
            orderinginfo.overallprice = overallprice;
        }
	    //popover
        window.onload = function () {

            // session check
            sessioncheck('orderedmenulistForm.php');
            generate();
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

            // 시간 뽑기
            $('#datetimepicker').datetimepicker(
            {
                timeFormat: "hh:mm tt",
                hourMin: 15,
                hourMax: 21,
                numberOfMonths: 2,
                minDate: 0,
                parse: 'loose',
                maxDate: 30
            });
            // discount 여부 체크
            $("#discountcheck").click(function (e) {
                alert(discountstatus);
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
                    orderinginfo.email = userinfo['email'];
                    orderinginfo.type = 'registered';
                    orderinginfo.oper = 'reorder'; // 재주문을 위한 것
                    if (discountable) {
                        orderinginfo.discount = true;
                    }
                    else {
                        orderinginfo.discount = false;
                    }


                    var form = document.createElement('form');

                    form.setAttribute("method", 'POST');
                    form.setAttribute("action", '/Interface/orderedmenulist.php');

                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", "jsondata");
                    hiddenField.setAttribute("value", JSON.stringify(orderinginfo));
                    form.appendChild(hiddenField);
                    form.submit();
                }
            });
            inittableoperation();
        };

	    //Bootstrap Table
	    function operateFormatter(value, row, index) {
	        
	        var i = [
		        "<a href='#' id='popover"+row['tidx']+"'class='btn btn-custom2' data-toggle='popover' tabindex='1' data-trigger='focus' title='Order Property' data-content='"+row['detail']+"'>",
                "<i class='fa fa-bars'></i>",
            	"</a>",
                "<a href='#' class='btn btn-custom2 reorder'>",
                "<i class='fa fa-refresh'></i>",
            	"</a>",
                "<a href='#' class='btn btn-custom2 cancel'>",
                "<i class='fa fa-remove'></i>",
            	"</a>"
        	].join('');
	        return i;
	    }
	    function inittableoperation() {

	        $('[data-toggle="popover"]').popover({
	            placement: 'top',
	            html: true
	        });
        }
        window.operateEvents =
   		    {
   		        'click .reorder': function (e, value, row, index) {  // 재주문
   		            // 여기서 주문 정보를 재조직해야 한다. 안하면 안됨
   		            var prevorderinfo = row['orderinfo']; // 기존 주문 정보 획득

   		            orderinginfo = {}; // 초기화

   		            orderinginfo.customizedcoursedishlist = []; // 이것을 세팅하기 위한 준비


   		            var prevccdlist = prevorderinfo['ccdlist']; // 기존 코스 요리 리스트


   		            for (var i in prevccdlist) {

   		                var courseobj = prevccdlist[i];

   		                var newcourse = {};

   		                // 복사
   		                newcourse.dbkey = courseobj.dbkey;
   		                newcourse.name = courseobj.origin_course_name;
   		                newcourse.style = courseobj.stylename;
   		                newcourse.totalprice = 0;
   		                newcourse.main_menu_list = [];

   		                var menudbkeylist = courseobj.menu_names;

   		                for (var k in menudbkeylist) {
   		                    var menudbkey = menudbkeylist[k];
   		                    var menuobj = menumap[menudbkey];
   		                    if (menuobj === undefined) {
   		                        // 메뉴가 없는 경우 - 삭제된 경우
   		                        alert("이전 주문 메뉴 중, 현재 존재하지 않는 메뉴가 있습니다. 이 주문은 재주문하실 수 없습니다.");
   		                        return;
   		                    }
   		                    var menuprice = menuobj.cost * 1;

   		                    newcourse.main_menu_list.push(menuobj);

   		                    newcourse.totalprice += menuprice;
   		                }
   		                orderinginfo.customizedcoursedishlist.push(newcourse);
   		            }
   		            updateConfirmOrder();

   		            $("#orderModal").modal();
   		        },
   		        'click .cancel': function (e, value, row, index) {  // 주문 취소
   		            if (confirm("정말로 이 주문을 취소하시겠습니까?")) {
   		                var json = {};
   		                var orderkey = row['idx'];
   		                json.dbkey = orderkey;
   		                json.oper = 'cancel';

   		                var form = document.createElement('form');

   		                form.setAttribute("method", 'POST');
   		                form.setAttribute("action", '/Interface/orderedmenulist.php');

   		                var hiddenField = document.createElement("input");
   		                hiddenField.setAttribute("type", "hidden");
   		                hiddenField.setAttribute("name", "jsondata");
   		                hiddenField.setAttribute("value", JSON.stringify(json));
   		                form.appendChild(hiddenField);
   		                form.submit();
   		            }
   		        }
   		    };
    </script>
	
	
	<!--
		
		주문번호, 과거 주문 날짜, 코스이름, 가격, 
		
	-->
	
	<body>
		<style>
		    .btn-custom2 {border:1px solid gray; color:gray;}
		    .popover
            {
                  max-width:500px;
                  width:auto;            
            }
		</style>
		<style>.fa{display:inline;}</style>
		<a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'></i> navi</a>
		<nav id='sidebar-wrapper' class=''>
	    	<ul class='sidebar-nav'>
   	        	<a id='menu-close' href='#' class='btn btn-light btn-lg pull-right toggle'><i class='fa fa-times'></i></a>
   	        	<li class='sidebar-brand'>
                	<a href='#top'>Navigator</a>
           		</li>
            	<li>
            		<a href="/Interface/index.php"><i class='fa fa-home'></i>&nbsp;Home</a> 
            	</li>
            	<li>
            		<a href='#top'><i class='fa fa-arrow-circle-up'></i>&nbsp;Top</a> 
            	</li>
            	<li>
            		<a href="/Interface/updatemembershipForm.php"><i class='fa fa-gear'></i>&nbsp;Setting</a> 
            	</li>
            	<li>
            		<a href="/Interface/logout.php"><i class='fa fa-unlock'></i>&nbsp;Log Out</a> 
            	</li>
        	</ul>
    	</nav>
		<div class="container">
			<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
    			<b>Ordered Menu Information</b>
    		</h1>
    		<h3 align='center' style='color:orange;'>
    			<b>You can order again! (If it is possible now)</b>
    		</h3>
    		
    		<div class="row">
    			<div class='col-md-6'>
				<!-- Modal -->
				<style>.modal .modal-body {overflow-y:auto; max-width:1000px; max-height: 400px;}</style>
				<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  					<div class='modal-dialog' > <!--style="width:1000px;"-->
    					<div class="modal-content">
      						<div class="modal-header">
        						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        							<h4 class="modal-title">Re-Order</h4>
      						</div>
      						<div class="modal-body">


    							<!-- 1. 주문 정보 -->
    							<div class='col-md-12' style='padding:0'>
    								<!-- 주문정보가 출력되는 전체 패널 -->
    								<div class='panel panel-default' style='margin:3%;'>
    									<div class='panel-heading'>
    										<b style='font-size: 20x;'>
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
    							<div class='col-md-12' style='padding:0'>
    								<div class='panel panel-default' style='margin:3%;'>
    									<div class='panel-heading'>
    										<b style='font-size: 20px;'>
    											<i class='fa fa-user'></i> Customer Information
    										</b>
    									</div>
    									<div class='panel-body'>
    										<div class='col-md-6' style='padding:10px;'>
    											<b style='margin-top:5px;'>
    												<i class='fa fa-check'></i> Name : 
    											</b>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>		
    											<input type='text' class='form-control input-sm' id='name' disabled style='margin:-5px 30px 0 0;'>
				    							
    										</div>
    										<div class='col-md-6' style='padding:10px;'>
    											<b style='margin-top:5px;'>
    												<i class='fa fa-check'></i> Address : 
    											</b>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>		
    											<input type='text' class='form-control input-sm' id='address' style='margin:-5px 30px 0 0;'>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>	
    											<b style='margin-top:5px;'>
    												<i class='fa fa-check'></i> Phone Number : 
    											</b>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>	
    											<input type='text' class='form-control input-sm' id='phonenumber' style='margin:-5px 30px 0 0;'>
				    							
    										</div>
    										<div class='col-md-6' style='padding:10px;'>
    											<b style='margin-top:5px;'>
    													<i class='fa fa-check'></i> Creaditcard Number : 
    											</b>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>		
    											<input type='text' class='form-control input-sm' id='creditcard' style='margin:-5px 30px 0 0;'>	
    										</div>
    										<!-- Calender -->
    										<div class='col-md-6' style='padding:10px;'>
    											<b style='margin-top:5px;'>
    												<i class='fa fa-check'></i> Reservation Date & Time :
    											</b>
    										</div>
    										<div class='col-md-6' style='padding:10px;'>
              									<input type='text' class='form-control input-sm' id='datetimepicker' style='margin:-5px 30px 0 0;' placeholder='Click here to select time' readonly>
    										</div>
                                            <div class='col-md-6' style='padding:5px;'>
                                                <b style='margin-top:2px;'>
                                                    <i class='fa fa-check'></i> Discount :
                                                    <a class="btn btn-custom2 btn-sm" id="discountcheck">
										                (Check my status!)
									                </a>
                                                </b>
    							            </div>
    						                <div class='col-md-6' style='padding:10px;'>		
    							                <input type='text' class='form-control input-sm' id='discounttype' style='margin:-5px 30px 0 0;' disabled="">	
    						                </div>
                                            <div class='col-md-6' style='padding:5px;'>
    							                <b style='margin-top:2px;'>
    								                <i class='fa fa-check'></i> Total Price :
    							                </b>
    						                </div>
    						                <div class='col-md-6' style='padding:10px;'>		
    								            <input type='text' class='form-control input-sm' id='totalprice'   style='margin:-5px 30px 0 0;' disabled="">	
    						                </div>
    									</div>
    								</div> <!-- /.panel -->
    							</div> <!-- /. 고객정보 -->	
      						</div> <!-- /. modal body -->
      						
      						<div class="modal-footer">
        						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						<button type="button" class="btn btn-primary" id='sendorderinfo'>Order</button>
      						</div>
      						
    					</div><!-- /.modal-content -->
  					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->	
    			</div>
    			
    			<table id="orderedmenulist" data-toggle="table" data-height="450" data-search="true" data-show-refresh="true" data-pagination="true">
   					<thead>
    					<tr>
        					<th data-field="date" data-sortable="true">Date</th>
        					<th data-field="name" data-sortable="true">Course Name</th>
        					<th data-field="price" data-sortable="true">Price</th>
                            <th data-field="status" data-sortable="true">Status</th>
                            <th data-field="option" data-formatter="operateFormatter" data-events="operateEvents">Options</th>
	    				</tr>
    				</thead>
				</table>
    		</div>
			
		</div>
	</body>
</html>

