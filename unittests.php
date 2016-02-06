<?php
require 'urgency.php';
class HabitTest extends PHPUnit_Framework_TestCase
{
	/* Scores for CSS
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
	public function __construct(){
		$this->habit = new Habit(0,0);
	}	
	public function testBecause(){
		$this->assertTrue(0 === 0);
	}
	
	// make sure the numbers to the urgent function match expectations
	public function testHabitIsComplete(){
		var_dump($this->habit);

		$this->assertTrue($this->habit->getUrgency() === 0); // test urgency starts at zero
		$this->assertTrue($this->habit->setUrgency(0) === 0);
	}
	public function testHabitNotComplete(){
		$this->assertTrue($this->habit->getUrgency() !== -1);
		$this->assertTrue($this->habit->setUrgency(1) !== 0);
	}
}
