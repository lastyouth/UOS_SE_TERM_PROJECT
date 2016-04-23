<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8"/>

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Welcome to Mr.Mat's Restaurant</title>
		<meta name="description" content="" />
		<meta name="author" content="T1000" />

		<!-- 반응형 UI -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    	<!-- <link href="assets/css/bootstrap-responsive.css" rel="stylesheet"> -->
    	
    	<!-- Custom CSS-->
    	<link href="css/customized_order.css" rel="stylesheet">
    	
    	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="css/font-awesome.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
        <link rel='stylesheet' href='newawesome/css/font-awesome.min.css' type='text/css' media='screen'title='no title' charset='utf-8' />
        <link rel='stylesheet' href='newawesome/css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8' />
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	    <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/config.js"></script>
	</head>
	
	<script type="text/javascript">
	    sessioncheck('registerForm.php');
	    $(function () {
	        $("#registerbutton").click(function (e) {
	            var isAllPrep = true;
	            var jsona = {};

	            for (var i = 1; i <= 7; i++) {
	                var id = "#d" + i;

	                if ($(id).val() === "") {
	                    isAllPrep = false;
	                }
	                //alert($(id).attr("name"));
	                var name = $(id).attr("name");
	                var val = $(id).val();
	                //json.push({ key: name, value: val });
	                jsona[name] = val;
	            }
	            $('#json').val(JSON.stringify(jsona));
	            if (isAllPrep) {
	                if ($("#d3").val() !== $("#d4").val()) {
	                    alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
	                    $("#d4").val("");
	                }
	            }
	            var isagreed = $("#agreement").is(":checked");

	            if (!isagreed) {
	                alert("가입에 동의하지 않으시면, 진행할 수 없습니다.");
	            }
	        });
	    });
    </script>
	
	
	<body>
		<header style="margin:50px 0; text-align: center;">
				<h1 style="color:rgba(255,100,0,1); font-size:700%;">
					<i class="fa fa-users fa-fw" ></i> <b>Register</b>
				</h1>
		</header>
		
		<!-- 회원가입 폼 상,하 여백 오버라이딩, Input box 크기 지정 -->
		<style>
			.col-md-4 {height:50px; padding:10px 0; font-size:20px}
			.col-md-8 {height:50px; padding:10px 0;}
			.name {width: 150px;}
			.email {width: 350px;}
			.password {width: 200px;}
			.creditcard {width: 200px;}
		</style>
		<div class="container">
			<!-- 회원가입 폼 -->
			<form method="post" action="/Interface/registerasmembership.php" role="form">
	
			<div class="row" style="margin-top:50px;">
				<!-- 왼쪽 폼 -->
				<div class="col-md-6">
					<div class="col-md-4">
						<b><i class="fa fa-check"></i> Name :</b>
					</div>
					<div class="col-md-8 ">
						<input type="text" class="form-control name" id="d1" name="name" maxlength="20" autofocus="" required="">
					</div>
					<div class="col-md-4 ">
						<b><i class="fa fa-check"></i> Email(ID) :</b>
					</div>
					<div class="col-md-8 ">
						<input type="email" class="form-control email" id="d2" name="email" required="">
					</div>
					<div class="col-md-4 ">
						<b><i class="fa fa-check"></i> Password :</b>
					</div>
					<div class="col-md-8 ">
						<input type="password" class="form-control password" id="d3" name="password" required="">
					</div>
					<div class="col-md-4 ">
						<b><i class="fa fa-check"></i> Confirm Pwd :</b>
					</div>
					<div class="col-md-8 ">
						<input type="password" class="form-control password" id="d4" name="confirmpassword" required="">
					</div>
				</div>
					
				<!-- 오른쪽 폼 -->
				<div class="col-md-6">
					<div class="col-md-4">
						<b><i class="fa fa-check"></i> Address :</b>
					</div>
					<div class="col-md-8 ">
						<input type="text" class="form-control" id="d5" name="address" required="">
					</div>
					<div class="col-md-4">
						<b><i class="fa fa-check"></i> Creditcard No :</b>
					</div>
					<div class="col-md-8 ">
						<input type="text" class="form-control creditcard" id="d6" name="creditcard" maxlength="16" required="">
					</div>
					<div class="col-md-4">
						<b><i class="fa fa-check"></i> Phone No :</b>
					</div>
					<div class="col-md-8 ">
						<input type="text" class="form-control creditcard" id="d7" name="phonenumber" maxlength="11" required="">
					    <input type="hidden" id="json" name="jsondata">
                    </div>
				</div>
				
				<div class='col-md-12' align='center' style='margin-top:20px'>
				    <label class="checkbox" style='font-size:20px;'>
                        <input id ="agreement" style="width:20px;height:20px;margin-top:5px;" type="checkbox" value="delete" required="">　I Agree for Registration
                    </label>
				</div>
			</div>
            
		<div class="row" style="margin-top:10px">
			<div class="col-md-offset-4 col-md-2" style="margin-top:50px;">
				<a class="btn btn-default btn-lg" href="javascript:history.back()" role="button"><b>Cancel</b></a>
			</div>
			<div class="col-md-2" style="margin-top:50px;">
				<button id="registerbutton" class="btn btn-default btn-lg"><b>Register</b></button>
			</div>
		</div>
        </form>
		</div>
	</body>
</html>
	