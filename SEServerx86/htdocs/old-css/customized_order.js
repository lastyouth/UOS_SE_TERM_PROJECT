//var httpresponse;
//var courseMenuList = JSON.parse(httpresponse);
var courseMenu=[{},{},{}];
var courseMenuList=[];
courseMenu[0].name = 'French Course With Foie gras';
courseMenu[0].price = '19,000';

courseMenu[1].name = 'Korean Table dhote With SinSeonRo';
courseMenu[1].price = '18,000';

courseMenu[2].name = 'Italian Course With Gorgonzola';
courseMenu[2].price = '15,000';


var userSelectionList = [];
		
courseMenuList.push(courseMenu[0]);
courseMenuList.push(courseMenu[1]);
courseMenuList.push(courseMenu[2]);

window.onload = function()
{
	var a;
	for(a = 0;a<courseMenuList.length;a++)
	{
		var menuname,price,id,source;
		id = a;
		menuname = courseMenuList[a].name;
		price = courseMenuList[a].price;
		
		source = "<a class='list-group-item'>\
					"+menuname+"\
					<button id='"+ id +"'type='submit' onclick='onClickedBySelection(this.id)' class='btn btn-default btn-xs pull-right'>\
						"+price+"\&nbsp;<i class='fa fa-arrow-right'></i></button></a>";
			
		document.getElementById('courseMenu').innerHTML += source; 	
	}		
};

function onClickedBySelection(id)
{
	if(!(id in userSelectionList))
	{
		userSelectionList[id]={};
		userSelectionList[id].count = 1;	
	}
	else
	{
		userSelectionList[id].count++;
	}
	var name = courseMenuList[id].name;			
	
	var source="<li class='list-group-item'>\
							<form class='form-inline' role='form'>\
								"+name+"\
								<div class='input-group input-group-sm pull-right' style='width:100px; margin-top:-5px;'>\
									<input type='number' class='form-control' placeholder='"+userSelectionList[id].count+"'>\
									<span class='input-group-addon'>EA</span>\
								</div>\
							</form>\
						</li>";
	userSelectionList[id].source = source;
	
	var viewSource="";
	
	for(var p in userSelectionList)
	{
		viewSource+=userSelectionList[p].source;
	}
	
	document.getElementById('courseChoice').innerHTML = viewSource;
}	
