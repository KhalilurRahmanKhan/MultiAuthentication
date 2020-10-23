<?php
 session_start();
 $conn = new mysqli("localhost","root","","multiauth");
 $msg="";

 if(isset($_POST['login'])){
 	$username= $_POST['username'];
 	$password= $_POST['password'];
 	$password= sha1($password);
 	$usertype= $_POST['usertype'];

 	$sql= "SELECT * FROM users WHERE username=? AND password=? AND usertype=?";
 	$stmt= $conn->prepare($sql);
 	$stmt->bind_param("sss",$username,$password,$usertype);
 	$stmt->execute();
 	$result= $stmt->get_result();
 	$row= $result->fetch_assoc();
 	session_regenerate_id();
 	$_SESSION['username'] = $row['username'];
 	$_SESSION['role'] = $row['usertype'];
 	session_write_close();
 	if($result->num_rows==1 && $_SESSION['role']=="student"){
 		header("location:student.php");
 	}
 	else if($result->num_rows==1 && $_SESSION['role']=="teacher"){
 		header("location:teacher.php");
 	}
 	else if($result->num_rows==1 && $_SESSION['role']=="admin"){
 		header("location:admin.php");
 	}
 	else{
 		$msg= "Username or password is incorrect!!!";
 	}

 }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Multi authentication system</title>
</head>
<body>
	<center>
	<div>
		<h1>Multi authentication system</h1>
		<form action="<?= $_SERVER['PHP_SELF']?>" method="post" >
			<input type="text" name="username" placeholder="username" required><br><br>
			<input type="password" name="password" placeholder="password" required><br><br>
			<label for="usertype" > User type:</label>
			<input type="radio" name="usertype" value="student" required > student |
			<input type="radio" name="usertype" value="teacher" required >teacher |
			<input type="radio" name="usertype" value="admin" required >admin <br><br>
			<input type="submit" name="login" ><br><br>
			<p><?= $msg; ?></p>

		</form>
    </div>
    </center>

</body>
</html>