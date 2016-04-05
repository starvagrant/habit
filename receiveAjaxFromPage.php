<?php
//	error_log($_POST['habit']);
//require 'debugging_functions.php'

try // connect to database
{
	$dsn = "sqlite:/var/www/habit/.ht.habit.sqlite";
	$habitPDO = new PDO($dsn);
	$habitPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e)
{
	$error = "PDO_ERROR_1:" . $e->getMessage;
	error_log($error);
}
try // fetch data into arrays
{
	$sql = "UPDATE habits SET lastCompleted=? WHERE name=?";
	$statement = $habitPDO->prepare($sql);
	error_log($_POST['lastCompleted']);
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

?>
