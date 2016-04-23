<!DOCTYPE html>
<html lang='ko'>
	<head>
		<meta charset='utf-8'/>

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />

		<title>Management Page</title>
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
        <script type="text/javascript" src='js/config.js'></script>
	</head>
	<script type="text/javascript">
	    sessioncheck('manageindex.php');
    </script>
	<body>
		<!-- Customizing button -->
		<style>.btn-custom {border:1px solid black; color:black;}</style>
		<div class="container">
			<header align="center" style="margin:50px;">
				<h1 style="color:rgba(255,100,0,1); font-size:700%;">
					<i class="fa fa-gear fa-fw"></i> <b>Management page</b>
				</h1>
				<a href="/Interface/logout.php" class="btn btn-custom pull-right" role="button" style='margin-top:20px;'>
					<i class="fa fa-unlock"></i><b>Log Out</b>
				</a>
			</header>
			
			<div class="row" align="center" style="margin-top:200px;">
				<div class="col-md-4">
				<div class="col-md-12">
					<a class="btn btn-custom btn-lg" href="/Interface/updatesuppliesinfoForm.php" role="button">
						<b style='font-size:25px'>Ingredient<br>Management</b>
					</a>
				</div>
				<div class='col-md-12' style='margin-top:50px; font-size:16px' align='center'>
					"You can add or modify ingredient list or quantity."
				</div>
				</div>

				<div class="col-md-4">
				<div class="col-md-12">
					<a class="btn btn-custom btn-lg" href="/Interface/updatemenuinfoForm.php" role="button">
						<b style='font-size:25px'>Food<br>Management</b>
					</a>
				</div>
				<div class='col-md-12' style='margin-top:50px; font-size:16px' align='center'>
					"You can add or remove dishes."
				</div>
				</div>
				
				<div class="col-md-4">
				<div class="col-md-12">
					<a class="btn btn-custom btn-lg" href="/Interface/discountpolicyForm.php" role="button">
						<b style='font-size:25px'>Discount Rate<br>Management</b>
					</a>
				</div>
				<div class='col-md-12' style='margin-top:50px; font-size:16px' align='center'>
					"You can determine discount policy."
				</div>
				</div>
			</div>
			
			
		</div>

		
	</body>
</html>

