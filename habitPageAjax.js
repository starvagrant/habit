var serializedData;

var $form = $('form');
var $table = $('table');

$table.on('click', 'tr button', function(){
	// grab data
	var $input = $(this).parent().next().children('input'); // the actual input node
	serializedData = $input.serialize();
	console.log(serializedData);
	var rightNow = new Date();
	var timestamp = (rightNow.getTime() - rightNow.getMilliseconds()) / 1000;
	serializedData += '&timestamp=' + timestamp;
	console.log(serializedData);
	// remove table row
	$(this).parent().parent().hide();
	$form.submit();
});

$form.on('submit', null, serializedData, function(event) {	// on form submission

	// prevent form submission
	event.preventDefault();
	var request;

	if (request) { request.abort(); } // abort if request is already present

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
	});
}); // end on submit
