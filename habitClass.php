<?php
class Habit
{
	public $habitArray;
	public $habitName;
	public $now;
	public $timestamp;
	public $secondsSinceCompletion;

	public function __construct(array $habitArray)
	{
		$this->habitArray = $habitArray;
		$this->now = time();
		$this->habitName = $this->habitArray['name'];
		$this->timestamp = $this->habitArray['lastCompleted'];
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
