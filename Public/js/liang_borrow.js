
//设备借还
function ActionData(tempB,tempC)
{
$.post('action',
{'id':tempB,'Action':tempC},
function refresh(data){
if(data.status){alert(data.info);}
location.reload();
},
'json');
}

//设备查询
function Research(tempA)
{
this.location = 'index?type='+tempA;
}

//编辑设备查询
function Reeditsearch(tempA)
{
this.location = 'edit?type='+tempA;
}


//编辑
$(".liangedit").click(function(){
	
	$(this).parent("td").parent("tr").find(".edit").show();
	$(this).parent("td").parent("tr").find(".old").hide();

// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").show();
// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").prev("td").prev("td").show();
// $(this).parent("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").show();

// $(this).parent("td").hide();
// $(this).parent("td").next().show();
});

//取消
$(".liangcancel").click(function(){
	$(this).parent("td").parent("tr").find(".ntype").children("input").val($(this).parent("td").parent("tr").find(".type").text());
	$(this).parent("td").parent("tr").find(".nid").children("input").val($(this).parent("td").parent("tr").find(".id").text());
	$(this).parent("td").parent("tr").find(".npar").children("input").val($(this).parent("td").parent("tr").find(".par").text());	

	$(this).parent("td").parent("tr").find(".edit").hide();
	$(this).parent("td").parent("tr").find(".old").show();
	
	$(this).parent("td").parent("tr").find(".nsubtype").children("input").val($(this).parent("td").parent("tr").find(".subtype").text());

// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").prev("td").show();
// $(this).parent("td").prev("td").prev("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").prev("td").prev("td").show();
// $(this).parent("td").prev("td").prev("td").hide();
// $(this).parent("td").prev("td").show();

// $(this).parent("td").hide();
// $(this).parent("td").prev().show();
});

//保存
$(".liangsave").click(function(){
var ndid = $(this).parent("td").parent("tr").find(".ndid").text();
var ntype = $(this).parent("td").parent("tr").find(".ntype").children("input").val();
var nid = $(this).parent("td").parent("tr").find(".nid").children("input").val();
var npar= $(this).parent("td").parent("tr").find(".npar").children("input").val();

var nsubtype = $(this).parent("td").parent("tr").find(".nsubtype").children("input").val();

if (ndid && ntype && nid && npar)   
{
	$.post('save',
	{'DID':ndid,'Type':ntype,'ID':nid,'Parameter':npar},
	function refresh(data){
	if(data.status){alert(data.info);}
	location.reload();
	},
	'json');

}
else if (ndid && nsubtype && nid && npar)
{
	$.post('save',
	{'DID':ndid,'SubType':nsubtype,'ID':nid,'Parameter':npar},
	function refresh(data){
	if(data.status){alert(data.info);}
	location.reload();
	},
	'json');
}
else	{
	alert("修改属性不能为空");
	}
});

// $(".liangdamaged").click(function(){
// var did = $(this).parent("td").parent("tr").find(".ndid").text();
// $(this).confirmation('show');

// });

