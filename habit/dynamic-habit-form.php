<?php
require 'login.php';
$dsn = 'mysql:host=localhost;dbname=habit';

try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// select habits with four highest priority scores (highest priority)
$sql_select1 = "SELECT habit_name from habit_tracker ORDER BY priority DESC LIMIT 4 ; ";
$result_statement = $pdo->query($sql_select1);

while($row = $result_statement->fetch(PDO::FETCH_ASSOC)){
	$habit[] = $row["habit_name"];
}

// select habits with four highest completion scores (keep old habits strong)
$sql_select2 = "SELECT habit_name from habit_tracker ORDER BY completion DESC LIMIT 3; ";
$result_statement = $pdo->query($sql_select2);

while($row = $result_statement->fetch(PDO::FETCH_ASSOC)){
	$habit[] = $row["habit_name"];
}

// select habits with four highest completion scores (put a spotlight on unfinished habits)
$sql_select3 = "SELECT habit_name from habit_tracker ORDER BY update_date ASC LIMIT 3; ";
$result_statement = $pdo->query($sql_select3);

while($row = $result_statement->fetch(PDO::FETCH_ASSOC)){
	$habit[] = $row["habit_name"];
}


/* shuffle the array values so that the ten habits come out prioritized */
// three types of habit: 1) highest priority, 2) highest completion, 3) something in the middle
// 1) focuses habits
// 2) ingrains habits
// 3) focuses on newly forming habits


function make_list_item($habit, $number){
// $habit is a value for form submission, $number is the numbered item
$list = <<<_LIST
	<li>
		<label>Habit:<input type="text" name="habit$number" value = "$habit" />
			<input type="checkbox" name="complete$number" value="1" />
			<input type="checkbox" name="priority$number" value="1" checked="checked" />
		</label>
	</li>

_LIST;
echo $list;
}

?>

<!DOCTYPE html>
<html lang="en-us">

<head>

<title>dynamichabits.php</title>
<link rel="stylesheet" href="habit-form.css" />
<script src="jquery-1.11.0.js"></script>
<meta charset="utf-8">

</head>

<body>
	<form id="habit-complete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<ul>
			<li> Habit, Complete, Priority </li>
			<?php for($i = 0; $i < 10; $i++) { make_list_item($habit[$i], $i); }?>
		</ul>
	<button id="submit-button">Submit All</button>
	</form>
	<button id="restore-button">Restore Form</button>
<script>
$( document ).ready( function(){
	console.log('yoyoyo');
	var serializedData;
	var $form = $('form#habit-complete');
	var $submitButton = $('button#submit-button');
	var $restoreButton = $('button#restore-button');
	var $checkboxes = $('input[type=checkbox]');

//////////////////////////////////////////////////////////////// UI events
	$restoreButton.hide();
	console.log($checkboxes);

	$checkboxes.on('change', function(event){
		console.log('yoyoyo');
		// put data in global serialized string $form.submit will use
		serializedData = $(this).parent().children().serialize( "input" );
		console.log(serializedData);

		$form.submit();
		// reset checkboxes to original state (checked attribute)
		if ($(this).attr('checked')) $(this).prop('checked', true);
		if (!$(this).attr('checked')) $(this).prop('checked', false);
		// the document's html: li > label > input, li needs to be hideen
		$(this).parent().parent().hide();
	});

	$submitButton.on('click', function(event){
		event.preventDefault();
		serializedData = $form.serialize();
		console.log(serializedData);
		$form.submit();
		$form.hide();
		$(this).show();
		$restoreButton.show();
	});

	$restoreButton.on('click', function(event){
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
	$form.on('submit', null, serializedData, function(event) {
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
//		 jqXHR.done(function( data, textStatus, jqXHR ) {});
		//
		.done(function(data, textStatus, jqueryXHR) {
			console.log('success!!');
//			console.log(data);
		})

//		jqXHR.fail(function( jqXHR, textStatus, errorThrown ) {})
		.fail(function(jqueryXHR, textStatus, errorThrown) {
			console.log('fail!!');
//			console.log(textStatus);
		})
//		jqXHR.always(function( data|jqXHR, textStatus, jqXHR|errorThrown ) { });
		.always(function(data__jqXHR, textStatus, jqXHR__errorThrown){
			console.log('finished');
//			console.log(textStatus);
//			console.log(data__jqXHR);
//			console.log(jqXHR__errorThrown);
//			console.log('finished');
		});

	}); // end on submit

	setTimeout(function(){			// automatic form submission, after interval
		serializedData = $form.serialize();
		$form.submit();
		$form.hide();
		$restoreButton.show();
	}, 3600000);


}); // end jquery's on ready function

</script>
</body>
</html>
