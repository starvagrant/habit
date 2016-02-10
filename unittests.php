<?php
require 'habitClass.php';
class HabitTest extends PHPUnit_Framework_TestCase
{
	/* Scores for CSS
	0 completed habit 1 - 255 right gets greener 256 - 511 solid green 512 - 767 right gets yellower 767 - 1023 solid yellow 1024 - 1279 right gets redder 1280 - 1535 solid red 1535 - 1792 darker red
	function report_habit_urgency
	function habit_is
	*/
	public function __construct()
	{
		$this->now = new DateTime;
		$this->habit = new Habit('{"habit": "Reflection in Git", "timestamp":"1454271192"}');
	}

	// make sure the numbers to the urgent function match expectations
	public function testHabitNotComplete()
	{
		$this->assertGreaterThan(0, $this->habit->secondsSinceCompletion); // get seconds should be a nonzero positive.
	}

	public function testHabitIsComplete()
	{
		$this->habit->markHabitComplete();
		$this->assertEquals(0, $this->habit->secondsSinceCompletion); // get seconds should equal zero if habit is completed
	}

	public function testDailyUrgency()
	{
		$this->assertEquals(0, $this->habit->dailyUrgency(0));
		$this->assertEquals(8, $this->habit->dailyUrgency(345601)); // second over four days
		$this->assertEquals(2, $this->habit->dailyUrgency(86399)); // second under day.
	}

	public function testHabitArrayContainsDefinedKeys(){
		$this->assertArrayHasKey('name', $this->habit->habitArray);
		$this->assertArrayHasKey('timestamp', $this->habit->habitArray);
	}
}
