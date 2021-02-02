<?php

if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}


// $DB_host = "localhost";
// $DB_user = "root";
// $DB_pass = "";
// $DB_name = "dbpdo";

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "Innerpeace628!";
$DB_name = "dbpdo";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}
include_once 'class.crud.php';
$crud = new crud($DB_con);


$link = $_SERVER['PHP_SELF'];
$link_array = explode('/',$link);
$page = end($link_array);
$authMessage = "";


if (!isset($_SESSION['loginfo']) && $page !== "index.php" ){
    header("Location: index.php");
    exit();
}

else if(isset($_SESSION['loginfo']) && $page == "index.php" ){
    header("Location: addurl.php");
	exit();
}

if(isset($_POST['loginbtn'])) {
	$query_confirm = "SELECT * FROM users WHERE email=:email AND password=:passsword";
	$stmt = $DB_con->prepare($query_confirm);	
	$stmt->bindparam(":email",$_POST['email']);
	$stmt->bindparam(":passsword",$_POST['password']);
	$stmt->execute();
	$currentUser=$stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount() > 0) {
		$_SESSION['loginfo'] = $_POST['email'];
		$_SESSION['username'] = $currentUser['username'];
		header("Location: addurl.php");
		exit();
	}
	else {
	  $authMessage = "Invalid password or email";
	}
}
?>
