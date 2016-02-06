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

	public function construct($urgency, $time){
		$this->urgency = $urgency;
		$this->time = $time;
	}

	public function setUrgency($time = 0){
		$this->urgency += $time;
	}

	public function getUrgency(){
		return $this->urgency;
	}
}
