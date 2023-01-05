<?php
session_start();
	$_SESSION['ADMIN_LOGGED_IN']="";
	$_SESSION['STUDENT_NAME']="";
	$_SESSION['STUDENT_EMAILID']="";
	session_destroy();
echo "<SCRIPT> parent.location.replace('index.php');</SCRIPT>";
?>