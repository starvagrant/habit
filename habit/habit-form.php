<?php
require 'login.php';

$db_database="habit";
$c = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        if ($c->connect_error) die ($c->connect_error);
if ($_POST)
{	
/*	PUT HABITS FROM DB INTO ARRAY NAMED '$habits'			*/

	$sql_habit_name = "SELECT `habit_name` from tracker";
	$r_name = $c->query($sql_habit_name);
	$rows = $r_name->num_rows;
	for ($i = 0; $i < $rows; $i++)
	{
		$r_name->data_seek($i);
		$habits_row = $r_name->fetch_assoc();
		$habit_multi_array[] = $habits_row;
		$habits[] = $habits_row['habit_name'];
	} // end for
	$r_name->close();

/*	LOOP THROUGH THE 10 POST ITEMS					*/

	for ($i = 0; $i < 10; $i++)
	{
		$habit_item[$i] = strtolower(get_sanit_post($c, "habit$i"));

		if (isset($_POST["complete$i"]))
		{
			$complete_item[$i] = get_sanit_post($c, "complete$i");
		} else {							 // complete not checked
			$complete_item[$i] = false;
		} // end if	

		if (isset($_POST["priority$i"]))
		{
			$priority_item[$i] = get_sanit_post($c, "priority$i");
		} else { 							// priority not checked
			$priority_item[$i] = false;
		} // end if	

/* 	True or False? Is it in the database already? */
		
		$update_item[$i] = in_array($habit_item[$i], $habits); 
	} // end for

	for ($i = 0; $i < 10; $i++)
	{
		if($update_item[$i])
		{
			if ($complete_item[$i] && $priority_item[$i]) 		// if complete/prioritized
			{
			$sql = <<<_SQL
				UPDATE tracker SET priority=priority+1, completion=completion+1
				WHERE habit_name="{$habit_item[$i]}";
_SQL;
			} else if ($complete_item[$i]) {			// if complete 
				$sql = <<<_SQL
					UPDATE tracker SET priority=priority-1, completion=completion+1
					WHERE habit_name="{$habit_item[$i]}";
_SQL;
			} else if ($priority_item[$i]) {			// if prioritized
				$sql= <<<_SQL
					UPDATE tracker SET priority=priority+1, completion=completion-1 
					WHERE habit_name="{$habit_item[$i]}";
_SQL;
			} else { 						// item is not prioritized/completed
				$sql = <<<_SQL
					UPDATE tracker SET priority=priority-1, completion=completion-1 
					WHERE habit_name="{$habit_item[$i]}";
_SQL;
			} // end inner if
		} else {							// if update item is false
			
			if ($complete_item[$i] && $priority_item[$i]) 		// if complete/prioritized
			{
				$sql = <<<_SQL
					INSERT INTO tracker (habit_name, insert_date, priority, completion)
					VALUES ("{$habit_item[$i]}", now(), 10, 1); 
_SQL;
			} else if ($complete_item[$i]) {			// if complete 
				$sql = <<<_SQL
					INSERT INTO tracker (habit_name, insert_date, priority, completion)
					VALUES ("{$habit_item[$i]}", now(), 1, 1); 
_SQL;
			} else if ($priority_item[$i]) {			// if prioritized
				$sql = <<<_SQL
					INSERT INTO tracker (habit_name, insert_date, priority, completion)
					VALUES ("{$habit_item[$i]}", now(), 10, 0); 

_SQL;
			} else { 						// item is not prioritized/completed
				$sql = <<<_SQL
					INSERT INTO tracker (habit_name, insert_date, priority, completion)
					VALUES ("{$habit_item[$i]}", now(), 1, 0); 

_SQL;
			} // end inner if
		} // end if

		$r = $c->query($sql);
		try 
		{
			$sql_array[] = $sql;
			if (!$r) throw New Exception('data query failed');
		}
		catch (Exception $e)
		{
			$query_error[$i] = $e->getMessage();
			$query_sql[$i] = $sql;
		} // end try / catch
	} // end for
} // end if	

	$sql_sel1 = "SELECT `habit_name`, `priority`, `completion` from tracker ORDER BY priority DESC LIMIT 2;";		       
	$sql_sel2 = "SELECT `habit_name`, `priority`, `completion` from tracker ORDER BY completion, priority DESC LIMIT 6;";
	$sql_sel3 = "SELECT `habit_name`, `priority`, `completion` from tracker ORDER BY completion DESC LIMIT 2;";		       
	$r1 = $c->query($sql_sel1);
	$r2 = $c->query($sql_sel2);
	$r3 = $c->query($sql_sel3);
	try 
	{
		if (!$r1) throw new Exception('no result 1');
		if (!$r2) throw new Exception('no result 2');
		if (!$r3) throw new Exception('no result 3');
	}
	catch (Exception $e)
	{
		$error = $e->getMessage();
	}	


echo <<<_DOCUMENT
<!DOCTYPE html>
<html>
  <head>
    <title>habits.php</title>
<!--    <link rel="stylesheet" href="habit-form.css" /> -->
  </head>
  <body>
    <div id="page">
      <h1 id="header">List</h1>
      <h2>Learn Habits</h2>
      <form action='habit-form.php' method="post"> 
		<ul id="completion">
<!-- putting top four habit items up front -->
_DOCUMENT;

print_dump($sql_array);
print_dump($query_error);
print_dump($query_sql);
//print_dump($habit_item);
//print_dump($complete_item);
//print_dump($priority_item);
//print_dump($habits);
print_dump($habit_multi_array);
print_dump($update_item);

	if ($error) echo "<li>$error</li>";

	$form_item = 0;
	$rows1 = $r1->num_rows; 
	for ($i = 0; $i < $rows1; $i++)
	{
		$r1->data_seek($i);
		$list1[$i] = $r1->fetch_array(MYSQLI_ASSOC);
		$score = $list1[$i]['priority'] * $list1[$i]['completion'];
		echo <<<_LIST_ITEMS

		<li>
			<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list1[$i]['habit_name']}"/></label>
			<label>Complete?<input type="checkbox" name="complete$form_item" value="completed" class="default" /></label>
			<label>More Priority?<input type="checkbox" name="priority$form_item" value="priority" class="default" /></label>
			<label><input type="hidden" name="score$form_item" class="default" value="$score"/></label>
		</li>
_LIST_ITEMS;
		$form_item++;
	} // end for
	$r1->close();

	$rows2 = $r2->num_rows; 
	for ($i = 0; $i < $rows2; $i++)
	{
		$r2->data_seek($i);
		$list2[$i] = $r2->fetch_array(MYSQLI_ASSOC);
		$score = $list2[$i]['priority'] * $list2[$i]['completion'];
		echo <<<_LIST_ITEMS

		<li>
			<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list2[$i]['habit_name']}"/></label>
			<label>Complete?<input type="checkbox" name="complete$form_item" value="completed" class="default" /></label>
			<label>More Priority?<input type="checkbox" name="priority$form_item" value="priority" class="default" /></label>
			<label><input type="hidden" name="score$form_item" value="$score" class="default" /></label>
		</li>
_LIST_ITEMS;
		$form_item++;
	} // end for
	$r2->close();

	$rows3 = $r3->num_rows; 
	for ($i = 0; $i < $rows3; $i++)
	{
		$r3->data_seek($i);
		$list3[$i] = $r3->fetch_array(MYSQLI_ASSOC);
		$score = $list3[$i]['priority'] * $list3[$i]['completion'];
		echo <<<_LIST_ITEMS

		<li>
			<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list3[$i]['habit_name']}"/></label>
			<label>Complete?<input type="checkbox" name="complete$form_item" value="completed" class="default" /></label>
			<label>More Priority?<input type="checkbox" name="priority$form_item" value="priority" class="default" /></label>
			<label><input type="hidden" name="score$form_item" class="default" value="$score"/></label>
		</li>
_LIST_ITEMS;
		$form_item++;
	} // end for
	$r3->close();

echo <<<_END
				<li>			
					<button id="add">Add</button>
					<button type="submit">Submit</button>
				</li>
	</ul>
</form>
</div>
<script src="js/jquery-1.11.0.js"></script>
<!--    <script src="js/basic-example.js"></script> -->
</body>
</html>
_END;

function print_dump($var)
{
	echo "<pre>\n";
	var_dump($var);
	echo "</pre>\n";
}
function get_sanit_post($connection, $var){
	        return htmlentities($connection->real_escape_string($_POST[$var]));
}
?>
