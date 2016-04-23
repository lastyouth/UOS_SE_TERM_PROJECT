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
	   
	   
	    <link rel="stylesheet" href="css/bootstrap-table.css">
	   	<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-table.js"></script>
        <script src="js/config.js"></script>
	   
	    
	    <!--<script src='http://getbootstrap.com/js/bootstrap.js'></script>-->

    <!--[if lt IE 9]>
    <script src='http://html5shiv.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->

	</head>
	<script type="text/javascript">
	    // global variables
	    
	    $supplies_table = null;
	   

	    function generate() {
	        // for ingredient data
	        
	        var httpReq = new XMLHttpRequest();
	        var params = 'type=ingredient';

	        httpReq.open('POST', '/Interface/requestjsondata.php', false);

	        httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	        try {
	            httpReq.send(params);
	        }
	        catch (e) {
	            alert(e.message);
	        }

	        var jsonobject = httpReq.responseText;

	        

	        if (jsonobject === "error") {
	            window.location.href = "/Interface/manageindex.php";
	            return;
	        }
	        jsonobject = JSON.parse(jsonobject);
	        var ingredient_objs = jsonobject['ingredientlist'];

	        for (var i in ingredient_objs) {
	            var obj = ingredient_objs[i];

	            var id = obj.dbkey;
	            var name = obj.name;
	            var quantity = obj.val;

	            $supplies_table.bootstrapTable('append', { id: id, name: name, quantity: quantity });
	        }
	    }

	    function operateFormatter(value, row, index) {
	        return [
		              	'<a class="modify" href="javascript:void(0)" title="modify_quantity">',
                    		'<i class="fa fa-gear"></i> &nbsp',
                		'</a>'
            		].join('');
	    }

	    window.operateEvents =
   			{
   			    'click .modify': function (e, value, row, index) {
   			        $('#mod-ingredient-info').val(JSON.stringify(row));
   			        $('#quantityModal').modal();
   			    }
   			};

   			window.onload = function () {
   			    sessioncheck('updatesuppliesinfoForm.php');
	        var obj = {};
	        obj.id = '';
	        obj.name = '';
	        obj.quantity = '';
	        //obj.operate = '';
	        $supplies_table = $('#supply_table').bootstrapTable({ data: obj });

	        generate();
	    };
	    

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
	    });

	    $(function () {	    // Add Supply to list SaveChange 처리 함수
	        $("#add-request-button").click(function () {
	            var k = $('#add-num').val();
	            if (k < 1) {
	                alert("원재료 추가 시 수량은 1보다 작을 수 없습니다.");
	                $('#add-num').val(0);
	            }
	            else {

	                $("#add-supply").submit();
	            }
	        });
	        // modify supply 처리 함수
	        $("#mod-apply-button").click(function () {
	            //alert($("#mod-quantity").val());
	            $("#mod-supply").submit();
	        });
	    });

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
                	<a href='#top'>Order</a>
           		</li>
            	<li>
            		<a href='/Interface/manageindex.php'><i class='fa fa-home'></i>&nbsp;Home</a> 
            	</li>
            	<li>
            		<a href='#'><i class='fa fa-arrow-circle-up'></i>&nbsp;Top</a> 
            	</li>
            	<li>
            		<a href='/Interface/updatemenuinfoForm.php'><i class='fa fa-gear'></i>&nbsp;Food Management</a> 
            	</li>
            	<li>
            		<a href='/Interface/discountpolicyForm.php'><i class='fa fa-gear'></i>&nbsp;Discount Policy</a> 
            	</li>
            	<!-- 구분선 <li class='divider' style='background-color: white;'></li> -->
        	</ul>
    	</nav>

		<div class="container">
			<h1 align='center' style='padding:3%; color:orange; font-size:500%;'>
    			<b>Ingredient Management</b>
    		</h1>
    		
    		<!-- 원재료 추가 창  고정-->
    		<div class="row">
    			
    		</div>
    		
    		<div class="row">
    			<!-- Button trigger modal -->
				<button class="btn btn-primary btn-lg " data-toggle="modal" data-target="#myModal">
					Add Ingredient to List
				</button>
				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  					<div class="modal-dialog">
    					<div class="modal-content">
      						<div class="modal-header">
        						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        							<h4 class="modal-title">Add Ingredient</h4>
      						</div>
                            <form id="add-supply" method="post" role="form" action="/Interface/updatesuppliesinfo.php">
      						<div class="modal-body">
      							<div class="row">      								      					      							
        						
        							<form class="form-horizontal" role="form">
        								<div class="form-group" style="margin-bottom:20px;">
        									<label for="supply-name" class="col-md-3 control-label">Supply Name : </label>
        									<div class="col-md-9">
        										<input type="text" class="form-control" name="supply-name">	
        									</div>        									
        								</div>
        							
        								<div class="form-group">        									
        									<label for="supply-quantity" class="col-md-3 control-label">Quantity : </label>
        									<div class="col-md-9">
        										<input id="add-num" type="number" class="form-control pull-right" name="supply-quantity">
        									</div>						
	        							</div>
        							
        							</form>        						
        						</div>
      						</div>
                            </form>
      						<div class="modal-footer">
        						    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						    <button id='add-request-button' type="button" class="btn btn-primary">Submit</button>
      						</div>
            
    					</div><!-- /.modal-content -->
  					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
    			
    			<table id="supply_table" data-toggle="table" data-height="400" 
    				data-search="true" data-show-refresh="true" data-show-toggle="true" data-pagination="true">
   					<thead>
    					<tr>
        					<th data-field="id" data-sortable="true">Ingredient ID</th>
        					<th data-field="name" data-sortable="true">Name</th>
        					<th data-field="quantity" data-sortable="true">Quantity</th>
        					<th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Operation</th>
	    				</tr>
    				</thead>
				</table>
				
				
				<div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  					<div class="modal-dialog">
    					<div class="modal-content">
      						<div class="modal-header">
        						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        							<h4 class="modal-title">Modify Quantity</h4>
      						</div>
      						<div class="modal-body">
      							<div class="row">      								      					      							
        						<form id="mod-supply" method="post" role="form" action="/Interface/updatesuppliesinfo.php">
        							<form class="form-horizontal" role="form">
        								      							
        								<div class="form-group">        									
        									<label for="supply-quantity" class="col-md-3 control-label">Quantity : </label>
        									<div class="col-md-9">
        										<input type="number" class="form-control pull-right" name="mod-num" id="mod-quantity">
                                                <input type="hidden" id="mod-ingredient-info" name="modinfo" />
        									</div>
                                            <div class="col-md-9" style="text-align:right">
        									    <label class="checkbox">
                                                        <input name ="deletecheck" type="checkbox" value="delete"> Delete this ingredient
                                                </label>
        									</div>								
	        							</div>
        							
        							</form>        						
        						</form>
        						
        						</div>
      						</div>
      						<div class="modal-footer">
        						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						<button id="mod-apply-button" type="button" class="btn btn-primary">Submit</button>
      						</div>
    					</div><!-- /.modal-content -->
  					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
    		</div>
		</div>	
	</body>
</html>

