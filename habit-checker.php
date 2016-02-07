<?php 
//require 'debugging_functions.php'
require 'urgency.php';

$dailyHabit = new Habit(new DateInterval('P1D'));

function toGradientCss(array $a) {	// four element numeric array
echo <<<_CSS
		background: -webkit-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -o-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: -moz-linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));
		background: linear-gradient(to left, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));

_CSS;
}

function scoresToGradients($int){
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
function scoresToClasses($int){
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
.habit-complete { <?php toGradientCss(scoresToGradients(1)); ?> }
.habit-green{ <?php toGradientCss(scoresToGradients(2)); ?> }
.habit-yellow-green { <?php toGradientCss(scoresToGradients(3)); ?>}
.habit-yellow { <?php toGradientCss(scoresToGradients(4)); ?> }
.habit-yellow-orange { <?php toGradientCss(scoresToGradients(5)); ?> }
.habit-orange { <?php toGradientCss(scoresToGradients(6)); ?> }
.habit-red { <?php toGradientCss(scoresToGradients(7)); ?> }
.habit-dark-red{ <?php toGradientCss(scoresToGradients(8)); ?> }
.habit-nil{ background: black; }
.habit-default { background: blue;}
</style>
</head>
<body>
<pre>

</pre>

	<table>
<?php for ($i = 0; $i < 9; $i++)
	{
	$habit_class = scoresToClasses($i);
	echo <<<_TR
		<tr>
			<td class="$habit_class">habit</td>
		</tr>
_TR;
	}	
	
?>
	</table>
</body>
</html>
