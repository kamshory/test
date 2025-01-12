var mui;
window.onload = function()
{
	mui = new mobileUI('*');
}
function dynamicSelect(trigger, trigged, url, queryID, method, labelLoading, labelFirst)
{
	labelLoading = labelLoading || 'Loading...';
	labelFirst = labelFirst || '';
	var id = $(trigger).val();
	$(trigged).empty().append('<option value="">'+labelLoading+'</option>');
	var i, j;
	$.ajax({
		url:url,
		data:{id:id},
		type:method,
		dataType:"json",
		success: function(result){
			$(trigged).empty().append('<option value="">'+labelFirst+'</option>');
			for(i in result)
			{
				j = result[i];
				$(trigged).append('<option value="'+j.v+'">'+j.l+'</option>');
			}
		}
	});
}
