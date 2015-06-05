<?php
if(isset($_POST)) {
	ob_start();
		var_dump($_POST);
		$a = ob_get_contents();
		error_log($a);
	ob_end_clean();
} else {
	error_log('post_empty');
}

print("this is a response");
?>
