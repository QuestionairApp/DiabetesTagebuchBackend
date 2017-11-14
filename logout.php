<?php
	session_start();
	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['gebdat']);
	header('Location: http://mkservices.de/diabetes/');
?>