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
		$this->habit = new Habit('{"json":"a"}', new DateInterval('P1Y'));
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
		$this->assertGreaterthanOrEqual(Habit::L1_THRESHOLD, $this->habit->addUrgency(1));
	}

	public function testHabitLevelTwo(){
		$this->assertGreaterThanOrEqual(Habit::L2_THRESHOLD, $this->habit->addUrgency(2));
	}
	public function testHabitLevelThree(){
		$this->assertGreaterThanOrEqual(Habit::L3_THRESHOLD, $this->habit->addUrgency(3));
	}
	public function testHabitLevelFour(){
		$this->assertGreaterThanOrEqual(Habit::L4_THRESHOLD, $this->habit->addUrgency(4));
	}
	public function testHabitLevelFive(){
		$this->assertGreaterThanOrEqual(Habit::L5_THRESHOLD, $this->habit->addUrgency(5));
	}
	public function testHabitLevelSix(){
		$this->assertGreaterThanOrEqual(Habit::L6_THRESHOLD, $this->habit->addUrgency(6));
	}
	public function testHabitLevelSeven(){
		$this->assertGreaterThanOrEqual(Habit::L7_THRESHOLD, $this->habit->addUrgency(7));
	}
	public function testHabitArrayContainsDefinedKeys(){
		$this->assertArrayHasKey('json', $this->habit->habitArray); 	
	}
}
