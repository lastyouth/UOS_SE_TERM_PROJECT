<!DOCTYPE html>
<html lang='ko'>
<head>
    <meta charset='utf-8' />
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
    <link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='screen' title='no title' charset='utf-8' />
    <link rel='stylesheet' href='css/bootstrap-theme.min.css' type='text/css' media='screen' title='no title' charset='utf-8' />
    <link rel='stylesheet' href='newawesome/css/font-awesome.min.css' type='text/css' media='screen'title='no title' charset='utf-8' />
    <link rel='stylesheet' href='newawesome/css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8' />
    <link rel='stylesheet' href='css/font-awesome.min.css' type='text/css' media='screen'title='no title' charset='utf-8' />
    <link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='screen' title='no title' charset='utf-8' />
    <link rel='stylesheet' href='css/style.css' type='text/css' media='screen' title='no title' charset='utf-8' />
    <link rel="stylesheet" href="css/bootstrap-table.css">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-table.js"></script>
    <script src="js/config.js"></script>
</head>
<script type="text/javascript">
        <!--    스크립트-- >
    // 전역 변수
        // 외부 뷰
        $menu_table = null;
        $course_table = null;

        // 코스 추가 팝업 뷰
        $course_menu_candidate = null;
        $course_menu_added = null;
        var added_idx = 0;

        // 메뉴 추가 팝업 뷰
        $menu_ingredient_candidate = null;
        $menu_ingredient_added = null;

        //$result ="";

        window.onload = function(){
            sessioncheck('updatemenuinfoForm.php');
            var obj = {id:'',name:'',price:''};
            $menu_table = $("#menu_table").bootstrapTable({data:obj}).on('all.bs.table', function (e, name, args) {
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
	        });;
            $course_table = $("#course_table").bootstrapTable({data:obj }).on('all.bs.table', function (e, name, args) {
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

            var courseobj_added = {id:'',name:'',price:'',type:''};
            
            $course_menu_candidate = $("#candidate-menu").bootstrapTable({data:obj});
            $course_menu_added = $("#added-menu").bootstrapTable({data:courseobj_added});

            var ingredient_added = {id:'',name:'',amount:''};

            $menu_ingredient_added = $("#addedingredient").bootstrapTable({data:ingredient_added});

            var ingredient_candidate = {id:'',name:''};

            $menu_ingredient_candidate = $("#candidateingredient").bootstrapTable({data:ingredient_candidate});

            generate('course');
            
        };

        // 테이블 리셋 함수
        function resettable(type)
        {
            var oldrow,removeid;
            switch(type)
            {
            case 'courseadd':
                oldrow = $course_menu_candidate.bootstrapTable("getData");
                
                removeid=[];

                for(var i in oldrow){
                    var obj = oldrow[i];

                    removeid.push(obj['cid']);
                }

                $course_menu_candidate.bootstrapTable("remove",{field:'cid',values:removeid});

                oldrow = $course_menu_added.bootstrapTable("getData");

                removeid = [];

                 for(var i in oldrow){
                    var obj = oldrow[i];

                    removeid.push(obj['aid']);
                }

                $course_menu_added.bootstrapTable("remove",{field:'aid',values:removeid});
                break;
            case 'menuadd':
                oldrow = $menu_ingredient_added.bootstrapTable("getData");
                
                removeid=[];

                for(var i in oldrow){
                    var obj = oldrow[i];

                    removeid.push(obj['iid']);
                }

                $menu_ingredient_added.bootstrapTable("remove",{field:'iid',values:removeid});

                oldrow = $menu_ingredient_candidate.bootstrapTable("getData");

                removeid = [];

                 for(var i in oldrow){
                    var obj = oldrow[i];

                    removeid.push(obj['ciid']);
                }

                $menu_ingredient_candidate.bootstrapTable("remove",{field:'ciid',values:removeid});
                break;
            case 'toggle':
                oldrow = $menu_table.bootstrapTable("getData");

                removeid = [];

                for(var i in oldrow)
                {
                    var obj = oldrow[i];

                    removeid.push(obj['mid']);
                }
                $menu_table.bootstrapTable("remove",{field:'mid',values:removeid});

                oldrow = $course_table.bootstrapTable("getData");

                removeid = [];

                 for(var i in oldrow){
                    var obj = oldrow[i];

                    removeid.push(obj['id']);
                }

                $course_table.bootstrapTable("remove",{field:'id',values:removeid});

                break;
            }
        }
        // 데이터를 받아와 페이지를 구성하는 함수
        function generate(type) {
            var httpReq = new XMLHttpRequest();
            var params;

            var menulist;
            var courselist;

            httpReq.open('POST', '/Interface/requestjsondata.php', false);

            httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            switch (type) {
                case 'menu':
                case 'courseadd':
                    params = 'type=menu';
                    break;
                case 'course':
                    params = 'type=course';
                    break;
                case 'menuadd':
                    params = 'type=ingredient';
                    break;
            }

            httpReq.send(params);

            var jsonobject = httpReq.responseText;
            if (jsonobject === "error") {
                window.location.href = "/Interface/mainForm.php";
                return;
            }
            jsonobject = JSON.parse(jsonobject);
            switch (type) {
                case 'menu':
                    menulist = jsonobject['menulist'];

                    for(var i in menulist){
                        var obj = menulist[i];
                        var id,name,price;

                        id = obj['dbkey'];
                        name = obj['name'];
                        price = obj['cost'];
                        ing_list = obj['Ingredient_list'];
                        var detail ="<b>" + name +"</b><br>";

                        for(var j in ing_list)
                        {
                            var ingobj = ing_list[j];
                            detail+="&nbsp&nbsp&nbsp&nbsp"+ingobj['name']+"&nbsp&nbsp"+ingobj['val']+"<br>";
                        }
                        detail+="<br>&nbsp&nbsp-description<br>&nbsp&nbsp&nbsp&nbsp<b>"+obj['description']+"</b><br>"

                        $menu_table.bootstrapTable('append',{mid:id,mname:name,mprice:price,detail:detail});
                    }
                    break;
                case 'course':
                    
                    courselist = jsonobject['courselist'];
                    
                    for(var i in courselist){
                        var obj = courselist[i];
                        var id,name,price;
                        var detail = "";
                        id = obj['dbkey'];
                        name = obj['name'];
                        price = obj['totalprice'];
                        detail += "<b>"+name+"</b><br>";

                        var main = obj['main_menu_list'];
                        var sub = obj['sub_menu_list'];

                        detail += "&nbsp&nbsp-main<br>"

                        for(var j in main)
                        {
                            var mobj = main[j];
                            var mname = mobj['name'];
                            var cost = mobj['cost'];

                            detail+= "&nbsp&nbsp&nbsp&nbsp"+mname+"&nbsp&nbsp&nbsp"+cost+"\\<br>";
                        }

                        detail += "<br>&nbsp&nbsp-sub<br>"

                        for(var j in sub)
                        {
                            var mobj = sub[j];
                            var mname = mobj['name'];
                            var cost = mobj['cost'];

                            detail+= "&nbsp&nbsp&nbsp&nbsp"+mname+"&nbsp&nbsp&nbsp"+cost+"\\<br>";
                        }
                        detail+="<br>&nbsp&nbsp-description<br>&nbsp&nbsp&nbsp&nbsp<b>"+obj['description']+"</b><br>"

                        $course_table.bootstrapTable('append',{id:id,name:name,price:price,detail:detail});
                    }
                    break;
                case 'courseadd':
                    menulist = jsonobject['menulist'];

                    for(var i in menulist){
                        var obj = menulist[i];
                        var id,name,price;

                        id = obj['dbkey'];
                        name = obj['name'];
                        price = obj['cost'];

                        $course_menu_candidate.bootstrapTable('append',{cid:id,cname:name,cprice:price});
                    }
                    break;
                case 'menuadd':
                    ingredientlist = jsonobject['ingredientlist'];

                    for(var i in ingredientlist)
                    {
                        var obj = ingredientlist[i];

                        var id,name;

                        id = obj['dbkey'];
                        name = obj['name'];

                        $menu_ingredient_candidate.bootstrapTable('append',{ciid:id,ciname:name});
                    }
                    break;
            }
            // popover activate

            inittableoperation();
        }
        function inittableoperation() {

	        $('[data-toggle="popover"]').popover({
	            placement: 'top',
	            html: true
	        });
        }
    // Tap action
    $(function () {
        $("#courseTab").click(function (e) {
            // 코스 탭 클릭
           resettable('toggle');
           generate('course');
        });
        $("#menuTab").click(function (e) {
           // 메뉴 탭 클릭
           resettable('toggle');
           generate('menu');
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

        // open Add course popup

        $("#addcoursebutton").click(function(e){
            // 모달 데이터 초기화
            resettable('courseadd');
            generate('courseadd');
            // 다음엔 이방법으로 안한다 시팍
            $("#coursenametext").val("");
            $("#coursedescriptiontext").val("");
            $("#simplecheck").attr("checked",false);
            $("#deluxecheck").attr("checked",false);
            $("#grandcheck").attr("checked",false);

            $("#addcoursemodal").modal();
        });

        // submit Add course popup

        $("#add-course-button").click(function(e){
            var jsondata = {};
            jsondata.type = 'courseadd'; // addcourse
            jsondata.coursetype = [];
            
            // 유효성 검사
            var addedmenu = $course_menu_added.bootstrapTable('getData');
            var isMainAdded = false; // 최소 1개의 메인 메뉴 필요 서브메뉴는 없어도 됨
            var isTypeAdded = false; // 설정되지 않은 타입은 있어서는 안됨
            var isSimpleChecked = $("#simplecheck").is(":checked");
            var isDeluxeChecked = $("#deluxecheck").is(":checked");
            var isGrandChecked = $("#grandcheck").is(":checked"); // 이중 최소 1개는 체크되어야 함

            if(!(isSimpleChecked || isDeluxeChecked || isGrandChecked))
            {
                // 타입이 하나도 선택 안됨
                alert("코스요리는 적어도 하나의 타입을 지원해야 합니다.");
                return;
            }
            if(isSimpleChecked)
            {
                jsondata.coursetype.push("Simple");
            }
            if(isDeluxeChecked)
            {
                jsondata.coursetype.push("Deluxe");
            }
            if(isGrandChecked)
            {
                jsondata.coursetype.push("Grand");
            }

            var coursename = $("#coursenametext").val();
            var coursedescription = $("#coursedescriptiontext").val();

            if(addedmenu.length === 0)
            {
                // 빈 메뉴판
                alert("추가된 메뉴가 하나도 없습니다.");
                return;
            }

            if(coursename === "")
            {
                // 코스요리의 이름이 없음
                alert("코스요리의 이름이 없습니다.");
                return;
            }

            if(coursedescription === "")
            {
                //코스요리의 설명이 없음
                alert("코스요리의 설명이 없습니다.");
                return;
            }

            // 메뉴판 검사

            for(var i in addedmenu)
            {
                var obj = addedmenu[i];

                if(obj['atype'] === '')
                {
                    isTypeAdded = true;
                }
                if(obj['atype'] === 'main')
                {
                    isMainAdded = true;
                }
            }

            if(isTypeAdded)
            {
                alert("코스요리를 구성하는 메뉴 중, 선택되지 않은 타입이 있습니다.");
                return;
            }
            if(!isMainAdded)
            {
                alert("코스요리는 적어도 하나의 메인 메뉴가 존재해야 합니다.");
                return;
            }
            jsondata.menulist = addedmenu;

            $("#jsondatatext").val(JSON.stringify(jsondata));

            // 제출

            $("#add-course-form").submit();            
        });
        // 메뉴 추가 상자 열기
        $("#addmenubutton").click(function(e){
            resettable('menuadd');
            generate('menuadd');
            $("#addmenumodal").modal();
        });

        // 메뉴 추가 요청 제출
        $("#addmenusubmit").click(function(e){
            var json = {};
            json.type = 'menuadd';

            var name = $("#menunametext").val();
            var description = $("#menudescriptiontext").val();
            var price = $("#menupricetext").val();

            if(name === "")
            {
                alert("메뉴 이름이 없습니다.");
                return;
            }
            if(description === "")
            {
                alert("메뉴 설명이 없습니다.");
                return;
            }
            if(price < 0 )
            {
                alert("메뉴 가격이 0원보다 작을 수 없습니다.");
                return;
            }

            var ingredientlist = $menu_ingredient_added.bootstrapTable('getData');

            if(ingredientlist.length === 0)
            {
                alert("추가된 재료가 하나도 없습니다.");
                return;
            }

            var isZeroAmount = false;

            json.ingredientlist = [];

            for(var i in ingredientlist){
                var obj = ingredientlist[i];

                if(obj.iamount === 0)
                {
                    isZeroAmount = true;
                }
                json.ingredientlist.push({dbkey:obj['iid'],amount:obj.iamount});
            }

            if(isZeroAmount)
            {
                alert("추가된 재료 중 필요 수량이 0인 재료가 있습니다.");
                return;
            }
            
            $("#menujsontext").val(JSON.stringify(json));

            $("#addmenuform").submit();
        });
    });    

    //Bootstrap Table
    function operateFormatter_menu(value, row, index) {
        return [
		         '<a class="menuminus" href="javascript:void(0)" title="minus">',
                	'<i class="fa fa-minus"></i> &nbsp',
            	 '</a>',
                 "<a class='menuquestion' href='javascript:void(0)' title='Menu details' data-toggle='popover' tabindex='1' data-trigger='focus' data-content='"+row['detail']+"'>",
                	'<i class="fa fa-question"></i> &nbsp',
            	 '</a>'
        	].join('');
    }

    function operateFormatter_courseadd_added(value, row, index) {
        return [
		         '<a class="courseaddminus" href="javascript:void(0)" title="minus">',
                	'<i class="fa fa-minus"></i> &nbsp',
            	'</a>'
        	].join('');
    }

    function operateFormatter_courseadd_menutype(value, row, index) {
        var source;
        var sourceid = 'selectedtype' + row['idx'];

        // 시밤,,, 이런거 까지 해야하나
        if(row['atype'] === 'main')
        {
            source = [
		        "<select id='"+sourceid+"' class='form-control'>",
  					'<option selected="selected">main</option>',
  					'<option>sub</option>',
				'</select>'
        	].join('');
        }
        else if(row['atype'] === 'sub')
        {
             source = [
		        "<select id='"+sourceid+"' class='form-control'>",
  					'<option>main</option>',
  					'<option selected="selected">sub</option>',
				'</select>'
        	].join('');
        }
        else
        {
            source = [
		        "<select id='"+sourceid+"' class='form-control'>",
                    '<option selected="selected" disabled="disabled">TYPE</option>',
  					'<option>main</option>',
  					'<option>sub</option>',
				'</select>'
        	].join('');
        }
        return source;
    }

    function operateFormatter_courseadd_candidate(value, row, index) {
        return [
            	'<a class="courseaddplus" href="javascript:void(0)" title="plus">',
                	'<i class="fa fa-plus"></i> &nbsp',
            	'</a>'
        	].join('');
    }

    // 메뉴 추가 시 후보 재료의 + 버튼
    function operateFormatter_menuadd_candidate(value,row,index){
        return [
            	'<a class="menuaddplus" href="javascript:void(0)" title="plus">',
                	'<i class="fa fa-plus"></i> &nbsp',
            	'</a>'
        	].join('');
    }

    // 재료의 수량을 적는 인풋 타입
    function operateFormatter_menuadd_amount(value, row, index){
        return [
        "<input id='menuaddamount"+ index +"' type='number' class='form-control' value='"+row['iamount']+"'"
        ].join('');
    }

    // 재료를 빼기위한 버튼
    function operateFormatter_menuadd_added(value,row,index){
        return [
		         '<a class="menuaddminus" href="javascript:void(0)" title="minus">',
                	'<i class="fa fa-minus"></i> &nbsp',
            	'</a>'
        	].join('');
    }

    // 코스 뷰

    function operateFormatter_course(value,row,index){
        return [
            	'<a class="courseminus" href="javascript:void(0)" title="minus">',
                	'<i class="fa fa-minus"></i> &nbsp',
                    '</a>',
                 "<a class='coursequestion' href='javascript:void(0)' title='Course details' data-toggle='popover' tabindex='1' data-trigger='focus' data-content='"+row['detail']+"'>",
                	'<i class="fa fa-question"></i> &nbsp',
            	 '</a>'
        	].join('');
    }

    // 코스요리 추가시, 메뉴간 타입 중복을 검사하는 함수 true면 가능, false면 불가

    function CheckAddedMenuTypeDuplicated(newtype,menuid,menuidx)
    {
        var currenttabledata = $course_menu_added.bootstrapTable('getData');
        var isSuccess = true;

        for( var i in currenttabledata)
        {
            var obj = currenttabledata[i];

            var objid = obj['aid'];
            var objtype = obj['atype'];
            var objidx = obj['idx'];
            if(objtype === 'TYPE')
            {
                // 아직 결정되지 않았다면 패스
                continue;
            }
            if(menuid === objid && objidx !== menuidx)
            {
                if(newtype === 'sub')
                {
                    if(objtype === 'main')
                    {
                        isSuccess = false;
                        break;
                    }
                }
                else
                {
                    if(objtype === 'sub')
                    {
                        isSuccess = false;
                        break;
                    }
                }
            }                        
        }
        return isSuccess;
    }

    window.operateEvents =
   		    {
                
                
                // course tab

                    // course_add
   		        'click .courseaddplus': function (e, value, row, index) {  // 후보 메뉴를 코스에 추가
   		            var id = row['cid'];
                    var name = row['cname'];
                    var price = row['cprice'];
                    
                    $course_menu_added.bootstrapTable('append',{aid:id,aname:name,aprice:price,atype:'',idx:added_idx++});
   		        },
                'click .courseaddminus': function(e,value,row,index){
                    var deleted = [];
                    deleted.push(row['idx']);
                    $course_menu_added.bootstrapTable('remove',{field:'idx',values:deleted});
                },
                'change select.form-control' : function(e,value,row,index){
                    var menuid = row['aid'];
                    var menuidx = row['idx'];
                    var newtype = $("#selectedtype"+menuidx).val();
                    // check, same menu can't be main and sub status samely
                    if(CheckAddedMenuTypeDuplicated(newtype,menuid,menuidx))
                    {
                        row['atype'] = newtype;

                        $course_menu_added.bootstrapTable('updateRow',{index:index,row:row});

                        //$("#selectedtype").val(newtype);
                    }
                    else
                    {
                        alert("같은 메뉴는, 동시에 main 타입과 sub 타입으로 지정될 수 없습니다.");
                        row['atype'] = '';
                        $course_menu_added.bootstrapTable('updateRow',{index:index,row:row});
                        //$("#selectedtype"+menuidx).val();
                    }                    
                },
                // 코스 요리 삭제
                'click .courseminus' : function(e,value,row,index){

                    if(confirm("["+row['name']+"] 이 코스요리에 대한 정보를 정말 삭제할까요?"))
                    {
                        var form = document.createElement('form');
                        var json = {};
                        json.type = 'coursedel';
                        json.courseinfo = row['id'];

                        form.setAttribute("method", 'POST');
                        form.setAttribute("action", '/Interface/updatemenuinfo.php');

                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", "jsondata");
                        hiddenField.setAttribute("value", JSON.stringify(json));
                        form.appendChild(hiddenField);
                        //document.body.appendChild(form);
                        form.submit();
                    }
                },
                // 메뉴의 추가 - 재료 추가
                'click .menuaddplus' : function(e,value,row,index){
                    var id = row['ciid'];
                    var name = row['ciname'];

                    var ingredientlist = $menu_ingredient_added.bootstrapTable('getData');
                    var isduplicated = false;
                    for( var i in ingredientlist){
                        var obj = ingredientlist[i];

                        if(id === obj['iid'])
                        {
                            isduplicated = true;
                        }
                    }
                    if(isduplicated)
                    {
                        alert("재료가 중복됩니다.");
                        return;
                    }
                    $menu_ingredient_added.bootstrapTable('append',{iid:id,iname:name,iamount:0});
                },
                // 메뉴의 추가 - 재료 삭제
                'click .menuaddminus' : function(e,value,row,index){
                    var id = row['iid'];
                    var values=[id];

                    $menu_ingredient_added.bootstrapTable('remove',{field:'iid',values:values});                                        
                },
                // 메뉴의 추가 - 수량 변화
                'change input.form-control' : function(e,value,row,index){
                    var amount = $("#menuaddamount"+index).val()*1;
                    
                    if(amount < 1)
                    {
                        alert('재료의 소모량은 1보다 작을 수 없습니다.');
                        $("#menuaddamount"+index).val(1);
                    }
                    else
                    {
                        row['iamount'] = amount;
                        $menu_ingredient_added.bootstrapTable('updateRow',{index:index,row:row});   
                    }                    
                },
                // 메뉴의 삭제
   		        'click .menuminus': function (e, value, row, index) {
                    if(confirm("["+row['mname']+"] 이 메뉴를 정말 삭제할까요? 메뉴가 코스요리에 포함되어 있을 경우, 해당 코스요리의 정보도 모두 제거됩니다!"))
                    {
                        var form = document.createElement('form');
                        var json = {};
                        json.type = 'menudel';
                        json.menuinfo = row['mid'];

                        form.setAttribute("method", 'POST');
                        form.setAttribute("action", '/Interface/updatemenuinfo.php');

                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", "jsondata");
                        hiddenField.setAttribute("value", JSON.stringify(json));
                        form.appendChild(hiddenField);
                        //document.body.appendChild(form);
                        form.submit();
                    }
   		        },
                // 메뉴의 상세정보
                'click .menuquestion': function(e,value,row,index)
                {
                    //alert('You click like icon, row: ' + JSON.stringify(row));
                },
                // 코스의 상세정보
                'click .coursequestion': function(e,value,row,index)
                {
                    
                }
   		    };
</script>
<body>
    <!-- icon .fa 클래스 display 오버라이딩-->
    <style>
        .fa
        {
            display: inline;
        }
        .popover
        {
            max-width:500px;
            width:auto;            
        }
    </style>
    <!-- 우측 슬라이드 메뉴 -->
    <a id='menu-toggle' href='#' class='btn btn-dark btn-lg toggle'><i class='fa fa-bars'>
    </i></a>
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
            		<a href='/Interface/updatesuppliesinfoForm.php'><i class='fa fa-gear'></i>&nbsp;Ingredients Management</a> 
            	</li>
            	<li>
            		<a href='/Interface/discountpolicyForm.php'><i class='fa fa-gear'></i>&nbsp;Discount Policy</a> 
            	</li>
            	<!-- 구분선 <li class='divider' style='background-color: white;'></li> -->
        	</ul>
    	</nav>
    <div class="container">
        <h1 align='center' style='padding: 3%; color: orange; font-size: 500%;'>
            <b>Food Management</b>
        </h1>
        <div class="food-tap-container">
            <ul class="nav nav-tabs" id="myTab" data-tabs="myTab">
                <li class="active" id="courseTab"><a href="#course" data-toggle="tab">Course Management</a></li>
                <li id="menuTab"><a href="#menu" data-toggle="tab">Menu Management</a></li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active in" id="course">
                    <!-- Button trigger modal -->
                    <button class="btn btn-primary btn-lg " data-toggle="modal" id="addcoursebutton">
                        Add Course to List
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="addcoursemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width: 1000px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Add Course</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <form id="add-course-form" method="post" role="form" action="/Interface/updatemenuinfo.php">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="course-name" class="control-label">
                                                    Course Name :
                                                </label>
                                                <input id="coursenametext" type="text" class="form-control" name="name">
                                                <input id="jsondatatext" type="hidden" name="jsondata" />
                                            </div>
                                            <div class="form-group">
                                                <label for="course-description" class="control-label">
                                                    Course Description :
                                                </label>
                                                <input id="coursedescriptiontext"type="text" class="form-control" name="description">
                                            </div>
                                            <div class="form-group">
                                                <b>Available Course Style : </b>
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input id="simplecheck" name="simple" type="checkbox" value="Simple">
                                                                Simple
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input id="deluxecheck" name="deluxe" type="checkbox" value="Deluxe">
                                                                Deluxe
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input id="grandcheck" name ="grand"type="checkbox" value="Grand">
                                                                Grand
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <b>Added Menus :
                                                    <br>
                                                </b>
                                                <table id="added-menu" data-toggle="table" data-height="400" data-pagination="true">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="aid" data-sortable="true">
                                                                Menu Id
                                                            </th>
                                                            <th data-field="aname" data-sortable="true">
                                                                Name
                                                            </th>
                                                            <th data-field="aprice" data-sortable="true">
                                                                Price
                                                            </th>
                                                            <th data-field="atype" data-formatter="operateFormatter_courseadd_menutype" data-events="operateEvents">
                                                                Main/Sub
                                                            </th>
                                                            <th data-field="aoper" data-formatter="operateFormatter_courseadd_added" data-events="operateEvents">
                                                                Oper
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <table id="candidate-menu" data-toggle="table" data-height="580"
                                                data-search="true" data-show-refresh="true" data-show-toggle="true" data-pagination="true">
                                                <thead>
                                                    <tr>
                                                        <th data-field="cid" data-sortable="true">
                                                            Menu ID
                                                        </th>
                                                        <th data-field="cname" data-sortable="true">
                                                            Name
                                                        </th>
                                                        <th data-field="cprice" data-sortable="true">
                                                            Price
                                                        </th>
                                                        <th data-field="coper" data-formatter="operateFormatter_courseadd_candidate" data-events="operateEvents">
                                                            Oper
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Close</button>
                                    <button type="button" class="btn btn-primary" id="add-course-button">
                                        Submit</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <table id="course_table" data-toggle="table" data-height="400"
                        data-search="true" data-show-refresh="true" data-show-toggle="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-field="id" data-sortable="true">
                                    Course ID
                                </th>
                                <th data-field="name" data-sortable="true">
                                    Course Name
                                </th>
                                <th data-field="price" data-sortable="true">
                                    Price
                                </th>
                                <th data-field="oper" data-formatter="operateFormatter_course" data-events="operateEvents">
                                    Operation
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="menu">
                    <button class="btn btn-primary btn-lg " data-toggle="modal" id="addmenubutton">
                        Add Menu to List
                    </button>
                    <!-- Menu Add Modal -->
                    <div class="modal fade" id="addmenumodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width: 1000px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Add Menu</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <form id="addmenuform" method="post" role="form" action="/Interface/updatemenuinfo.php">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="menu-name" class="control-label">
                                                    Menu Name :
                                                </label>
                                                <input id="menunametext" type="text" class="form-control" name="name">
                                                <input id="menujsontext" type="hidden" name="jsondata" />
                                            </div>
                                            <div class="form-group">
                                                <label for="course-description" class="control-label">
                                                    Menu Description :
                                                </label>
                                                <input id="menudescriptiontext"type="text" class="form-control" name="description">
                                            </div>
                                            <div class="form-group">
                                                <label for="course-description" class="control-label">
                                                    Menu Price :
                                                </label>
                                                <input id="menupricetext"type="number" class="form-control" name="price">
                                            </div>
                                            <div class="form-group">
                                                <b>Added Ingredients :
                                                    <br>
                                                </b>
                                                <table id="addedingredient" data-toggle="table" data-height="380" data-pagination="true">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="iid" data-sortable="true">
                                                                Ingredient Id
                                                            </th>
                                                            <th data-field="iname" data-sortable="true">
                                                                Name
                                                            </th>
                                                            <th data-field="iamount" data-formatter="operateFormatter_menuadd_amount" data-events="operateEvents" data-sortable="true">
                                                                Needed amount
                                                            </th>
                                                            <th data-field="ioper" data-formatter="operateFormatter_menuadd_added" data-events="operateEvents">
                                                                Oper
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <table id="candidateingredient" data-toggle="table" data-height="500"
                                                data-search="true" data-show-refresh="true" data-show-toggle="true" data-pagination="true">
                                                <thead>
                                                    <tr>
                                                        <th data-field="ciid" data-sortable="true">
                                                            Ingredient ID
                                                        </th>
                                                        <th data-field="ciname" data-sortable="true">
                                                            Name
                                                        </th>
                                                        <th data-field="cioper" data-formatter="operateFormatter_menuadd_candidate" data-events="operateEvents">
                                                            Oper
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Close</button>
                                    <button type="button" class="btn btn-primary" id="addmenusubmit">
                                        Submit</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div> <!-- modal-->
                    <table id="menu_table" data-toggle="table" data-height="400"
                        data-search="true" data-show-refresh="true" data-show-toggle="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-field="mid" data-sortable="true">
                                    Menu ID
                                </th>
                                <th data-field="mname" data-sortable="true">
                                    Menu Name
                                </th>
                                <th data-field="mprice" data-sortable="true">
                                    Menu Price
                                </th>
                                <th data-field="moper" data-formatter="operateFormatter_menu" data-events="operateEvents">
                                    Operation
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        
</body>
</html>
