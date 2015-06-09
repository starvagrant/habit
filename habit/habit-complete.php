<?php
if(isset($_POST)) {
require 'login.php';
print("ajax result is: "); 					// response sent upon ajax request

$dsn = 'mysql:host=localhost;dbname=habit';
try { $pdo = new PDO($dsn, $db_username, $db_password); } 
catch (PDOException $e) { $error = $e->getMessage(); die("$error"); }
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$keys_from_post = array_keys($_POST); 				// get the number of form names by array keys 
foreach($keys_from_post as $key => $value) { error_log($value); }
preg_match('/\d+/', $keys_from_post[0], $matches);
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

$sql_update = "UPDATE habit_tracker SET completion = completion + ? , priority = priority + ? WHERE habit_name=?; ";
if(!$statement = $pdo->prepare($sql_update)) print ("statement not prepared");
if(!$statement->execute(array($priority, $complete, $habit))) print(" statement not executed ");

} // end POST processing
?>
