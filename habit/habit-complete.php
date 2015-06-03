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
if(isset($_GET)) {
	ob_start();

	var_dump($_GET);
	$a = ob_get_contents();
	error_log($a);

	ob_end_clean();
}
print("this is a response");
/*
<!DOCTYPE html>
<html lang="en-us">

<head>
	<meta charset="utf-8">
<title></title>
<style>
// {	text-indent: 4em;	} 
</style>
</head>

<body>
 <pre>
	<?php var_dump($a); ?>
	yoyoyo
 </pre>

</body>
</html>
*/
?>
