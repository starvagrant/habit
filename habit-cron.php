<?php
$habitJsonObject = get_json_object('habit.json');

var_dump($habitJsonObject);

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

var_dump($habitJsonObject);
// stash_json($habitJsonObject, 'habit.json');

function get_json_object($file){
	return json_decode(file_get_contents($file), TRUE);
}

function stash_json($json, $file){
	return file_put_contents($file, json_encode($json));
}

/* Test Mods 1.029, 1, .92, 0.867 */

function urgent($habit = 1){
	// normally, habit should equal 1
	for ($i = 0; $i < 24; $i++)
	{
		$habit *= 1.029;
	}
	return $habit;

}
function daily($habit = 1){
	for ($i = 0; $i < 24; $i++)
	{
		$habit *= 1;
	}
	return $habit;

}
function weekly($habit = 1){
	for ($i = 0; $i < 24; $i++)
	{
		$habit *= 0.92;
	}
	return $habit;
}
function monthly($habit = 1){
	for ($i = 0; $i < 24; $i++)
	{
		$habit *= 0.867;
	}
	return $habit;
}
