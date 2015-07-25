<?php
require 'TestClass.php';
Class ArraySorter {
	private $unique_array = array();
	private $current_value;
	private $current_keys = array();
	private $return_array;
	private $sort_field;
	private $test;

	public function __construct($sort_field){
		$this->sort_field = $sort_field;	
	}

	public function push_if_unique_array($array){

		$test = new TestClass;
		$test->setErrorMessage("push method not passed an array");
		$test->testTrue(is_array($array));

	//	error_print($array);
/*
		$test->setErrorMessage("array does not contain the sorting key");
		$test->testTrue(in_array($this->sort_field, $array));
*/

		// current_value is equal to value at sort_field key
		$this->current_value = $array[$this->sort_field];
		// if current_value isn't in the unique_array, add to the value 
		// to unique_array and the entire row to the return array
		
		error_dump($this->current_value);	
		error_log($this->current_value);	

		if (!in_array($this->current_value, $this->unique_array)) {
			$this->unique_array[] = $array[$this->sort_field];
			$this->return_array[] = $array;
		}	
		$test->setErrorMessage('each array should have one unique value');
		$test->testTrue(isset($this->return_array));
	}

	public function getArray(){
		return $this->return_array;
	}

	public function getSorter(){
		return $this->sort_field;
	}

	public function keyInArray($array){
		return isset($array[$this->sort_field]);	
	}

}

?>
