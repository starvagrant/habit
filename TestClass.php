<?php

function error_dump($var){
	ob_start();
	var_dump($var);
	$error = ob_get_contents();
	ob_end_clean();
	error_log($error);
}
function error_print($var){
	ob_start();
	print_r($var);
	$error = ob_get_contents();
	ob_end_clean();
	error_log($error);
}
function my_assert_handler($file, $line, $code, $desc = null) {
	echo "Assertion Failed:
	File '$file'
	Line '$line'
	Code '$code'";
	if ($desc) {
		echo "\n description: $desc \n";
	}		
}

Class TestClass {
	public $expected;
	public $result;
	public $message;
	public $assertion;

	public function __construct($strict = true){
		if ($strict === true){
			assert_options(ASSERT_ACTIVE, 1);
			assert_options(ASSERT_BAIL, 1);
			assert_options(ASSERT_CALLBACK, 'my_assert_handler');
		} else if ($strict === "quiet"){
			assert_options(ASSERT_ACTIVE, 1);
			assert_options(ASSERT_WARNING, 0);
			assert_options(ASSERT_QUIET_EVAL, 1);
			assert_options(ASSERT_CALLBACK, 'my_assert_handler');
		} else {
			assert_options(ASSERT_ACTIVE, 1);
			assert_options(ASSERT_BAIL, 0);
			assert_options(ASSERT_CALLBACK, 'my_assert_handler');
		}
		$message = "default";
	}
	public function setExpected($expected){
		$this->expected = $expected;
	}
	public function setResult($result){
		$this->result = $result;
	}
	public function setErrorMessage($message){
		$this->message = $message;
	}

	public function test(){
		assert('$this->expected == $this->result', "$this->message");
		assert('$this->expected === $this->result', "$this->message: (class inequality)");
	}
	public function testTrue($assertion){
		if (!isset($this->message)) $this->message = "default";	
		assert($assertion === true, $this->message);
		assert($assertion == true, "{$this->message}, ('strict')");
	}
} // end Test Class
?>
