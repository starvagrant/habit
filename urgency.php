<?php
class Habit
{
	const L1_THRESHOLD = 256; // numbers, multiples of 256, represent colors for CSS properties.
	const L2_THRESHOLD = 512;
	const L3_THRESHOLD = 768;
	const L4_THRESHOLD = 1024;
	const L5_THRESHOLD = 1280;
	const L6_THRESHOLD = 1536;
	const L7_THRESHOLD = 1792;

	public $urgency;
	public $time;
	public $interval;

	public function __construct(DateInterval $interval){

		$this->urgency = 0;
	}

	public function addUrgency($urgency = 0){
		$this->urgency += $urgency;
		return $this->urgency;
	}

	public function getUrgency(){
		return $this->urgency;
	}

	public function setUrgency($urgency = 0){
		$this->urgency = $urgency;
		return $this->urgency;
	}
}
