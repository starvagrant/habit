<?php
require 'login.php';
$dsn = 'mysql:host=localhost;dbname=habit';

try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$habit = array();

function get_habit_urgency_css_classname($completion, $priority) {
	// no divide by zero
	if ($priority == 0) $priority++;

	// css class name for list item
	$urgency = $completion / $priority;
	if ($urgency > 0.85) $urgency = 0.85;
	if ($urgency > 0.70) {
		$css_class_name = "low";
	} else if ($urgency < 0.70 && $urgency > 0.40){
		$css_class_name = "normal";
	} else {
		$css_class_name = "high";
	}
	return $css_class_name;
}

function get_habit_date($leveled_up_date){
	$date_part = preg_split('/[:-\s]/', $leveled_up_date);
	$habit_date = $date_part[1] . "-" . $date_part[2] . " " . $date_part[0];
	return $habit_date;
}

function get_habit_score($completion, $priority, $habit_level) {
	if ($completion == 0) $completion++;
	$habit_score = 8 * ($priority / $completion) * ((128 - $habit_level) / 128);
	$habit_score = (int)$habit_score;
	return $habit_score;
}

function push_db_rows_to_global_habit_array($query){
	global $pdo;
	global $habit;
	$result_statement = $pdo->query($query);

	while($row = $result_statement->fetch(PDO::FETCH_ASSOC)){

		$completion = $row["completion"]; $priority = $row["priority"];
		$leveled_up_date = $row["leveled_up_date"]; $habit_level = $row["habit_level"];

		$row['urgency'] = get_habit_urgency_css_classname($completion, $priority);
		$row['leveled_date'] = get_habit_date($leveled_up_date);
		$row['score'] = get_habit_score($completion, $priority, $habit_level);

		$habit[] = $row;
	}
}

// select habits with four highest priority scores (highest priority)
// followed by highest completion and longest time since update

$sql_select1 = "SELECT h.habit_id, h.habit_name, h.priority, h.completion,
				s.habit_level, s.habit_experience, s.leveled_up_date
				FROM habit_tracker as h INNER JOIN habit_score as s ON h.habit_id=s.habit_id
				ORDER BY h.priority DESC LIMIT 4 ";
$sql_select2 = "SELECT h.habit_id, h.habit_name, h.priority, h.completion,
				s.habit_level, s.habit_experience, s.leveled_up_date
				FROM habit_tracker as h INNER JOIN habit_score as s ON h.habit_id=s.habit_id
				ORDER BY h.completion DESC LIMIT 3; ";
$sql_select3 = "SELECT h.habit_id, h.habit_name, h.priority, h.completion,
				s.habit_level, s.habit_experience, s.leveled_up_date
				FROM habit_tracker as h INNER JOIN habit_score as s ON h.habit_id=s.habit_id
				ORDER BY h.update_date ASC LIMIT 3; ";

try {

push_db_rows_to_global_habit_array($sql_select1);
push_db_rows_to_global_habit_array($sql_select2);
push_db_rows_to_global_habit_array($sql_select3);

}
catch (PDOException $e){
	error_log($e->getMessage());
}

function make_list_item($habit_array, $number){
// $habit is a value for form submission, $number is the numbered item
$list = <<<_LIST
	<li>
		<label class="{$habit_array["urgency"]}">
				<span class="start">{$habit_array["habit_name"]}:</span>

				<input type="hidden" name="habit$number" value = "{$habit_array["habit_id"]}" />
				<input type="checkbox" name="complete$number" value="1" />
				<input type="checkbox" name="priority$number" value="1" checked="checked" />
				<input type="hidden" name="score$number" value = "{$habit_array["score"]}" />
				<input type="hidden" name="date$number" value = "{$habit_array["leveled_up_date"]}" />
				<input type="hidden" name="exp$number" value = "{$habit_array["habit_experience"]}" />
				<input type="hidden" name="level$number" value = "{$habit_array["habit_level"]}" />

				<span class="level"> {$habit_array["score"]}</span>
				<span class="level">  {$habit_array["habit_experience"]} /128 </span>
				<span class="level">  {$habit_array["habit_level"]}</span>
				<span class="end level"> {$habit_array["leveled_date"]}</span>
		</label>

	</li>


_LIST;
echo $list;
}

function print_dump($var){
	echo "<pre>\n"; var_dump($var); echo "</pre>\n";
}
?>

<!DOCTYPE html>
<html lang="en-us">

<head>

<title>dynamichabits.php</title>
<link rel="stylesheet" href="css/habit-form.css" />
<meta charset="utf-8">

</head>

<body>
	<form id="habit-complete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<ul>
			<li> Habit, Complete, Priority </li>
			<?php for($i = 0; $i < 10; $i++) {make_list_item($habit[$i], $i); } ?>
		</ul>
	<button id="submit-button">Submit All</button>
	</form>
	<button id="restore-button">Restore Form</button>

<script src="jquery-1.11.0.js"></script>
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
		//
		.done(function(data, textStatus, jqueryXHR) {
			console.log('success!!');
		})

		.fail(function(jqueryXHR, textStatus, errorThrown) {
			console.log('fail!!');
//			console.log(textStatus);
		})
//		jqXHR.always(function( data|jqXHR, textStatus, jqXHR|errorThrown ) { });
		.always(function(data__jqXHR, textStatus, jqXHR__errorThrown){
			console.log('finished');
//			console.log(textStatus);
			console.log(data__jqXHR);
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
