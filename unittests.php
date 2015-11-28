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
	public $json;
	public $jsonObject;
	public $stashed;

	public function test_json_is_stored(){
		$this->json = array('test' => 'test');
		$this->stashed = stash_json($this->json, 'test.json');
		$this->assertTrue(isset($this->stashed));
	}

	public function test_file_is_json(){
		$this->jsonObject = get_json_object('test.json');
		$this->assertTrue(isset($this->jsonObject));
	}
	public function test_urgency_max_value()
	{
	$this->assertLessThanOrEqual(2, urgent());
	}

	public function test_urgency_min_value()
	{
	$this->assertGreaterThanOrEqual(1.95, urgent());
	}

	public function test_daily_max_value()
	{
	$this->assertLessThanOrEqual(1, daily());
	}

	public function test_daily_min_value()
	{
	$this->assertGreaterThanOrEqual(1, daily());
	}

	public function test_weekly_max_value()
	{
	$this->assertLessThanOrEqual((1/7), weekly());
	}

	public function test_weekly_min_value()
	{
	$this->assertGreaterThanOrEqual((1/8), weekly());
	}

	public function test_monthly_max_value()
	{
	$this->assertLessThanOrEqual((1/30), monthly()); 
	}
	public function test_monthly_min_value()
	{
	$this->assertGreaterThanOrEqual((1/31), monthly()); 
	}
}
