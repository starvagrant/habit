<?php
require 'login.php';

$db_database="habit";
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        if ($connection->connect_error) die ($connection->connect_error);
$form_increment = 0;

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
		return "SELECT `habit_name`, `priority`, `completion` 
			FROM habit_tracker ORDER BY priority DESC LIMIT $number;";
	case 'habits seeking commitment':
		return "SELECT `habit_name`, `priority`, `completion` 
			FROM habit_tracker ORDER BY completion, priority DESC LIMIT $number;";
	case 'reinforcing old habits':
		return "SELECT `habit_name`, `priority`, `completion` 
			FROM habit_tracker ORDER BY completion DESC LIMIT $number;";
	default: 
		return "SELECT NOW();";
	} 
}

function make_form_item($form_label, $form_item_name, $form_item_type, $form_item_value){
	$form_list_item = <<<_FLI
	<label>$form_label <input type="$form_item_type" name="$form_item_name" value="$form_item_value" /></label>

_FLI;
	return $form_list_item;
}
function print_habit_list_items($string_to_sql, $number){
	$query = query_habit_database($string_to_sql, $number);
	global $connection; // connection object
	global $form_increment;
	$result = $connection->query($query);
	if (!$result) die ($string_to_sql . "not valid");
	$rows = $result->num_rows;
	for ($i = 0; $i < $rows; $i++){
		$result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$habit_item = make_form_item("Habit", "habit$form_increment", "text", $row['habit_name']);
		$complete_item = make_form_item("Complete?", "complete$form_increment", "checkbox", "completed");
		$priority_item = make_form_item("Priority?", "priority$form_increment", "checkbox", "priority");
		$score = $row["priority"] * $row["completion"];
		$score_item = make_form_item("", "score$i", "hidden", "$score");
		print ("<li>$habit_item $complete_item $priority_item $score_item</li>");

		$form_increment++;
	} // end for
} // end function

if ($_POST){
	$i = 0;
//	$statement_check = "statements prepared:";
/*
	$sql_update = "UPDATE habit_tracker SET completion = ?; ";
	$statement = $connection->prepare($sql_update);
	$statement->bind_param("d", $debug);
	$debug = 42;
	$statement->execute();	
*/
	$sql_update = "UPDATE habit_tracker SET completion = completion + ? , priority = priority + ? WHERE habit_name = ?;";
	$statement = $connection->prepare($sql_update); 
	$statement->bind_param("dds", $completed, $priority, $habit_name);
	while (isset( $_POST["habit$i"] )) {			// go through POST's habit fields
		if (isset( $_POST["complete$i"] )){		// complete checkbox checked 
			$completed = 1;
		} else {
			$completed = -1;
		}
		if (isset( $_POST["priority$i"] )){		// priority checkbox checked 
			$priority =  1;
		} else {
			$priority = -1;
		}
		$habit_name = $_POST["habit$i"];
		
		$statement->execute();
		$i++;
	} // end while
} // end if($_POST) 
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

<form action="habit-form.php" method="post">
	<ul>
		<?php print_habit_list_items('highest priority habit changes', 3) ?>
		<?php print_habit_list_items('habits seeking commitment', 3) ?>
		<?php print_habit_list_items('reinforcing old habits', 2) ?>
	</ul>
	<input type="submit" value="submit" />
	
</form>
<!--
	<p> <?php print_dump($statement); ?> </p>	
	<p> <?php echo $statement_check; ?> </p>
-->
</body>
</html>
