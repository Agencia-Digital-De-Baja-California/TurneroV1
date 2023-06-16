<?php

	session_start();

	if(isset($_SESSION['usuario']) && isset($_SESSION['password'])){

		session_destroy();

		header("location:login.php");

	}else{

		echo"Error";

	}

?>