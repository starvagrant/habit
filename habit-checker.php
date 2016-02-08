<?php 
//require 'debugging_functions.php'
require 'urgency.php';
$json = file_get_contents('habit.json');
$fileJson = json_decode($json, true);
//foreach($fileJson as $habits) 
$dailyHabits[] = new Habit('{"Habit": "Reflection in Git", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Programming Exercises", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Novel Review", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Financial Tracking", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Medicine", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Wash Dishes", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Clear Desk", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Avoid Distractions", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));
$dailyHabits[] = new Habit('{"Habit": "Career Skills", "DateTime":"2016-02-08 12:34:00"}', new DateInterval('P1D'));

function toGradientCss(array $a) 
{	// four element numeric array $a[0] left red, $a[1] left green, $a[2] right red, $a[3] rigit green
echo <<<_CSS
		
		background: -webkit-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -o-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -moz-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));

_CSS;
}

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
// same logic with scores as above, only to apply css classes 
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
 * Current Styling Rules are Static, that is 8 possible values only
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
	<table>
<?php 
	$i = 0;
	foreach ($dailyHabits as $habitkey => $habitvalue):
	$habit_class = scoresToClasses($habitvalue->addUrgency($i)); 
	$i++;
	$completion = $habitvalue->habitCompletionTime->format('Y-m-d H:i:s');
	var_dump($completion);
		$now = date('Y-m-d H:i:s');
		echo <<<_TR
		<tr>
			<td class="$habit_class"> $habitvalue->habitName</td>
			<td class="$habit_class"> Was lasted completed $completion </td>
			<td class="$habit_class"> But it is now $now </td>
		</tr>
_TR;
	endforeach;		
	
?>
	</table>
</body>
</html>
