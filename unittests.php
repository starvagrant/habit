<?php
require 'urgency.php';
class HabitTest extends PHPUnit_Framework_TestCase
{
	/* Scores for CSS
	0 completed habit 1 - 255 right gets greener 256 - 511 solid green 512 - 767 right gets yellower 767 - 1023 solid yellow 1024 - 1279 right gets redder 1280 - 1535 solid red 1535 - 1792 darker red
	function report_habit_urgency 
	function habit_is  
	*/
	public function __construct()
	{
		$this->habit = new Habit(new DateInterval('P1Y'), new DateTime('now'));
	}	
	
	// make sure the numbers to the urgent function match expectations
	public function testHabitIsComplete()
	{

		$this->assertTrue($this->habit->getUrgency() === 0); // test urgency starts at zero
		$this->assertTrue($this->habit->addUrgency(0) === 0);
	}
	public function testHabitNotComplete()
	{
		$this->assertTrue($this->habit->getUrgency() !== -1);
		$this->assertTrue($this->habit->addUrgency(1) !== 0);
	}

	// Tests ensure addUrgency method always matches the defined thresholds in
	// The Habit Class, independent of how it is implemented.
	public function testHabitLevelOne()
	{
		$this->assertLessThan(Habit::L1_THRESHOLD, $this->habit->addUrgency(255));
	}

	public function testHabitLevelTwo(){
		$this->assertLessThan(Habit::L2_THRESHOLD, $this->habit->addUrgency(511));
	}
	public function testHabitLevelThree(){
		$this->assertLessThan(Habit::L3_THRESHOLD, $this->habit->addUrgency(767));
	}
	public function testHabitLevelFour(){
		$this->assertLessThan(Habit::L4_THRESHOLD, $this->habit->addUrgency(1023));
	}
	public function testHabitLevelFive(){
		$this->assertLessThan(Habit::L5_THRESHOLD, $this->habit->addUrgency(1279));
	}
	public function testHabitLevelSix(){
		$this->assertLessThan(Habit::L6_THRESHOLD, $this->habit->addUrgency(1535));
	}
	public function testHabitLevelSeven(){
		$this->assertLessThan(Habit::L7_THRESHOLD, $this->habit->addUrgency(1791));
	}
}
