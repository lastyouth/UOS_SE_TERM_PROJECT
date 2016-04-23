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
		
		<!-- Custom CSS-->
        <link href='css/customized_order.css' rel='stylesheet'>
        
        
    	<link rel="stylesheet" href="/Interface/css/bootstrap.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="/Interface/css/bootstrap.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="/Interface/css/bootstrap-theme.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="/Interface/css/font-awesome.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="/Interface/css/font-awesome.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="/Interface/css/style.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
        <script type="text/javascript" src = '/Interface/js/config.js'></script>

	</head>

    <script type="text/javascript">
        function generate() {
            sessioncheck('index.php');
        }

        generate();

    </script>

	<body class='index'>
       	<div class="container">
			<header align="center">
				<h1 style="color:rgba(255,100,0,1); font-size:700%;">
					<i class="fa fa-home fa-fw" ></i> <b>Mr.Mat's Restaurant</b>
				</h1>
				<h3 style="color:rgba(255,100,0,1); font-size:300%;">
					<b>Welcome and Enjoy our Food</b>
				</h3>
			</header>
			
			<div class="row" style="background-color:rgba(255,255,255,0.7); border-radius: 40px; margin-top:50px;">
				<div class="col-md-4 block">
					<h2 align="center"><b>Login</b></h2>
					<p align="center">
						"You have a lot of benefits"
					</p>
					<form class="form-horizontal" role="form" action="/Interface/login.php" method="post">
						<div class="form-group">
			   				<label class="col-md-3 control-label">Email</label>
			  				<div class="col-md-9">
			  					<input type="email" class="form-control" name="email" placeholder="Enter Email" required="" autofocus="">
                                <input type="hidden" class="form-control" name="type" value="registered">
			 				</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Password</label>
							<div class="col-md-9">
								<input type="password" class="form-control" name="password" placeholder="Enter Password" required="">
						    </div>
						</div>						  
			  			 <div class="form-group">
			  			 	<div class="col-md-12" align="center">
			  			 		<div class="controls">
			  			    		<button type="submit" id="registeredbtn" class="btn btn-primary btn-lg">Log In</button>
			  			   		</div>
			  			 	</div>
			 			 </div>			 			
					</form>
				</div>
				
				<div class="col-md-4 block">
					<h2 align="center"><b>Unregistered Login</b></h2>
					<p align="center">
						"You can order ONLY"
					</p>
					<form class="form-horizontal" role="form" action="/Interface/login.php" method="post">
						<div class="form-group">
			   				<label class="col-md-3 control-label" for="inputEmail">Name</label>
			  				<div class="col-md-9">
			  					<input type="text" class="form-control" name="name" placeholder="Enter Name" required="" autofocus="">
                                <input type="hidden" class="form-control" name="type" value="unregistered">
			 				</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="inputPassword">Address</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="address" placeholder="Enter Address" required="">
						    </div>
						</div>						  
						<div class="form-group">
							<label class="col-md-3 control-label" for="inputPassword">Phone Number</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="phonenumber"  maxlength="11" placeholder="Enter Phone Number" required="">
						    </div>
						</div>	
						<div class="form-group">
							<label class="col-md-3 control-label" for="inputPassword">Creditcard Number</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="creditcard"  maxlength="16" placeholder="Enter Creditcard Number" required="">
						    </div>
						</div>	
			  			<div class="form-group">
			  				<div class="col-md-12" align="center">
			  			 		<div class="controls">
			  			    		<button type="submit" id="unregisteredbtn" class="btn btn-primary btn-lg">Log In</button>
			  			   		</div>
			  			 	</div>
			 			</div>
					</form>
				</div>
				<div class="col-md-4 block">
					<h2 align="center"><b>Register</b></h2>
					<p align="center"> 
						"Go to registeration page"
					</p>
					<div class="form-group">
			  			<div class="col-md-12" align="center">
			  			 	<div class="controls">
			  			    	<a href="/Interface/registerForm.php" class="btn btn-primary btn-lg" role="button">Register</a>
			  			   	</div>
			  			 </div>
			 		</div>
				</div>
			</div>
			<footer>
				<p> 
					&copy; Copyright By T1000
				</p>
			</footer>
		</div>
	</body>
</html>