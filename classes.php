<?php
class Habit
{
		public $urgencyRating;
		public $urgencyScore;

		public function construct($urgencyScore)
		{
		$this->urgencyScore = $urgencyScore;
			$this->urgencyRating = $this->rateUrgencyScore($urgencyScore);
		}

		public function getHabitCSS($urgencyScore){
			return "background: black;";
		}

		public function getHabitCSSClassName($urgencyScore){
			return "habit-complete";
		}

		private function rateUrgencyScore($urgencyScore)
		{
			return rand(0,1792);		
		}
}
