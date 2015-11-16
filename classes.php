<?php

class Habit {
	// what should a habit do?
	// if I want to use it in a browser, it should return json
	// if I want to use it in a browser, it should also sanitize data
	public $habit_id
	public $habit_name;
	public $habit_json;
	public $habit_description;
	public $urgency;
	public $frequency;
	
	

	public function __construct($habit_id) {

	}
	public function toJson(array $array) {

	}
	public function sanitize($string) {

	}
	public function sortByFrequncy(array $array){

	}
	public function sortByUrgency(array $array){

	}
}
