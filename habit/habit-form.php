<?php
require 'login.php';

$db_database="habit";
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        if ($connection->connect_error) die ($connection->connect_error);

function print_dump($var)
{
	echo "<pre>\n";
	var_dump($var);
	echo "</pre>\n";
}

function query_habit_database($string_to_sql, $number) {
	global $connection; // connection object
	switch($string_to_sql) {
	case 'highest priority habit changes':
		return "SELECT * FROM habit_tracker LIMIT $number;";
	case 'habits seeking commitment':
		return "SELECT * FROM habit_tracker LIMIT $number;";
	case 'reinforcing old habits':
		return "SELECT * FROM habit_tracker LIMIT $number;";
	default: 
		return "SELECT NOW();";
	} 
}

function print_habit_list_items($string_to_sql, $number)
{
	$query = query_habit_database($string_to_sql, $number);
	global $connection; // connection object
	$result = $connection->query($query);
	if (!$result) die ($string_to_sql . "not valid");
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$list_item = "<li> {$row["habit_name"]} </li>";
		echo $list_item;
	} // end for
} // end function
?>

<!DOCTYPE html>
<html lang="en-us">

<head>
	<meta charset="utf-8">
<title></title>
<style>
</style>
</head>

<body>
	<ul>
		<? print_habit_list_items('highest priority habit changes', 3) ?>
		<? print_habit_list_items('habits seeking commitment', 3) ?>
		<? print_habit_list_items('reinforcing old habits', 2) ?>
	</ul>
</body>
</html>
