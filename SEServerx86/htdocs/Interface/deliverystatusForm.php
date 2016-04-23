<!DOCTYPE html>
<html lang='ko'>
	<head>
		<meta charset='utf-8'/>

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />

		<title>Delivery Status</title>
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
    	<!-- WoW animations -->
    	<script src='js/customized/wow.min.js'></script>
        <script type="text/javascript" src="js/config.js"></script>
    	<!-- Easing for image animations -->
	
       
	</head>
	
	<!--
		
		주문자 이름, 전화번호, 주소, 가격, 배달 요청 시간 
	
	-->
    <script type="text/javascript">
        $delivery_ready_table = null;
        $delivery_complete_table = null;

        function GetOrderlist() {

            var jsonobject = requestserver('delivery');

            if (jsonobject === 'error') {
                alert("정보를 받아오는 데 문제가 있습니다.")
                window.location.href = '/Interface/index.php';
            }


            jsonobject = JSON.parse(jsonobject);

            resettable();
            updateprevdeliverylist(jsonobject);
            updatedeliverylist(jsonobject);
        }
        function updateprevdeliverylist(info) 
        {
            // 배달 시작 전 먼저 업데이트
            var prev_delivery_list = info['prev_delivering'];

            for (var i in prev_delivery_list) {
                var obj = prev_delivery_list[i];
                var dbkey = obj.dbkey;
                var requesttime = obj.requested_delivered_time;
                var userinfo = obj.userinfo;
                var name = userinfo['name'];
                var price = obj.totalcost;
                var address = userinfo['address'];
                var phonenumber = userinfo['phonenumber'];
                

                // 주문 정보 조직
                $delivery_ready_table.bootstrapTable('append', { stime: requesttime, sname: name, sphonenumber: phonenumber,saddress:address,sprice:price,idx:dbkey });
            }
        }
        // 배달 중인 것 업데이트
        function updatedeliverylist(info) {
            var delivery_list = info['delivering'];

            for (var i in delivery_list) {
                var obj = delivery_list[i];
                var dbkey = obj.dbkey;
                var requesttime = obj.requested_delivered_time;
                var userinfo = obj.userinfo;
                var name = userinfo['name'];
                var price = obj.totalcost;
                var address = userinfo['address'];
                var phonenumber = userinfo['phonenumber'];

                // 배달 정보 조직
                $delivery_complete_table.bootstrapTable('append', { etime: requesttime, ename: name, ephonenumber: phonenumber,eaddress:address,eprice:price,idx:dbkey });
            }
        }
        // 리셋 테이블
        function resettable() {
            var prev_table = $delivery_ready_table.bootstrapTable('getData');
            var removeid = [];

            for (var i in prev_table) {
                var obj = prev_table[i];

                removeid.push(obj.idx);
            }

            $delivery_ready_table.bootstrapTable('remove', { field:'idx', values:removeid });

            var delivery_table = $delivery_complete_table.bootstrapTable('getData');
            removeid = [];

            for (var i in delivery_table) {
                var obj = delivery_table[i];

                removeid.push(obj.idx);
            }

            $delivery_complete_table.bootstrapTable('remove', { field: 'idx', values: removeid });
        }



        function generate() {
            
            // 세션 체크
            sessioncheck('deliverystatusForm.php');
            // 테이블 초기화
            var obj = { time: '', name: '', phonenumber: '',address:'',price:'' };

            $delivery_ready_table = $("#prev_delivery_table").bootstrapTable({ data: obj });

            $delivery_complete_table = $("#delivery_table").bootstrapTable({ data: obj });
            
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
   		        json.type = 'start_delivery';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changedeliverystatus.php');

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
   		        json.type = 'end_delivery';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changedeliverystatus.php');

   		        var hiddenField = document.createElement("input");
   		        hiddenField.setAttribute("type", "hidden");
   		        hiddenField.setAttribute("name", "jsondata");
   		        hiddenField.setAttribute("value", JSON.stringify(json));
   		        form.appendChild(hiddenField);

   		        form.submit();
   		    }
   		};

        window.onload = function () {
            sessioncheck('deliverystatusForm.php');
            generate();
        };

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
   		        json.type = 'start_delivery';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changedeliverystatus.php');

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
   		        json.type = 'end_delivery';

   		        var form = document.createElement('form');

   		        form.setAttribute("method", 'POST');
   		        form.setAttribute("action", '/Interface/changedeliverystatus.php');

   		        var hiddenField = document.createElement("input");
   		        hiddenField.setAttribute("type", "hidden");
   		        hiddenField.setAttribute("name", "jsondata");
   		        hiddenField.setAttribute("value", JSON.stringify(json));
   		        form.appendChild(hiddenField);

   		        form.submit();
   		    }
   		};

    </script>
		
	<body>
		
		<div class="container">
			
			<h1 align='center' style='margin-top:0; padding-top:3%; color:orange; font-size:500%;'>
    			<b>Delivery Status</b>		

    		</h1>
    		<style>.btn-custom {border:1px solid black; color:black;}</style>
    		<div class='col-md-12' style='margin-bottom:20px;'>
    			<a href="/Interface/logout.php" class="btn btn-custom pull-right" role="button" style='margin-top:20px;'>
					<i class="fa fa-unlock"></i><b> Log Out</b>
				</a>
    		</div>
    		
    		<table id="prev_delivery_table" data-toggle="table" data-url="data1.json" data-height="300" 
    				data-pagination="true">
   				<thead>
    				<tr>
                        <th data-field="stime" data-sortable="true">Reservation Time</th>
        				<th data-field="sname" data-sortable="true">Name</th>
        				<th data-field="sphonenumber" data-sortable="true">Phone Number</th>
        				<th data-field="saddress" data-sortable="true">Address</th>
        				<th data-field="sprice" data-sortable="true">Price</th>
        				<th data-field="soper" data-formatter="operateFormatter_start" data-events="operateEvents">Delivery Status</th>
	    			</tr>
    			</thead>
			</table>
    		
    		<table id="delivery_table" data-toggle="table" data-url="data1.json" data-height="300" 
    				data-pagination="true">
   				<thead>
    				<tr>
                        <th data-field="etime" data-sortable="true">Reservation Time</th>
        				<th data-field="ename" data-sortable="true">Name</th>
        				<th data-field="ephonenumber" data-sortable="true">Phone Number</th>
        				<th data-field="eaddress" data-sortable="true">Address</th>
        				<th data-field="eprice" data-sortable="true">Price</th>
        				<th data-field="eoper" data-formatter="operateFormatter_finish" data-events="operateEvents">Delivery Status</th>
	    			</tr>
    			</thead>
			</table>
    		
    </div>
	</body>
</html>