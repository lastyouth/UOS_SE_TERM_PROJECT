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
        <link rel='stylesheet' href='css/pie-chart.css' type='text/css' media='screen' title='no title' charset='utf-8'/>
        
        <script src='js/jquery.js'></script>
    	<script src='js/bootstrap.js'></script>

    	<!-- WoW animations -->
    	<script src='js/customized/wow.min.js'></script>

    	<!-- Easing for image animations -->
    	<script src='js/customized/jquery.easing.min.js'></script>
        <script src='js/pie-chart.js'></script>
        <script src="js/config.js"></script>
        
	</head>
    <script type="text/javascript">
        function generate() {
            // Tap action
            $("#myTab").click(function (e) {
                $('#myTab').tab();
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

            //fly me to the moon/
            // 정보 가져 오기
            var jsontext = requestserver('discountpolicy');


            jsontext = JSON.parse(jsontext);

            /*
                private $target_ordercount;
                private $discount_percent;
            */
            var discountpolicy = jsontext['discountpolicy'];

            var text = discountpolicy['discount_percent'] + "% discount per " + discountpolicy['target_ordercount'] + " time order.";

            $("#currentdiscountrate").val(text);

            $("#sendpolicy").click(function (e) {
                var targettime = $("#targettime").val();
                var discountrate = $("#discountrate").val();

                if (discountrate < 0 && discountrate > 100) {
                    alert("할인율은 0 이상 100이하여야 합니다.");
                    return;
                }
                if (targettime < 1) {
                    alert("주문 횟수는 1보다 커야합니다.");
                    return;
                }
                $("#discountpolicyform").submit();
            });
        }

        window.onload = function () {
            sessioncheck('discountpolicyForm.php');
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
        };
    </script>
	<body>
		<!-- icon .fa 클래스 display 오버라이딩-->
		<style>.fa{display:inline;}</style>
		<!-- 우측 슬라이드 메뉴 -->
		<a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'></i></a>
		<nav id='sidebar-wrapper' class=''>
	    	<ul class='sidebar-nav'>
   	        	<a id='menu-close' href='#' class='btn btn-light btn-lg pull-right toggle'><i class='fa fa-times'></i></a>
   	        	<li class='sidebar-brand'>
              		<a href='#top'>Menu</a>
           		</li>
            	<li>
            		<a href='/Interface/manageindex.php'><i class='fa fa-home'></i>&nbsp;Home</a> 
            	</li>
            	<li>
            		<a href='#'><i class='fa fa-arrow-circle-up'></i>&nbsp;Top</a> 
            	</li>
            	<li>
            		<a href='/Interface/updatesuppliesinfoForm.php'><i class='fa fa-gear'></i>&nbsp;Ingredient Management</a> 
            	</li>
            	<li>
            		<a href='/Interface/updatemenuinfoForm.php'><i class='fa fa-gear'></i>&nbsp;Food Management</a> 
            	</li>
        	</ul>
    	</nav>

		<div class="container">
			<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
    			<b>Discount Rate Management</b>
    		</h1>
			
			<div class='row' style="margin-top:40px;">
				<div class="col-md-6">
					<h1 style='font-weight: bold; margin-bottom: 50px;' align="center">
						<i class='fa fa-bar-chart fa-fw'></i> (ex)Yearly Sale
					</h1>
					<section>
   						<img src='image/pie-chart.jpg'>
  					</section>	
				</div>
				
				<div class="col-md-6">
					<h1 style='font-weight: bold; margin-bottom: 50px;' align="center">
						<i class='fa fa-gift fa-fw'></i> Discount Rate
					</h1>
					<form id='discountpolicyform' method="post" action="/Interface/updatediscountpolicy.php">
						
						<div class='col-md-4' style='padding:10px;'>
    						<b style='font-size:18px;'>
    								Current Policy :  
    						</b>
    					</div>
    					<div class='col-md-8' style='padding:10px;'>		
    							<input type='text' class='form-control input-sm' id='currentdiscountrate'style='margin:-5px 30px 0 0;' disabled>
    					</div>
						
						<div class='col-md-4' style='padding:10px;'>
    						<b style='font-size:20px;'>
    								Discount rate
    						</b>
    					</div>
    					<div class='col-md-4' style='padding:10px;'>		
    							<input type="text" pattern="\d*" required="" class='form-control input-sm' id='discountrate' name='discountpercent' style='margin:-5px 30px 0 0;'>
    					</div>
    					<div class='col-md-4' style='padding:10px;'>
    						<b style='font-size:20px;'>
    								%
    						</b>
    					</div>
    					
    					<div class='col-md-4' style='padding:10px;'>
    						<b style='font-size:20px;'>
    								Per
    						</b>
    					</div>
    					<div class='col-md-4' style='padding:10px;'>		
    							<input type="text" pattern="\d*" required="" class='form-control input-sm' id='targettime' name='ordertime' style='margin:-5px 30px 0 0;'>
    					</div>
    					<div class='col-md-4' style='padding:10px;'>
    						<b style='font-size:20px;'>
    							times ordered
    						</b>
    					</div>
    					</form>
    					<div class='col-md-offset-5 col-md-7' style='padding:10px; margin-top:50px;'>
    						<button class='btn btn-warning btn-lg' id='sendpolicy'>
    							Apply
    						</button> 
    					</div>
					
				</div>
				
			</div>

      	</div>
	</body>
</html>

