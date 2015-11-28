<?php
require 'habit-cron.php';
/* UI 
* an urgent habit should be increase 2em at the end of a day
* a daily habit should be increase 1em at the end of a day
* a weekly habit should be increase 1em at the end of a week
* a monthly habit should be increase 1em at the end of a month
* for the for floats: urgent increase by 2 after being called 24 times, daily 1, weekly 1/7, monthly 1 / 30
 */

class HabitTest extends PHPUnit_Framework_TestCase
{
	public function test1()
	{
	$this->assertLessThanOrEqual(2, urgent());
	}

	public function test2()
	{
	$this->assertGreaterThanOrEqual(1.95, urgent());
	}

	public function test3()
	{
	$this->assertLessThanOrEqual(1, daily());
	}

	public function test4()
	{
	$this->assertGreaterThanOrEqual(1, daily());
	}

	public function test5()
	{
	$this->assertLessThanOrEqual((1/7), weekly());
	}

	public function test6()
	{
	$this->assertGreaterThanOrEqual((1/8), weekly());
	}

	public function test7()
	{
	$this->assertLessThanOrEqual((1/30), monthly()); 
	}
	public function test8()
	{
	$this->assertGreaterThanOrEqual((1/31), monthly()); 
	}
}
