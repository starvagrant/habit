<?php

$habitJsonObject = get_json_object('habit.json');

foreach($habitJsonObject['urgent'] as $key => $value){
	$habitJsonObject['urgent'][$key] = urgent($value);
}

foreach($habitJsonObject['daily'] as $key => $value){
	$habitJsonObject['daily'][$key] = daily($value);
}

foreach($habitJsonObject['weekly'] as $key => $value){
	$habitJsonObject['weekly'][$key] = weekly($value);
}

foreach($habitJsonObject['monthly'] as $key => $value){
	$habitJsonObject['monthly'][$key] = monthly($value);
}
// reseting a field.
// $habitJsonObject = reset_field('urgent', 'dishes', $habitJsonObject);

stash_json($habitJsonObject, 'habit.json');

// not that I should need to reset a field (this script should only be called by cron)
// but here's a function to do so.

function reset_field($array, $key, $habitJsonObject){

	$habitJsonObject[$array][$key] = 0;
	return $habitJsonObject;
}

function get_json_object($file){
	return json_decode(file_get_contents($file), TRUE);
}

function stash_json($json, $file){
	return file_put_contents($file, json_encode($json);
}

function urgent($habit = 1){
	$habit = $habit + (1 / 13); // 1 / 1 + 12
	return $habit;
}
function daily($habit = 1){
	$habit = $habit + (1 / 25); // 1 / 1 + 24 (hours per day)
	return $habit;

}
function weekly($habit = 1){
	$habit = $habit + (1/169); // 1 / 1 + 168 (hours per week)
	return $habit;
}
function monthly($habit = 1){
	$habit = $habit + (1/5041); // 1 / 1 + 5040 (hours per 30 days)
	return $habit;
}
