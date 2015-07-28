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

function is_variable_set($boolean){
	if (($boolean)) return 1;
	return 0;
}

function calculate_exp($score, $exp){
	$new_score = $score + $exp;
	return $new_score;
}

function calculate_level($score){
	if ($score < 128) return 1;
	// when score is divisible by 128
	if ($score % 128 === 0) return ($score / 128 + 1);
	return ($score / 128);
}

function calculate_date($date, $score){
	if ($score == 0) return date('Y-m-d H:i:s');
	return $date;
}
function did_habit_level($score, $new_score){
	// starting experience
	if ($score == 0) $level = 1;
	// no points gained
	if ($score != 0 && $score / $level == 1) return "false";

	$start_level = calculate_level($score);	
	$end_level = calculate_level($score);	
	if ($start_level - $end_level === 0) return "false";
	return "true";
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
$sql_update_score =		"UPDATE habit_score SET habit_experience = ? ,
						habit_level = ?, leveled_up_date = ? WHERE habit_id=?; ";

$sql_insert_history =	"INSERT INTO habits_over_time VALUES (?, ?, ?, ?, ?, ?, ?, ?) ; "; 

$habit_statement = $pdo->prepare($sql_update_habits);
$score_statement = $pdo->prepare($sql_update_score);
$history_statement = $pdo->prepare($sql_insert_history);


$keys_from_post = array_keys($_POST); 				// get the number of form names by array keys
													// note, foreach goes through post by numeric keys
													// so every third key will be what we're looking for
//error_print($keys_from_post);
$pdo->beginTransaction();

// assign post variables to arrays
foreach($keys_from_post as $key => $value) {

	// match the digits in the keys, % number is number of inputs per list item
	if ($key % 8 === 0){
		preg_match('/\d+/', $value, $matches);
		$number = $matches[0];

	$habit_id =(int)$_POST["habit_id$number"];
	$habit_name = $_POST["habit_name$number"];
	$complete = is_variable_set(isset($_POST["complete$number"]));
	$priority = is_variable_set(isset($_POST["priority$number"]));
	$urgency = $_POST["urgency$number"];

	$score = $_POST["score$number"];
	$new_score = calculate_exp($_POST["score$number"], $_POST["exp$number"]);
	$new_level = calculate_level($_POST["level$number"], $score);
	$new_date = calculate_date($_POST["date$number"], $score);
	$habit_leveled_up = did_habit_level($new_level, $score);
	$current_time = date('Y-m-d H:i:s');	

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
"habit_leveled": "$habit_leveled_up"
}
_AJAX;

	// habit, complete, priority assigned
	$sql_array_habit= array($complete, $priority, $habit_id);
	$sql_array_score = array($score, $new_level, $new_date, $habit_id);

	// variables: 
	// habit_id, completed, priority, urgency, time_of_entry
	// habit_level, habit_experience, leveled_up_date 
	
	
	$sql_array_history = array($habit_id, $complete, $priority, $urgency, $current_time, 
		$new_level, $score,  $new_date );

	$habit_statement->execute($sql_array_habit);
	$score_statement->execute($sql_array_score);
	$history_statement->execute($sql_array_history);

} // end if 

} // end foreach

$pdo->commit();
// error_log(str_replace( "\n", " ", $ajax_data));
print $ajax_data;
} // end POST processing
?>
