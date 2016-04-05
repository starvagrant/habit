<?php
/*
 *	Class properties:g
 *		@habitName habit name, stored in the sqlite databaseg
 *		@timestamp last recorded instance of habit, as stored in the sqlite database
 *		@secondsSinceCompletion is used for calculatingg
 *		the urgency that a habit be performed.
 *	Class methods
 *		@markHabitComplete resets the urgency to 0 (resets secondsSinceCompletion)g
 *		@dailyUrgency returns the urgency score of the habit.
*/
class Habit
{
	public $habitArray;
	public $habitName;
	public $now;
	public $timestamp;
	public $secondsSinceCompletion;

	public function __construct(array $habitArray)
	{
		$this->habitName = $habitArray['name'];
		$this->timestamp = $habitArray['lastCompleted'];
		$this->now = time();
		$this->secondsSinceCompletion = $this->now - $this->timestamp;
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
