<?php
$ajzx = "ajax: ";

function error_log_dump($var){
ob_start();
var_dump($var);
$error = ob_get_contents();
ob_get_clean();
error_log($error);
}

function is_variable_set($boolean){
	if (($boolean)) return 1;
	return 0;
}

function calculate_exp($score, $exp){
	if ($score == 0) $score++;
	$new_score = $score + $exp;
	$error_score = (string)$new_score;
	error_log($error_score);
	if ($new_score > 127) return 0;
	return (int)$new_score;
}

function calculate_level($level, $score){
	if ($score == 0) return $level + 1;
	return (int)$level;
}

function calculate_date($date, $score){
	if ($score == 0) return date('Y-m-d H:i:s');
	return $date;
}

if(isset($_POST)) {
require 'login.php';
$dsn = 'mysql:host=localhost;dbname=habit';
try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ajax = "ajax is: ";

// prepare statements
$sql_update_habits =	"UPDATE habit_tracker SET completion = completion + ? ,
						priority = priority + ? WHERE habit_id=?; ";
$sql_update_score =		"UPDATE habit_score SET habit_experience = ? ,
						habit_level = ?, leveled_up_date = ? WHERE habit_id=?; ";

$habit_statement = $pdo->prepare($sql_update_habits);
$score_statement = $pdo->prepare($sql_update_score);


$keys_from_post = array_keys($_POST); 				// get the number of form names by array keys
													// note, foreach goes through post by numeric keys
													// so every third key will be what we're looking for
$pdo->beginTransaction();

// assign post variables to arrays
foreach($keys_from_post as $key => $value) {

	// match the digits in the keys, % number is number of inputs per list item
	if ($key % 7 === 0){
		preg_match('/\d+/', $value, $matches);
		$number = $matches[0];

	$habit =(int)$_POST["habit$number"];
	$complete = is_variable_set(isset($_POST["complete$number"]));
	$priority = is_variable_set(isset($_POST["priority$number"]));

	$score = calculate_exp($_POST["score$number"], $_POST["exp$number"]);
	$new_level = calculate_level($_POST["level$number"], $score);
	$new_date = calculate_date($_POST["date$number"], $score);

	error_log($sql_update_score);
	error_log($score);
	error_log($new_level);
	error_log($new_date);
	error_log($habit);
	// habit, complete, priority assigned
	$sql_array_habit= array($complete, $priority, $habit);
	$sql_array_score = array($score, $new_level, $new_date, $habit);

	if($habit_statement->execute($sql_array_habit)) $ajax .= $number;
	if($score_statement->execute($sql_array_score));

	} // end if

} // end foreach

$pdo->commit();

print $ajax;
} // end POST processing
?>
