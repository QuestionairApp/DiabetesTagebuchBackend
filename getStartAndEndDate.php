<?php
	session_start();
	if(!isset($_SESSION['name'])){
		header('Location: http://mkservices.de/diabetes/login.php');
	}
	$startdat1="";
	$DBH=new PDO("mysql:host=rdbms.strato.de;dbname=DB2465298", "U2465298", "Abraham1");
	$sql="SELECT datum FROM dia_blutzucker WHERE gedruckt=0 ORDER BY datum ASC LIMIT 1";
	$stmt=$DBH->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $res){
		$startdat1=$res->datum;
	}
	$enddat1="";
	$sql="SELECT datum FROM dia_blutzucker WHERE gedruckt=0 ORDER BY datum DESC LIMIT 1";
	$stmt=$DBH->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $res){
		$enddat1=$res->datum;
	}
	$startdat2="";
	$sql="SELECT datum FROM dia_blutdruck ORDER BY datum ASC LIMIT 1";
	$stmt=$DBH->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $res){
		$startdat2=$res->datum;
	}
	$enddat2="";
	$sql="SELECT datum FROM dia_blutdruck ORDER BY datum DESC LIMIT 1";
	$stmt=$DBH->prepare($sql);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	$result=$stmt->fetchAll();
	foreach($result as $res){
		$enddat2=$res->datum;
	}
	$ret=array();
	if($startdat1<$startdat2){
		$ret["start"]=$startdat1;
	} else {
		$ret["start"]=$startdat2;
	}
	if($enddat1>$enddat2){
		$ret["ende"]=$enddat1;
	} else {
		$ret["ende"]=$enddat2;
	}
	echo json_encode($ret);
?>
