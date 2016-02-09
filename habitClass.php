<?php
class Habit
{
	public $timestamp;
	public $now;
	public $habitJson;
	public $habitArray;
	public $habitCompletionTime;
	public $habitName;

	public function __construct($habitJson){
		$this->urgency = 0;
		$this->habitArray = json_decode($habitJson, true);
		if (!$this->habitArray) throw New Exception("invalid Json: \n $habitJson \n  provided");
		$this->now = time();
		$this->habitName = $this->habitArray['habit'];
		$this->timestamp = $this->habitArray['timestamp'];
	}

	public function markHabitComplete(){
		$this->timestamp = $this->now;
	}

	public function getSeconds(){
		return $this->now - $this->timestamp;
	}
}
