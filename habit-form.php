<?php
require 'login.php';

$db_database="habit";
$c = new mysqli($db_hostname, $db_username, $db_password, $db_database);
        if ($c->connect_error) die ($c->connect_error);

function print_dump($var)
{
	echo "<pre>\n";
	var_dump($var);
	echo "</pre>\n";
}
function get_sanit_post($connection, $var){
	        return htmlentities($connection->real_escape_string($_POST[$var]));
}
if (!$_POST)
{
	$sql_sel1 =	"SELECT `habit_name`, `habit_description`, `insert_date`, `update_date`
			`priority`, `completion`, `finished`, `info` from tracker ORDER BY priority DESC LIMIT 2;";		       
	$sql_sel2 =	"SELECT `habit_name`, `habit_description`, `insert_date`, `update_date`
			`priority`, `completion`, `finished`, `info` from tracker ORDER BY completion, priority DESC LIMIT 6;";		       
	$sql_sel3 =	"SELECT `habit_name`, `habit_description`, `insert_date`, `update_date`
			`priority`, `completion`, `finished`, `info` from tracker ORDER BY completion DESC LIMIT 2;";		       
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
}
	
echo <<<_DOCUMENT
<!DOCTYPE html>
<html>
  <head>
    <title>habits.php</title>
<!--    <link rel="stylesheet" href="habit-form.css" /> -->
    <link rel="stylesheet" href="habit-form.css" /> 
  </head>
  <body>
    <div id="page">
      <h1 id="header">List</h1>
      <h2>Learn Habits</h2>
      <form action='habit-form.php' method="post"> 
		<ul id="completion">
<!-- putting top four habit items up front -->
_DOCUMENT;
/*
print_dump($r1);
print_dump($r2);
print_dump($r3);
print_dump($r1->num_rows);
print_dump($r2->num_rows);
print_dump($r3->num_rows);
 */

			if ($error) echo "<li>$error</li>";
			if (!$_POST)
			{
				$form_item = 1;
				$rows1 = $r1->num_rows; 
				for ($i = 0; $i < $rows1; $i++)
				{
					$r1->data_seek($i);
					$list1[$i] = $r1->fetch_array(MYSQLI_ASSOC);
					$score = $list1[$i]['priority'] * $list1[$i]['completion'];
					echo <<<_LIST_ITEMS

					<li>
						<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list1[$i]['habit_name']}"/></label>
						<label>Complete?<input type="checkbox" name="complete$form_item" class="default" /></label>
						<label>More Priority?<input type="checkbox" name="priority$form_item" class="default" /></label>
						<label><input type="hidden" name="score$form_item" class="default" value="$score"/></label>
					</li>
_LIST_ITEMS;
					$form_item++;
				} // end for

				$rows2 = $r2->num_rows; 
				for ($i = 0; $i < $rows2; $i++)
				{
					$r2->data_seek($i);
					$list2[$i] = $r2->fetch_array(MYSQLI_ASSOC);
					$score = $list2[$i]['priority'] * $list2[$i]['completion'];
					echo <<<_LIST_ITEMS

					<li>
						<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list2[$i]['habit_name']}"/></label>
						<label>Complete?<input type="checkbox" name="complete$form_item" class="default" /></label>
						<label>More Priority?<input type="checkbox" name="priority$form_item" class="default" /></label>
						<label><input type="hidden" name="score$form_item" class="default" value="$score"/></label>
					</li>
_LIST_ITEMS;
					$form_item++;
				} // end for
				$rows3 = $r3->num_rows; 
				for ($i = 0; $i < $rows3; $i++)
				{
					$r3->data_seek($i);
					$list3[$i] = $r3->fetch_array(MYSQLI_ASSOC);
					$score = $list3[$i]['priority'] * $list3[$i]['completion'];
					echo <<<_LIST_ITEMS

					<li>
						<label>Habit:<input type="text" name="habit$form_item" class="default" value = "{$list3[$i]['habit_name']}"/></label>
						<label>Complete?<input type="checkbox" name="complete$form_item" class="default" /></label>
						<label>More Priority?<input type="checkbox" name="priority$form_item" class="default" /></label>
						<label><input type="hidden" name="score$form_item" class="default" value="$score"/></label>
					</li>
_LIST_ITEMS;
					$form_item++;
				} // end for
			} // end if

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
?>
