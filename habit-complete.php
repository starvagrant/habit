<?php

function error_dump($var){
ob_start();
var_dump($var);
$error = ob_get_contents();
ob_get_clean();
error_log($error);
}

function error_print($var){
ob_start();
print_r($var);
$error = ob_get_contents();
ob_get_clean();
error_log($error);
}

// for assigning zeroes or ones to set / unset post variables
function is_variable_set($boolean){
	if (($boolean)) return 1;
	return 0;
}
function calculate_experience($score, $experience){
	$new_experience = $score + $experience;
	return $new_experience;
}
function calculate_level($experience){
	if ($experience < 128) return 1;
	// when score is divisible by 128
	if ($experience % 128 === 0) return (int)($experience / 128 + 1);
	return (int)($experience / 128);
}
function calculate_date($date, $score){
	if ($score == 0) return date('Y-m-d H:i:s');
	return $date;
}
function did_habit_level($old_level, $new_level){
	if ($new_level > $old_level) return 1;
	return 0;
}

function post_filter($string){
    strip_tags($string);
    htmlentities($string, ENT_NOQUOTES, "UTF-8", false);
}

if(isset($_POST)) {

require 'login.php';
$dsn = 'mysql:host=localhost;dbname=habit';
try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// prepare statements
$sql_update_habits =	"UPDATE habit_tracker SET completion = completion + ? ,
			priority = priority + ? WHERE habit_id=?; ";
$sql_update_score =	"UPDATE habit_score SET habit_experience = ? ,
			habit_level = ?, leveled_up_date = ? WHERE habit_id=?; ";

$sql_insert_history =	"INSERT INTO habits_over_time VALUES (?, ?, ?, ?, ?, ?, ?, ?) ; ";

$habit_statement = $pdo->prepare($sql_update_habits);
$score_statement = $pdo->prepare($sql_update_score);
$history_statement = $pdo->prepare($sql_insert_history);

$pdo->beginTransaction();

$keys_from_post = array_keys($_POST); 				// get the number of form names by array keys
													// note, foreach goes through post by numeric keys
													// so every nth key will be what we're looking for

// assign post variables to arrays
foreach($keys_from_post as $key => $value) {

	// match the digits in the keys, % number is number of inputs per list item
	if ($key % 8 === 0){
		preg_match('/\d+/', $value, $matches);
		$increment = $matches[0];

	// get post variables
	$habit_id =(int)$_POST["habit_id$increment"];
	$habit_name = $_POST["habit_name$increment"];
	$score = $_POST["score$increment"];
	$urgency = $_POST["urgency$increment"];
	$experience =  $_POST["experience$increment"];
	$old_date = $_POST["date$increment"];

	$complete = is_variable_set(isset($_POST["complete$increment"]));
	$priority = is_variable_set(isset($_POST["priority$increment"]));

	$old_experience = calculate_experience(0, $experience);
	$new_experience = calculate_experience($score, $experience);

	$old_level = calculate_level($old_experience);
	$new_level = calculate_level($new_experience);
	$habit_leveled_up = did_habit_level($old_level, $new_level);

	$current_date = date('Y-m-d H:i:s');

	// recording date after leveling
	// $new_date for SQL, $habit_level_string for JSON
	if ($habit_leveled_up === 1){
		$new_date = $current_date;
		$habit_level_string = "true";
	} else if ($habit_leveled_up === 0){
		$new_date = $old_date;
		$habit_level_string = "false";
	}

	// habit, complete, priority assigned
	$sql_array_habit= array($complete, $priority, $habit_id);
	$sql_array_score = array($new_experience, $new_level, $new_date, $habit_id);

	// 		habit_id, completed, priority, urgency, time_of_entry
	// 		habit_level, habit_experience, leveled_up_date
	$sql_array_history = array(
			$habit_id, $complete, $priority, $urgency, $current_date,
			$new_level, $score,  $new_date
			);

	$habit_statement->execute($sql_array_habit);
	$score_statement->execute($sql_array_score);
	$history_statement->execute($sql_array_history);

	$ajax_data = <<<_AJAX
{
"habit_id": "$habit_id",
"habit_name": "$habit_name",
"complete": "$complete",
"urgency": "$urgency",
"priority": "$priority",
"score": "$score",
"new_level": "$new_level",
"new_date": "$habit_leveled_up",
"habit_leveled": "$habit_level_string"
}
_AJAX;


} // end if

} // end foreach

$pdo->commit();

print $ajax_data;
} // end POST processing
?>
