<html lang="en-us">
<head>
	<meta charset="utf-8">
	<title>Styling Form</title>
</head>
<body>
	<form action="name.php" method="GET">
		<input type="text" name="first" value="blablabla" />	
		<input type="text" value="blabla" />	
		<input type="text" value="bla" />	
		<button type="submit">submit</button>
	</form>
<?php
	if ($_GET['food-entry0']) echo "<p> {$_GET['food-entry0']} </p>";
	if ($_GET['food-entry1']) echo "<p> {$_GET['food-entry1']} </p>";
//	echo "<pre>"; var_dump($_GET); echo "</pre>";
?>
	<div>Zero<span></span></div>
	<div>First<span></span></div>
	<div>Second<span></span></div>

	<script src="jquery-1.11.0.js"></script>
	<script src="name.js"></script>
</body>
</html>
