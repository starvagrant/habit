<?php
require 'habitClass.php';

/*
 *	Put the habits listed in the sqlite database into objects
 *	of the class habit, as defined in habitClass.php
*/

try
{
	$dsn = "sqlite:/var/www/html/habit/.ht.habit.sqlite";
	$pdo = new PDO($dsn);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	$error = "PDO_ERROR_1:" . $e->getMessage;
	error_log($error);
}
try
{
	$sql = "SELECT * FROM habits";
	$statement = $pdo->prepare($sql);
	if (!$statement->execute()) throw New PDOException("Statement Not Executed");
	while ($result = $statement->fetch(PDO::FETCH_ASSOC))
	{
		$dailyHabits[] = new Habit(array("name" => $result['name'], "lastCompleted" => $result['lastCompleted']));
	}
}

catch (PDOException $e)
{
	$error = "PDO_ERROR_2:" . $e->getMessage;
	error_log($error);
}

unset($pdo);

/*
 *	The habits are represented as text with css gradient backgrounds.
 *	The following function produces that Css, with prefixes for older browsers.
 *	This is the program's 'view' in MVC terminology.
 *	The program adapts the following color scheme:
 *		- A recently completed habit transitions from black to green.
 *		Black means unimportant. Transition to green makes habit readable.
 *		- As habit urgency increases, the transition goes from green to dark red
 *		- A habit is considered unimportant after a while, in which case the color goes solid black
 */

function toGradientCss(array $a) // four element numeric array $a[0] left red, $a[1] left green, $a[2] right red, $a[3] rigit green
{
echo <<<_CSS

		background: -webkit-linear-gradient(to right, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -o-linear-gradient(to right, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -moz-linear-gradient(to right, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: linear-gradient(to right, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));

_CSS;
}

/*
 *	scoresToGradients takes an integer an returns an array for the function toGradientCss, currently
 *	there are only 9 discrete warning levels hence the case statement 0 to 9.
 */
function scoresToGradients($int)
{
	// based on the integer fed to it, returns an array for color gradient: red, green, red, green
	// the result being a progression from black to green to dark red, as the integer gets higher
	switch($int){
		case 0:
			return array(0,0,0,0);
			break;
		case 1:
			return array(0, 0, 0, 255); // transition: black to green
		break;
		case 2:
			return array(0, 255, 0, 255); // solid green
		break;
		case 3:
			return array(0, 255, 255, 255); // transition, green to yellow
		break;
		case 4:				// solid yellow
			return array(255, 255, 255, 255);
		break;
		case 5: 				// transition, yellow to red
			return array(255, 255, 255, 0);
		break;
		case 6:						// solid red
			return array(255, 0, 255, 0);
		break;
		case 7:						//  darker red
			return array(127, 0, 127, 0);
		break;
		case 8:
			return array(0, 0, 0, 0); // black
		default:
			return array(0, 0, 255, 0);  // black to red
		break;
	}
}
/*
 *	@scoresToClasses has the same logic as @scoresToGradients
 *	except that it produces css class names not the numerical
 *	gradients used in those classes
*/
function scoresToClasses($int)
{
	switch($int){
		case 0:
			return "habit-complete";
			break;
		case 1: 						// 1 - 255	right gets greener
			return "habit-green";
		break;
		case 2: 						// 256 - 511 solid green
			return "habit-yellow-green";
		break;
		case 3: 						// 512 - 767 right gets yellower
			return "habit-yellow";
		break;
		case 4:						// 767 - 1023 solid yellow
			return "habit-yellow-orange";
		break;
		case 5: 						// 1024 - 1279 right gets redder
			return "habit-orange";
		break;
		case 6:						// 1280 - 1535 solid red
			return "habit-red";
		break;
		case 7:						// 1535 - 1792 darker red
			return "habit-dark-red";
		break;
		case 8:
			return "habit-nil"; // plus 2048 habit grows black
		default:
			return "habit-default";
		break;
	}

}
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
	<meta charset="utf-8" />
<title>Habit Checker</title>
<style>

/*
 * Generate the css for the urgency levels here. This will be the
 * applications view.
 *
 */

.habit-complete { <?php toGradientCss(scoresToGradients(0)); ?> }
.habit-green{ <?php toGradientCss(scoresToGradients(1)); ?> }
.habit-yellow-green { <?php toGradientCss(scoresToGradients(2)); ?>}
.habit-yellow { <?php toGradientCss(scoresToGradients(3)); ?> }
.habit-yellow-orange { <?php toGradientCss(scoresToGradients(4)); ?> }
.habit-orange { <?php toGradientCss(scoresToGradients(5)); ?> }
.habit-red { <?php toGradientCss(scoresToGradients(6)); ?> }
.habit-dark-red{ <?php toGradientCss(scoresToGradients(7)); ?> }
.habit-nil{ background: black; }
.habit-default { background: blue;}

</style>
</head>
<body>
<form action="" id="habit-form">
	<table>
<?php
	$i = 0;
	foreach ($dailyHabits as $habit):
		$urgency = $habit->dailyUrgency($habit->secondsSinceCompletion);
		$habit_class = scoresToClasses($urgency);
		$i++;
		$lastCompleted = DateTime::CreateFromFormat('U', $habit->timestamp)->format('m/d H:i');
		$currentDate = DateTime::CreateFromFormat('U', $habit->now)->format('m/d H:i');

	// Table Rows give name of habit, its last completion, and the time the form has been generated
	// Only Clientside JS can give when the item is actually submitted.

		echo <<<_TR
		<tr>
			<td class="$habit_class"> $habit->habitName</td>
			<td class="$habit_class"> $lastCompleted </td>
			<td class="$habit_class"> $currentDate</td>
			<td><button>Mark as Complete</button></td>
			<td>
				<input type="hidden" name="formCreated" value="$habit->now" />
				<input type="hidden" name="habit" value="$habit->habitName" />
			</td>
		</tr>

_TR;
	endforeach;

?>
	</table>
</form>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="habitPageAjax.js"></script>
</body>
</html>
