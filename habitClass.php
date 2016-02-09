<?php
class Habit
{
	public $habitJson;
	public $habitArray;

	public $habitName;
	public $now;
	public $timestamp;
	public $secondsSinceCompletion;

	public function __construct($habitJson)
	{
		$this->habitArray = json_decode($habitJson, true);
		if (!$this->habitArray) throw New Exception("invalid Json: \n $habitJson \n  provided");
		$this->now = time();
		$this->habitName = $this->habitArray['habit'];
		$this->timestamp = $this->habitArray['timestamp'];
		$this->secondsSinceCompletion = $this->now - $this->timestamp;
//		$this->secondsSinceCompletion = 4400; // make all habits less than 6 hours.
	}

	public function markHabitComplete()
	{
		$this->secondsSinceCompletion = 0;
	}
	public function dailyUrgency($seconds)
	{
		if ($seconds === 0) return 0;
		if ($seconds > 345599) return 8;	
		if ($seconds < 43200) return 1;	
		if ($seconds < 86400) return 2;	
		if ($seconds < 129600) return 3;	
		if ($seconds < 172800) return 4;	
		if ($seconds < 216000) return 5;	
		if ($seconds < 259200) return 6;	
		if ($seconds < 302400) return 7;	
		return 9;
	}
}
