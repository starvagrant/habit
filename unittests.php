<?php
require 'habit-cron.php';
/* UI 
* an urgent habit should be increase 2em at the end of a day
* a daily habit should be increase 1em at the end of a day
* a weekly habit should be increase 1em at the end of a week
* a monthly habit should be increase 1em at the end of a month
* for the for floats: urgent increase by 2 after being called 24 times, daily 1, weekly 1/7, monthly 1 / 30
 */

/*
 * Currently I have little idea what my unit tests are testing. In TDD style, it's time to rewrite some
 *
 */

class HabitTest extends PHPUnit_Framework_TestCase
{
	public $json;
	public $jsonObject;
	public $stashed;

	// make sure json is being saved
	public function test_json_is_stored(){
		$this->json = array('test' => 'test');
		$this->stashed = stash_json($this->json, 'test.json');
		$this->assertTrue(isset($this->stashed));
	}

	// make sure a file is json
	public function test_file_is_json(){
		$this->jsonObject = get_json_object('test.json');
		$this->assertTrue(isset($this->jsonObject));
	}
	/* REGARDIGN HABIT ALGORITHMS
	 an urgent habit should be twice daily, daily, weekly, monthly
	 Under my current scheme, I have eight classes based on 256
	 An urgent habit should go red if not done for two days 
	 An daily habit should go red if not done for three days 
	 An weekly habit should go red if not done for ten days 
	 An monthly habit should go red if not done for forty days

	 To what degree should I consider a habit undone? What's the percentage?
	 
	 Scores
	 0 completed habit
	 1 - 255 right gets greener
	 256 - 511 solid green
	 512 - 767 right gets yellower
	 767 - 1023 solid yellow
	1024 - 1279 right gets redder
	 1280 - 1535 solid red
	 1535 - 1792 darker red
	 function report_habit_urgency 
	 function habit_is  

	 	*/		
	

	// make sure the numbers to the urgent function match expectations
	public function test_urgent_algorithm(){

	}

	// make sure the numbers to the daily function match expectations
	public function test_daily_algorithm(){
	}

	// make sure the numbers to the weekly function match expectations
	public function test_weekly_algorithm(){
	}
	
	// make sure the numbers to the monthly function match expectations
	public function test_monthly_algorithm(){

	}
}
