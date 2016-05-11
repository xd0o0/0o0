$(function () { $('#new-device').on('hide.bs.modal', function () {
	location.reload();
	})
   });
   

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
	$(this).parent("td").parent("tr").find(".nname").children("input").val($(this).parent("td").parent("tr").find(".name").text());
	$(this).parent("td").parent("tr").find(".nbrand").children("input").val($(this).parent("td").parent("tr").find(".brand").text());
	$(this).parent("td").parent("tr").find(".nmodel").children("input").val($(this).parent("td").parent("tr").find(".model").text());	

	$(this).parent("td").parent("tr").find(".nprice").children("input").val($(this).parent("td").parent("tr").find(".price").text());
	$(this).parent("td").parent("tr").find(".nunit").children("input").val($(this).parent("td").parent("tr").find(".unit").text());	
	
	$(this).parent("td").parent("tr").find(".edit").hide();
	$(this).parent("td").parent("tr").find(".old").show();
	

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
var id = $(this).parent("td").parent("tr").find(".id").text();
var nname= $(this).parent("td").parent("tr").find(".nname").children("input").val();
var nbrand = $(this).parent("td").parent("tr").find(".nbrand").children("input").val();
var nmodel = $(this).parent("td").parent("tr").find(".nmodel").children("input").val();
var nprice= $(this).parent("td").parent("tr").find(".nprice").children("input").val();
var nunit= $(this).parent("td").parent("tr").find(".nunit").children("input").val();

if (id && nname && nbrand && nmodel && nprice && nunit)   
{
	$.post('save',
	{'CabilityID':id,'CabilityName':nname,'Brand':nbrand,'Model':nmodel,'Price':nprice,'Unit':nunit},
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

