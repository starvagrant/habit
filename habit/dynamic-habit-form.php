<?php
require 'login.php';
$dsn = 'mysql:host=localhost;dbname=habit';

try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }

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
	<?php for($i = 0; $i < 10; $i++) { make_list_item($habit[$i], $i); }?>
	</form>
<script>
$( document ).ready( function(){
	console.log('yoyoyo');
	var serializedData;
	var $form = $('form#habit-complete');

	$form.on('submit', null, serializedData, function(event) {
		event.preventDefault() ;

		// prevent form submission
		console.log("begin ajax");

		var request;
	
		if (request) { request.abort(); } // abort if request is already present 	

		// serialized data passed through function call
		request = $.ajax({
			url: "habit-complete.php",
			type: "post",
			data: serializedData
		});

		// curious as to what these are	
		request.done(function(textStatus, response, jqueryXHR) {
//			console.log(jqueryXHR);
			console.log(textStatus);
		});

		request.fail(function(jqueryXHR, response, textStatus) {
			console.log(textStatus);
//			console.log(jqueryXHR);
		});

		console.log("end ajax");
	}); // end on submit

	$checkboxes = $('input[type=checkbox]');
	$checkboxes.on('change', function(e){ 
		console.log('yoyoyo');
		// put data in global serialized string $form.submit will use
		serializedData = $(this).parent().children().serialize( "input" );
		console.log(serializedData);
//		$('form#habit-complete').trigger("submit");
//		$form.trigger("submit");	
		$form.submit();	
		$(this).parent().parent().hide(); 
	}); // end onclick
}); // end jquery's on ready function
</script>
</body>
</html>
