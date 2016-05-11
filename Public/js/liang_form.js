$(function(){
		$('#form').ajaxForm({
			beforeSubmit:  process,  // pre-submit callback
			success:       complete,  // post-submit callback
			dataType: 'json'
		});
	function process(){
		alert('小二正在处理中....');
		}
	function complete(data){
		alert(data.info);
		if(data.status)
			{
			location.reload();
			}
		}
});