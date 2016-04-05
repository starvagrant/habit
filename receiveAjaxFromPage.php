<?php
/*
 * process form data from habitPageAjax.js
*/

try // connect to database
{
	$dsn = "sqlite:/var/www/habit/.ht.habit.sqlite";
	$pdo = new PDO($dsn);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	$error = "PDO_ERROR_1:" . $e->getMessage;
	error_log($error);
}

try // update database
{
	$sql = "UPDATE habits SET lastCompleted=? WHERE name=?";
	$statement = $pdo->prepare($sql);
	$lastCompleted = (int)$_POST['timestamp'];
	if (!$statement->bindParam(1, $lastCompleted)) throw New PDOException("Parameter Not Bound");
	if (!$statement->bindParam(2, $_POST['habit'])) throw New PDOException("Parameter Not Bound");
	if (!$statement->execute()) throw New PDOException("Statement Not Executed");
}

catch (PDOException $e)
{
	$error = "PDO_ERROR_2:" . $e->getMessage;
	error_log($error);
}

unset($pdo);
?>
