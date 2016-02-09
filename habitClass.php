<?php
class Habit
{
	const L1_THRESHOLD = 1; // numbers, multiples of 256, represent colors for CSS properties.
	const L2_THRESHOLD = 2;
	const L3_THRESHOLD = 3;
	const L4_THRESHOLD = 4;
	const L5_THRESHOLD = 5;
	const L6_THRESHOLD = 6;
	const L7_THRESHOLD = 7;

	public $urgency;
	public $interval;
	public $habitJson;
	public $habitArray;
	public $lastCompleted;
	public $habitCompletionTime;
	public $habitName;


	public function __construct($habitJson, DateInterval $interval){
		$this->urgency = 0;
		if (!json_decode($habitJson)) throw New Exception('invalid Json provided'); 
		$this->habitArray = json_decode($habitJson, true);
		$this->interval = $interval;
		$this->now = new DateTime();
		$this->habitName = $this->habitArray['Habit'];
		$this->habitCompletionTime = DateTime::CreateFromFormat('Y-m-d H:i:s', $this->habitArray['DateTime']);
		if (!$this->habitCompletionTime) throw New Exception("invalid string: {$this->habitArray['DateTime']} provided to format 'Y-m-d H:i:s'");
	}

	public function addUrgency($urgency = 0){
		$this->urgency += $urgency;
		return $this->urgency;
	}

	public function getUrgency(){
		$this->lastCompleted = $this->now->diff($this->habitCompletionTime);
		if ($this->lastCompleted === false) throw New Exception('Method Habit::getUrgency failed');
			
			}

	public function setUrgency($urgency = 0){
		$this->urgency = $urgency;
		return $this->urgency;
	}
}
