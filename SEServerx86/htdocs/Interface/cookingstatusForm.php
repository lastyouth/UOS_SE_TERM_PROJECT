<!DOCTYPE html>
<html lang='ko'>
	<head>
		<meta charset='utf-8'/>

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />

		<title>Cooking Status</title>
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

	   	<!-- Bootstrap Table -->
	    <link rel="stylesheet" href="css/bootstrap-table.css">
	   	<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-table.js"></script>
        <script type="text/javascript" src ="js/config.js"></script>
    	<script src='js/customized/wow.min.js'></script>
    	<script src='js/customized/jquery.easing.min.js'></script>

    <!--[if lt IE 9]>
    <script src='http://html5shiv.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->

	</head>
	<!--
		배달요청시간, 코스요리 이름, 코스요리를 구성하는 메뉴들
		
	-->
    <script type="text/javascript">
        $cook_ready_table = null;
        $cook_complete_table = null;

        var menuinfo = [];

        function GetOrderlist() {
           
            var jsonobject = requestserver('cook');

            if (jsonobject === 'error') {
                alert("정보를 받아오는 데 문제가 있습니다.")
                window.location.href = '/Interface/index.php';
            }


            jsonobject = JSON.parse(jsonobject);

            resettable();
            updateprevcookinglist(jsonobject);
            updatecookinglist(jsonobject);
        }
        function updateprevcookinglist(info) 
        {
            // 요리 시작 전 먼저 업데이트
            var prev_cooking_list = info['prev_cooking'];

            for (var i in prev_cooking_list) {
                var obj = prev_cooking_list[i];
                var dbkey = obj.dbkey;
                var requesttime = obj.requested_delivered_time;
                
                var coursestring = "";
                var menustring = "";

                var ccdlist = obj.ccdlist;

                for(var p in ccdlist) {
                    var courseobj = ccdlist[p];
                    var coursename = courseobj.origin_course_name;
                    var stylename = courseobj.stylename;

                    // 코스 이름 추가
                    coursestring += " " + coursename + " / " + stylename + "<br>";
                    // 메뉴의 디비키 목록
                    var menulist = courseobj.menu_names;


                    for (var l in menulist) {
                        // 메뉴의 이름을 가져옴
                        var menudbkey = menulist[l];

                        var menuname = menuinfo[menudbkey].name;

                        // 추가
                        menustring += " " + menuname + " /";
                    }
                }
                // 주문 정보 조직
                $cook_ready_table.bootstrapTable('append', { rtime: requesttime, rname: coursestring, rmenu: menustring,idx:dbkey });
            }
        }
        // 요리 중인 것 업데이트
        function updatecookinglist(info) {
            var cooking_list = info['cooking'];

            for (var i in cooking_list) {
                var obj = cooking_list[i];
                var dbkey = obj.dbkey;
                var requesttime = obj.requested_delivered_time;

                var coursestring = "";
                var menustring = "";

                var ccdlist = obj.ccdlist;

                for (var p in ccdlist) {
                    var courseobj = ccdlist[p];
                    var coursename = courseobj.origin_course_name;
                    var stylename = courseobj.stylename;

                    // 코스 이름 추가
                    coursestring += " " + coursename + " / " + stylename + "<br>";
                    // 메뉴의 디비키 목록
                    var menulist = courseobj.menu_names;

                    for (var l in menulist) {
                        // 메뉴의 이름을 가져옴
                        var menudbkey = menulist[l];

                        var menuname = menuinfo[menudbkey].name;

                        // 추가
                        menustring += " " + menuname + " /";
                    }
                }
                // 주문 정보 조직
                $cook_complete_table.bootstrapTable('append', { ctime: requesttime, cname: coursestring, cmenu: menustring,idx:dbkey });
            }
        }
        // 리셋 테이블
        function resettable() {
            var prev_table = $cook_ready_table.bootstrapTable('getData');
            var removeid = [];

            for (var i in prev_table) {
                var obj = prev_table[i];

                removeid.push(obj.idx);
            }

            $cook_ready_table.bootstrapTable('remove', { field:'idx', values:removeid });

            var cooking_table = $cook_complete_table.bootstrapTable('getData');
            removeid = [];

            for (var i in cooking_table) {
                var obj = cooking_table[i];

                removeid.push(obj.idx);
            }

            $cook_complete_table.bootstrapTable('remove', { field: 'idx', values: removeid });
        }



        function generate() {
            
            // 세션 체크
            // 테이블 초기화
            var obj = { time: '', name: '', menu: '' };

            $cook_ready_table = $("#prev_cooking_table").bootstrapTable({ data: obj });

            $cook_complete_table = $("#cooking_table").bootstrapTable({ data: obj });
            
            // 메뉴 정보를 가져옴
           
            var jsonobject = requestserver('menu');

            if (jsonobject === 'error') {
                alert("정보를 받아오는 데 문제가 있습니다.")
                window.location.href = '/Interface/index.php';
            }

            jsonobject = JSON.parse(jsonobject);

            var menulist = jsonobject['menulist'];

            for(var i in menulist)
            {
                var dbkey = menulist[i].dbkey;
                
                menuinfo[dbkey] = menulist[i];
            }
            GetOrderlist();
            setInterval(GetOrderlist, 5000); // 5초당 1번 업데이트
        }

        //Bootstrap Table
        function operateFormatter_start(value, row, index) {
            return [
		         '<a class="btn btn-default start" href="javascript:void(0)" title="change_status">',
                	'Start&nbsp <i class="fa fa-arrow-circle-right"></i>',
            	'</a>'
        	].join('');
        };

        function operateFormatter_finish(value, row, index) {
            return [
		         '<a class="btn btn-default finish" href="javascript:void(0)" title="change_status">',
                	'<i class="fa fa-check"></i> Finish&nbsp',
            	'</a>'
        	].join('');
        };

        window.operateEvents =
   		{
   		    'click .start': function (e, value, row, index) {
   		        var json = {};
   		        json.dbkey = row['idx'];
   		        json.type = 'start_cooking';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changecookingstatus.php');

   		        var hiddenField = document.createElement("input");
   		        hiddenField.setAttribute("type", "hidden");
   		        hiddenField.setAttribute("name", "jsondata");
   		        hiddenField.setAttribute("value", JSON.stringify(json));
   		        form.appendChild(hiddenField);

   		        form.submit();
   		    },

   		    'click .finish': function (e, value, row, index) {
   		        var json = {};
   		        json.dbkey = row['idx'];
   		        json.type = 'end_cooking';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changecookingstatus.php');

   		        var hiddenField = document.createElement("input");
   		        hiddenField.setAttribute("type", "hidden");
   		        hiddenField.setAttribute("name", "jsondata");
   		        hiddenField.setAttribute("value", JSON.stringify(json));
   		        form.appendChild(hiddenField);

   		        form.submit();
   		    }
   		};

   		window.onload = function () {
   		    sessioncheck('cookingstatusForm.php');
   		    generate();
   		};


    </script>
	<body>
		
		<div class="container">
			
			<h1 align='center' style='margin-top:0; padding-top:3%; color:orange; font-size:500%;'>
    			<b>Cooking Status</b>		

    		</h1>
    		<style>.btn-custom {border:1px solid black; color:black;}</style>
    		<div class='col-md-12' style='margin-bottom:20px;'>
    			<a href="/Interface/logout.php" class="btn btn-custom pull-right" role="button" style='margin-top:20px;'>
					<i class="fa fa-unlock"></i><b> Log Out</b>
				</a>
    		</div>
    		
    		<table id="prev_cooking_table" data-toggle="table" data-height="300" data-sort-name="rtime" data-sort-order="asc"
    				data-pagination="true">
   				<thead>
    				<tr>
        				<th data-field="rtime" data-sortable="true">Reservation Time</th>
        				<th data-field="rname" data-="true">Course　　　　　　　　　　　　　</th>
        				<th data-field="rmenu" data-sortable="true">Menu</th>
        				<th data-field="roper" data-formatter="operateFormatter_start" data-events="operateEvents">Cooking Status</th>
	    			</tr>
    			</thead>
			</table>
    		
    		<table id="cooking_table" data-toggle="table" data-height="300"  data-sort-name="ctime" data-sort-order="asc"  
    				data-pagination="true">
   				<thead>
    				<tr>
        				<th data-field="ctime" data-sortable="true">Reservation Time</th>
        				<th data-field="cname">Course　　　　　　　　　　　　　</th>
        				<th data-field="cmenu">Menu</th>
        				<th data-field="coper" data-formatter="operateFormatter_finish" data-events="operateEvents">Change Status</th>
	    			</tr>
    			</thead>
			</table>
    		
    </div>

		
	</body>
</html>