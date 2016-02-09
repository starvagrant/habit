var $form = $('form');
	$form.on('submit', function(e){
		e.preventDefault();
	});

	var $tableRows = $('table tr');

	$tableRows.on('click', 'button', function(){
//		alert('table button clicked'); 
		var $input = $(this).parent()[0].nextElementSibling;
		console.log($input.value);
	});
