<?php
if(isset($_POST)) {
require 'login.php';
$ajax = "ajax result is:\n"; 					// response sent upon ajax request

$dsn = 'mysql:host=localhost;dbname=habit';
try { $pdo = new PDO($dsn, $db_username, $db_password); }
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ajax .= "database connected\n";

$keys_from_post = array_keys($_POST); 				// get the number of form names by array keys
													// note, foreach goes through post by numeric keys
													// so every third key will be what we're looking for
foreach($keys_from_post as $key => $value) {

	// match the digits in the keys
	if ($key % 3 === 0){
		preg_match('/\d+/', $value, $matches);
		$number = $matches[0];
	$habit = $_POST["habit$number"];
	if (isset($_POST["complete$number"])){
		$complete = 1;			 			// by convention: 1=true, 0=false
	} else {
		$complete = 0;
	}
	if (isset($_POST["priority$number"])){
		$priority = 1;
	} else {
		$priority = 0;
	}
	// habit, complete, priority assigned
	$sql_array[] = array($complete, $priority, $habit);

	} // end if

} // end foreach (sql_array populated)

$sql_update = "UPDATE habit_tracker SET completion = completion + ? , priority = priority + ? WHERE habit_name=?; ";
if( !$statement = $pdo->prepare($sql_update) ) print ($ajax . " statement not prepared");

// $ajax = $statement->queryString;

foreach ($sql_array as $key => $array){
		$ajax .= "\n" . $key . " is the outer key";
	foreach($array as $check_key => $checks){
		$ajax .= "\n" . $check_key . " is the inner key";
		$ajax .= "\n" . $checks . " is the value";
	}
	if( !$statement->execute($array) )  $ajax .= "\n" . $statement->errorCode() . " execute problem" ;
}

if ($pdo->errorCode !== null) $ajax .= "\n" . $pdo->errorCode() . " db handle problem";
echo $ajax;

} // end POST processing
?>
