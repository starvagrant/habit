/*
 *	Script utilizing jquery, places event listeners on the form and table.
 *
 *	@table.on listens to the click event on table row buttons. When theg
 *	click event occurs, it calculates a timestamp, and sends it to POST.
 *	by calling the @form.on function
 *
 *	@form.on listens for the submit event and takes data serialized ing
 *	POST to the script receiveAjaxFromPage.php
 */
var serializedData;

var $form = $('form');
var $table = $('table');

$table.on('click', 'tr button', function(){
	// grab data
	var $input = $(this).parent().next().children('input'); // the actual input node
	serializedData = $input.serialize();

	// date calculation
	var rightNow = new Date();
	var timestamp = (rightNow.getTime() - rightNow.getMilliseconds()) / 1000;
	serializedData += '&timestamp=' + timestamp;

	// remove table row (marks habit as complete).
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
	}); // end request
}); // end on submit
