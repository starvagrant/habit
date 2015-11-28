<?php

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
