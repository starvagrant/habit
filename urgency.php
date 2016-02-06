<?php
class Habit
{
/*	
	const URGENT = (1 / 14); // twice daily
	const DAILY = 1/7; // daily
	const WEEKLY = 1;
	const MONTHLY = 4;
*/

	public $urgency;
	public $time;

	public function __construct(){
		$this->urgency = 0;
		$this->time = 0;
	}

	public function setUrgency($time = 0){
		$this->urgency += $time;
		return $this->urgency;
	}

	public function getUrgency(){
		return $this->urgency;
	}
}
