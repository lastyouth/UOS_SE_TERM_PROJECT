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
    	<link href='css/customized_order.css' rel='stylesheet'>
    	
    	<!-- set bootstrap, font, style
    	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>-->
    	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
    	<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="css/font-awesome.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	   
	   	<script src='js/jquery.js'></script>
		<script src='js/bootstrap.min.js'></script>
		<!-- WoW animations -->
		<script src='js/customized/wow.min.js'></script>
		<!-- Easing for image animations -->
		<script src='js/customized/jquery.easing.min.js'></script>
        <script src='js/config.js'></script>
    	
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	</head>
	
	<script>
	    sessioncheck('loginedmainForm.php');
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
    });
	</script>
	
	
	<body style="opacity: 1; background-image: url(image/restaurant_5.jpg); background-size:100% auto;">
		<style>.fa{display:inline;}</style>
		<a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'></i> navi</a>
		<nav id='sidebar-wrapper' class=''>
	    	<ul class='sidebar-nav'>
   	        	<a id='menu-close' href='#' class='btn btn-light btn-lg pull-right toggle'><i class='fa fa-times'></i></a>
   	        	<li class='sidebar-brand'>
                	<a href='#top'>Navigator</a>
           		</li>
            	<li>
            		<a href='#'><i class='fa fa-home'></i>&nbsp;Home</a> 
            	</li>
            	<li>
            		<a href='#top'><i class='fa fa-arrow-circle-up'></i>&nbsp;Top</a> 
            	</li>
            	<li>
            		<a href='/Interface/updatemembershipForm.php'><i class='fa fa-gear'></i>&nbsp;Setting</a> 
            	</li>
            	<li>
            		<a href='/Interface/logout.php'><i class='fa fa-unlock'></i>&nbsp;Log Out</a> 
            	</li>
        	</ul>
    	</nav>
		
       	<div class="container">
			<header align="center">
				<h1 style="color:rgba(255,100,0,1); font-size:700%;">
					<i class="fa fa-home fa-fw" ></i><b>Mr.Mat's Restaurant</b>
				</h1>
				<h3 style="color:rgba(255,100,0,1); font-size:300%;">
					<b>Welcome and Enjoy our Food</b>
				</h3>
			</header>
			
			<!-- Customizing button -->
			<style>.btn-custom {border:1px solid #fff; color:#fff;}</style>
			<div class='row'>
				<div class="col-md-4">
					<div class='col-md-12' style="background-color:rgba(255,150,50,0.7); border-radius: 40px; margin-top:50px; padding:30px;color:white;">
						<a href="/Interface/ordermenuForm.php" class="btn btn-custom btn-lg" role="button"><b>ORDER</b></a>	
						
						<p align="left" style='margin-top:70px;'>
							"You can order our Delicious Dishes"<br>
						</p>
					</div>				
					
				</div>
				
				<div class="col-md-4">
					<div class='col-md-12' style="background-color:rgba(255,150,50,0.7); border-radius: 40px; margin-top:50px; padding:30px;color:white;">
						<a href="/Interface/orderedmenulistForm.php" class="btn btn-custom btn-lg" role="button"><b>PREVIOUS ORDERS</b></a>
						
						<p align="left" style='margin-top:50px;'>
							"You can check and reorder your previous choices"
						</p>
					</div>
										
				</div>
				<div class="col-md-4">
					<div class='col-md-12' style="background-color:rgba(255,150,50,0.7); border-radius: 40px; margin-top:50px; padding:30px;color:white;">
						<a href="/Interface/updatemembershipForm.php" class="btn btn-custom btn-lg" role="button"><b>SETTING</b></a>	
						
						<p align="left" style='margin-top:50px;'> 
							"You can modify your membership information"
						</p>					
					
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