<?php
require 'login.php';

$dsn = 'mysql:host=localhost;dbname=habit';
$pdo = new PDO($dsn, $db_username, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql_select = "SELECT habit_name from habit_tracker LIMIT 10; ";

$result_statement = $pdo->query($sql_select);
while($row = $result_statement->fetch()){
	$habit[] = $row[0];			// add all habits to an array
}


function make_list_item($habit, $number){
$list = <<<_LIST
	<li>
		<label>Habit:<input type="text" name="habit$number" value = "$habit" />
			<input type="checkbox" name="complete$number" value="completed" />
			<input type="checkbox" name="priority$number" value="priority" checked="checked" />
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
	<?php for($i = 0; $i < 10; $i++) { make_list_item($habit[$i], $i); }?>
	</form>
<script>
$( document ).ready( function(){
	var serializedData;
	var $form = $('form#habit-complete');

	$form.on('submit', null, serializedData, function(event) {
		event.preventDefault() ;

		// prevent form submission
		console.log("begin ajax");
		console.log(serializedData);
		console.log(event);
		console.log(this);
		console.log($(this));

		var request;
	
		if (request) { request.abort(); } // abort if request is already present 	

		// serialized data passed through function call
		request = $.ajax({
			url: "habit-complete.php",
			type: "post",
			data: serializedData
		});

		// curious as to what these are	
		request.done(function(response, textStatus, jqueryXHR) {
			console.log(response);
			console.log(textStatus);
		});

		request.fail(function(response, textStatus, jqueryXHR) {
			console.log(textStatus);
		});

		console.log("end ajax");
	}); // end on submit

	$checkboxes = $('input[type=checkbox]');
	$checkboxes.on('change', function(e){ 
		console.log('yoyoyo');
		$(this).parent().parent().hide(); 
		// put data in global serialized string $form.submit will use
		serializedData = $(this).siblings().serialize( "input" );
		console.log(serializedData);
//		$('form#habit-complete').trigger("submit");
//		$form.trigger("submit");	
		$form.submit();	
	}); // end onclick
}); // end jquery's on ready function
</script>
</body>
</html>
