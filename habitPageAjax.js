var serializedData;

var $form = $('form');
var $table = $('table');

$table.on('click', 'tr button', function(){
	var $input = $(this).parent().next().children('input'); // the actual input node
	serializedData = $input.serialize();
	console.log(serializedData);
	$form.submit();
});

$form.on('submit', null, serializedData, function(event) {	// on form submission

	// prevent form submission
	event.preventDefault();
	var request;

	if (request) { request.abort(); } // abort if request is already present
	// serialized data passed through function call
	request = $.ajax({
		url: "receiveAjaxFromPage.php",
		type: "post",
		data: serializedData
	})
	.done(function(data, textStatus, jqueryXHR) {
		console.log('success!!'); console.log(serializedData);
	})
	.fail(function(jqueryXHR, textStatus, errorThrown) {
		console.log('fail!!'); console.log(textStatus); console.log(jqueryXHR);
	})
	.always(function(data_or_jqXHR, textStatus, jqXHR_or_errorThrown){ });
}); // end on submit
