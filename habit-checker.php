<?php 

function error_dump($var){
	ob_start();
	var_dump($var);
	$error = ob_get_contents();
	ob_end_clean();
	error_log($error);
}
function print_gradient_css_string($a) {	// four element numeric array
	echo "background: linear-gradient(to right, rgba($a[0], $a[1], 0, 0.7), rgba($a[2], $a[3], 0, 1));";
}

function scores_to_gradient_arrays($int){
	// based on the integer fed to it, returns an array for color gradient: red, green, red, green
	// the result being a progression from black to green to dark red, as the integer gets higher	
	switch($int){
		case 0: 
			return array(0,0,0,0);
			break;
		case ($int / 256 < 1): 						// 1 - 255	right gets greener
			$right_green = $int;
			return array(0, 0, 0, $right_green);
		break;
		case ($int / 256 < 2): 						// 256 - 511 solid green
			$left_green = $int - 255;
			return array(0, $left_green, 0, 255);
		break;
		case ($int / 256 < 3): 						// 512 - 767 right gets yellower
			$right_red = $int - 511;	
			return array(0, 255, $right_red, 255);
		break;
		case ($int / 256 < 4):						// 767 - 1023 solid yellow
			$left_red = $int - 767;
			return array($left_red, 255, 255, 255);
		break;
		case ($int / 256 < 5): 						// 1024 - 1279 right gets redder
			$right_green = 255 - ($int - 1024); 
			return array(255, 255, 255, $right_green);
		break;
		case ($int / 256 < 6):						// 1280 - 1535 solid red
			$left_green = 255 - ($int - 1280);
			return array(255, $left_green, 255, 0);
		break;
		case ($int / 256 < 7):						// 1535 - 1792 darker red
			$left_red =(int)(255 - ($int - 1536) / 2);
			$right_red =(int)(255 - ($int - 1536) / 2);
			return array($left_red, 0, $right_red, 0);
		break;
		default: 
			return array(127,0,127,0); 
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
#habit-complete { <?php print_gradient_css_string(scores_to_gradient_arrays(0)); ?> }
#habit-green{ <?php print_gradient_css_string(scores_to_gradient_arrays(256)); ?> }
#habit-yellow-green { <?php print_gradient_css_string(scores_to_gradient_arrays(512)); ?>}
#habit-yellow { <?php print_gradient_css_string(scores_to_gradient_arrays(768)); ?> }
#habit-yellow-orange { <?php print_gradient_css_string(scores_to_gradient_arrays(1024)); ?> }
#habit-orange { <?php print_gradient_css_string(scores_to_gradient_arrays(1280)); ?> }
#habit-red { <?php print_gradient_css_string(scores_to_gradient_arrays(1536)); ?> }
#habit-dark-red{ <?php print_gradient_css_string(scores_to_gradient_arrays(1791)); ?> }
table { 
  background: -webkit-linear-gradient(left, rgba(#ff0,0,0,0), rgba(#ff0,0,0,1)); /* For Safari 5.1 to 6.0 */
  background: -o-linear-gradient(right, rgba(255,0,0,0), rgba(255,0,0,1)); /* For Opera 11.1 to 12.0 */
  background: -moz-linear-gradient(left, rgba(255,0,0,0), rgba(255,0,0,1)); /* For Firefox 3.6 to 15 */
  background: linear-gradient(to left, rgba(255,0,0,0), rgba(255,0,0,1)); /* Standard syntax (must be last) */
  background: linear-gradient(to right,rgba(255,0,0,0.6), rgba(255,0,0,1))
}
</style>
</head>
<body>
<pre>
</pre>
<pre>
<?php $habit_json =json_decode(file_get_contents('habit.json'), true);

// grab the two items from each array which are at highest value
foreach($habit_json as $array_key => $habit_array){
	$habit_array = $habit_array;
	echo "<hr />";
	var_dump($habit_array);
	arsort($habit_array);
	$habit_array = array_slice($habit_array, 0, 2);
	$habit_json[$array_key] = $habit_array;
	var_dump($habit_array);
	echo "<hr />";
}
?>

<?php var_dump($habit_json); ?>
</pre>

	<table>
<?php foreach($habit_json as $array_keys => $habit_arrays):
		foreach($habit_arrays as $habit => $habit_value):
	echo <<<_TR
		<tr>
			<td>$habit</td>
			<td>$habit_value</td>
		</tr>
_TR;
		endforeach;
	endforeach;
	
?>
	</table>


</body>
</html>
