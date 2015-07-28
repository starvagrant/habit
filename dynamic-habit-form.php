<?php
require 'login.php';
require 'ArraySorterClass.php';

$dsn = 'mysql:host=localhost;dbname=habit';
$Test = new TestClass("quiet");

try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$habit = array();

/////////////////////////////////////////////////////////////////////////// Error Functions

function print_dump($var){
	echo "<pre>\n"; var_dump($var); echo "</pre>\n";
}

/////////////////////////////////////////////////////////////////////////// Value Calculation

function get_habit_urgency_css_classname($completion, $priority) {
	// no divide by zero
	if ($priority == 0) $priority++;

	// css class name for list item
	$urgency = get_urgency($completion, $priority);
	$test_case = true;
	switch ($test_case){  
	case $urgency >= 0.60: 
		$css_class_name = "established";
		break;
	case $urgency > 0.50:
		$css_class_name = "relaxed";
		break;
	case $urgency > 0.35:
		$css_class_name = "moderate";
		break;
	case $urgency > 0.25:
		$css_class_name = "heigthened";
	default:
		$css_class_name = "severe";
		break;
	}
	return $css_class_name;
}

function get_habit_score($completion, $priority, $habit_level) {
	if ($completion == 0) $completion++;
	$habit_score = 8 * ($priority / $completion) * ((128 - $habit_level) / 128);
	$habit_score = (int)$habit_score;
	return $habit_score;
}

function get_urgency($completion, $priority) {
	if ($priority == 0) $priority++;
	$urgency = $completion / $priority;
	return $urgency;
}

function get_experience_for_display($experience){
	if ($experience <= 0) return 0;
	return $experience % 128;
}

function get_date_for_display($date){
	// 2000-01-01 13:13:13
	$date_without_time = substr($date, 0, 10);
	$date_fragments = explode('-', $date_without_time);
	$date = $date_fragments[1] . '/' . $date_fragments[2] . ' ' . $date_fragments[0];
	return $date;
}

/////////////////////////////////////////////////////////////////////////// Data Function

function push_db_rows_to_global_habit_array($query){
	global $pdo;
	global $habit;
	$result_statement = $pdo->query($query);

	while($row = $result_statement->fetch(PDO::FETCH_ASSOC)){

		$completion = $row["completion"]; $priority = $row["priority"];
		$leveled_up_date = $row["leveled_up_date"]; $habit_level = $row["habit_level"];
		$habit_experience = $row['habit_experience'];

		$row['urgency_class'] = get_habit_urgency_css_classname($completion, $priority);
		$row['urgency'] = get_urgency($completion, $priority);
		$row['score'] = get_habit_score($completion, $priority, $habit_level);
		$row['display_experience'] = get_experience_for_display($habit_experience);
		$row['display_date'] = get_date_for_display($leveled_up_date);

		$habit[] = $row;
	}
}
/////////////////////////////////////////////////////////////////////////// Make HTML
$increment = 0; 
function make_list_item($habit_array){
// note: each time inputs change, I need to change the % test in habit-complete.php
	global $increment;
	$list = <<<_LIST
	<li>
		<label class="{$habit_array["urgency_class"]}">
				<span class="start">{$habit_array["habit_name"]}:</span>

				<input type="checkbox" name="complete$increment" value="1" />
				<input type="checkbox" name="priority$increment" value="1" checked="checked" />
				<input type="hidden" name="score$increment" value = "{$habit_array["score"]}" />
				<input type="hidden" name="habit_id$increment" value = "{$habit_array["habit_id"]}" />
				<input type="hidden" name="habit_name$increment" value = "{$habit_array["habit_name"]}" />
				<input type="hidden" name="date$increment" value = "{$habit_array["leveled_up_date"]}" />
				<input type="hidden" name="experience$increment" value = "{$habit_array["habit_experience"]}" />
				<input type="hidden" name="level$increment" value = "{$habit_array["habit_level"]}" />
				<input type="hidden" name="urgency$increment" value = "{$habit_array["urgency"]}" />

				<span class="level"> {$habit_array["score"]}</span>
				<span class="level">  {$habit_array["display_experience"]} /128 </span>
				<span class="level">  {$habit_array["habit_level"]}</span>
				<span class="end level"> {$habit_array["display_date"]}</span>
		</label>

	</li>
_LIST;
	echo $list;
	$increment++;
}

////////////////////////////////////////////////////////////////////////// End Functions



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
	die ('uncaught exception');
}

$sql_distinct_sorter = new ArraySorter('habit_id');

// test parameters
$evaluated = 0; $pushed = 0;
foreach ($habit as $habit_keys => $habit_row){
	$evaluated++; 
	if($sql_distinct_sorter->push_if_unique_array($habit_row)) {
		$pushed++;
	}
	// values that indicate not enough pushed
	if ($evaluated === 4 && $pushed < 4) {
		$too_little_pushed = true;
		$message = "not enough, first query";
	} else if ($evaluated === 7 && $pushed < 4){
	   	$too_little_pushed = true;
		$message = "not enough, second query";
	} else if ($evaluated === 10 && $pushed < 6) {
		$too_little_pushed = true;
		$message = "not enough, third query";
	} else {
		$too_little_pushed = false;
		$message = "nothing wrong";
	}
/*
	$Test->setErrorMessage($message);
	$Test->testTrue(!$too_little_pushed);
*/
	$filtered_array = $sql_distinct_sorter->getArray();

}

?>

<!DOCTYPE html>
<html lang="en-US">
<head>

<title>dynamichabits.php</title>
<link rel="stylesheet" href="css/habit-form.css" />
<meta charset="utf-8">

</head>

<body>
	<form id="habit-complete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<ul id="habit-list">
			<li> Habit, Complete, Priority </li>
			<?php foreach ($filtered_array as $key => $filtered_items) { make_list_item($filtered_items); } ?>
		</ul>
	<button id="submit-button">Submit All</button>
	</form>
		<ul id="feedback-list">
			<li>FeedBack List</li>
		</ul>
	<button id="restore-button">Restore Form</button>

<script src="jquery-1.11.0.js"></script>
<script>
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
	//
	$( document ).ajaxSuccess(function (event, xhr, settings){
		var habitInfoObject = JSON.parse(xhr.responseText);

		var listItem = '<li class="response-text">' + xhr.responseText + '</li>';
		var truthItem = '<li><h5>' + habitInfoObject.habit_leveled + ' ';
		truthItem += '';
		truthItem += '';
		truthItem += '';
		truthItem += '</h5></li>';
//		console.dir(habitInfoObject);
		if (habitInfoObject.habit_leveled === "true") {
			$feedBackList.append(truthItem);
		} else {
			$feedBackList.append(truthItem);
		}

		$feedBackList.append(listItem);	
	});	
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
		.always(function(data_or_jqXHR, textStatus, jqXHR_or_errorThrown){
			if (typeof data_or_jqXHR === "string"){
				var data = data_or_jqXHR;
				var success = true;
			} else {
				var jqXHR = data_or_jqXHR;
				var success = false;
			}
			if (typeof jqXHR_or_errorThrown === "string"){
				var errorThrown = jqXHR_or_errorThrown;
			} else {
				var jqXHR = jqXHR_or_errorThrown;
			}

			if (data !== undefined){
				console.log(data);
//				var responseObject = JSON.parse(data);
//				console.dir(responseObject);
			}
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

<?php 
///////////////////////////////////////////////////////////////////////////// Assertions

?>
</body>
</html>
