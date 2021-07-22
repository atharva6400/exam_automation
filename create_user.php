<?php
//Create User
include_once('config.php');
if(isset($_POST['Submit']))
{
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$password = md5($password);
	$department = mysqli_real_escape_string($conn, $_POST['department']);
	$CheckUserSQL = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$username'"));
	if($CheckUserSQL > 0)
	 header('Location: add_user.php?error=Username Exists');
	else
	 {
		 $CreateUser = mysqli_query($conn, "INSERT into admin_user (username,email,name,password,department,role) values ('$username','$email','$name','$password','$department','admin')");
		 if($CreateUser)
		  header ('Location: add_user.php?success=1');
		 else
		  header("Location: add_user.php?error=Contact Admin");
	 }
}
else
{
	header ('Location: add_user.php');
}
?>
