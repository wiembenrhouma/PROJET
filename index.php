<?php
session_start();
$user='admin@tn';
$password_definit='123456789';
if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
if($username&&$password){
	if($username==$user&&$password==$password_definit){
$_SESSION['username']=$username;
header('Location: index3.php');
		
	}else{
		echo"identifiants ernonnes";
	}
}else{
	echo"veuiller remplir tous les champs";
}
	
}
?>

<link rel="stylesheet" type="text/css" href="css1.css">
<div id="container">
<form action="" method="POST">
<h1>pseudo:</h1><input type="Text" name="username"/><br/><br/>
<h2>mdp:</h2><input type="password" name="password"/><br/><br/>
<input type="submit" name="submit"/><br/><br/>
</form>
</div>
