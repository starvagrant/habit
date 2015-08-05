$( document ).ready( function(){
	if(!console.log) alert ('console not working');
	console.log('yoyoyo');
	var serializedData;
	var $form = $('form#habit-complete');
	var $submitButton = $('button#submit-button');
	var $restoreButton = $('button#restore-button');
	var $checkboxes = $('input[type=checkbox]');
	var $feedBackList = $('ul#feedback-list');

//////////////////////////////////////////////////////////////// UI events
	$restoreButton.hide();

																			// user clickes form check boxes
	$checkboxes.on('change', function(event){
		console.log('yoyoyo');
		// put data in global serialized string $form.submit will use
		serializedData = $(this).parent().children().serialize( "input" );
//		console.log(serializedData);

		$form.submit();
		// reset checkboxes to original state (checked attribute)
		if ($(this).attr('checked')) $(this).prop('checked', true);
		if (!$(this).attr('checked')) $(this).prop('checked', false);
		// the document's html: li > label > input, li needs to be hideen
		$(this).parent().parent().hide();
	});

	$submitButton.on('click', function(event){								// user clicks submit button
		event.preventDefault();
		serializedData = $form.serialize();
		console.log(serializedData);
		$form.submit();
		$form.hide();
		$(this).show();
		$restoreButton.show();
	});

	$restoreButton.on('click', function(event){								// user clicks restore button
		event.preventDefault();
		$(this).hide();
		$form.show();
		// the document's html: li > label > input, li needs to be shown
		$checkboxes.parent().parent().show();
		setTimeout(function(){			// automatic form submission, after interval
			serializedData = $form.serialize();
			$form.submit();
			$form.hide();
			$restoreButton.show();
	}, 3600000);

	});

//////////////////////////////////////////////////////////////// Ajax
	//
	$( document ).ajaxSuccess(function (event, xhr, settings){				// on Ajax success
		var habitInfoObject = JSON.parse(xhr.responseText);
		var listItem;
//		major debug line
//		var listItem = '<li class="response-text">' + xhr.responseText + ' </li>';
		listItem += '<li> ' + habitInfoObject.habit_leveled + ' </li>';

		if (habitInfoObject.priority == 0) { 
			listItem += '<li> ' + habitInfoObject.habit_name + ' not a priority ' + '</li> ';
		}

		if (habitInfoObject.priority == 1 && habitInfoObject.habit_leveled !== 'true') {
			listItem += '<li> ' + habitInfoObject.habit_name + ' not leveled ' + ' </li> ';
		} else if (habitInfoObject.priority == 1) { 
			listItem += '<li> ' + habitInfoObject.habit_name + ' leveled ' + ' </li> ';
		}

//		console.dir(habitInfoObject);
		$feedBackList.append(listItem);	
	});	
	$form.on('submit', null, serializedData, function(event) {				// on form submission
		// prevent form submission
		event.preventDefault();

		var request;

		if (request) { request.abort(); } // abort if request is already present
		// serialized data passed through function call
		request = $.ajax({
			url: "habit-complete.php",
			type: "post",
			data: serializedData
		})
		//
		.done(function(data, textStatus, jqueryXHR) {
			console.log('success!!');
			console.log(data);
		})

		.fail(function(jqueryXHR, textStatus, errorThrown) {
			console.log('fail!!');
			console.log(textStatus);
			console.log(jqueryXHR);
		})
//		jqXHR.always(function( data|jqXHR, textStatus, jqXHR|errorThrown ) { });
		.always(function(data_or_jqXHR, textStatus, jqXHR_or_errorThrown){
		});

	}); // end on submit

	setTimeout(function(){												// after interval-- submit form
		serializedData = $form.serialize();
		$form.submit();
		$form.hide();
		$restoreButton.show();
	}, 3600000);


}); // end jquery's on ready function

