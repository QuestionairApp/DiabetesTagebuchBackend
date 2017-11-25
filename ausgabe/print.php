<?php

//Einbinden der PHPExcel-Klassen
set_include_path(get_include_path() . PATH_SEPARATOR . './Classes');
include 'PHPExcel/IOFactory.php';

//Eingeloggt?
session_start();
if(!isset($_SESSION['name'])){
	header('Location: http://mkservices.de/diabetes/login.php');
}

//Herstellen der Datenbankverbindung
$DBH=new PDO("mysql:host=rdbms.strato.de;dbname=DB2465298", "U2465298", "Abraham1");

//Einlesen der Post-Werte
$start=$_POST["anfang"];
$ende=$_POST["ende"];
if($_POST["bz"]==1){
	$bz="Blutzucker ja";
} else {
	$bz="Blutzucker nein";
}

if($_POST["bd"]==1){
	$bd="Blutdruck ja";
} else {
	$bd="Blutdruck nein";
}

//Ermitteln der ersten KW
$anf_arr=explode("-", $start);
$kw=date("W",mktime(0,0,0,$anf_arr[1], $anf_arr[2], $anf_arr[0]));

//Erzeugen des PHPExcel-Objekts aus der Vorlage
$objPHPExcel=PHPExcel_IOFactory::load("vorlage.xlsx");
$objWorksheet=$objPHPExcel->getActiveSheet();
$objWorksheet->getCell("C3")->setValue($_SESSION["name"]);
$objWorksheet->getCell("K3")->setValue($_SESSION["gebdat"]);
$sc=$objWorksheet->copy();
$clonedSheet=clone $sc;
$clonedSheet->setTitle($kw." KW");
$clonedSheet->getCell("O3")->setValue($start);


//Auslesen der Daten aus der Datenbank und schreiben in die Excel-Tabelle
$sql="SELECT datum, zeit, username, bz, ie, ke, lantus FROM dia_blutzucker WHERE datum>=:start AND datum<=:ende AND username=:user ORDER BY datum ASC, zeit ASC";
$stmt=$DBH->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_OBJ);
$stmt->bindParam(":start", $start, PDO::PARAM_STR);
$stmt->bindParam(":ende", $ende, PDO::PARAM_STR);
$stmt->bindParam(":user", $_SESSION["username"], PDO::PARAM_STR);
if($stmt->execute()){
	$result=$stmt->fetchAll();
	foreach($result as $res){
		$datarr=explode("-",$res->datum);
		$aktkw=date("W", mktime(0,0,0, $datarr[1], $datarr[2], $datarr[0]));
		$dow=date("w", mktime(0,0,0, $datarr[1], $datarr[2], $datarr[0]));
		if($dow==1){
			if($aktkw>$kw){
				$endarr=explode("-",$res->datum);
				$endtime=mktime(0,0,0,$endarr[1], $endarr[2], $endarr[0])-3600*24;
				$clonedSheet->getCell("S3")->setValue(date("Y-m-d", $endtime));
				$objPHPExcel->addSheet($clonedSheet);
				$clonedSheet=clone $objPHPExcel->getSheetByName("Tabelle1");
				$morgen=mktime(0,0,0,$datarr[1], $datarr[2], $datarr[0]);
				$clonedSheet->getCell("O3")->setValue(date("Y-m-d", $morgen));
				$kw=$aktkw;
				$clonedSheet->setTitle($kw." KW");
			}
		}
		$zeitarr=explode(":", $res->zeit);
		$spalte=getZeitSpalte($zeitarr[0]);
		if($dow==0) $dow=7;
		$zeile=$dow*3+3;
		//echo $res->datum." - ".$res->zeit." - ".$spalte.$zeile." --- \n";
		$clonedSheet->getCell($spalte.$zeile)->setValue($res->bz);
		$zeile++;
		$clonedSheet->getCell($spalte.$zeile)->setValue($res->ke);
		$zeile++;
		$clonedSheet->getCell($spalte.$zeile)->setValue($res->ie."/".$res->lantus);
	}
	//Hier abspeichern des PHPExcel-Objekts und Ausgabe des Dateinamens
	$objPHPExcel->addSheet($clonedSheet);
	$sheetIndex=$objPHPExcel->getIndex($objPHPExcel->getSheetByName("Tabelle1"));
	$objPHPExcel->removeSheetByIndex($sheetIndex);
	$filename=$_SESSION["username"]."-".$start."-".$ende.".xlsx";
	$filename=$_SESSION["username"].".xlsx";
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save($filename);
	echo $filename;
} else {
	echo "Fehler: ".$stmt->errorCode();
} 

function getZeitSpalte($stunde){
	switch($stunde){
		case "00":
			$spalte= "C";
			break;
		case "01":
			$spalte="D";
			break;
		case "02":
			$spalte="E";
			break;
		case "03":
			$spalte="F";
			break;
		case "04":
			$spalte="G";
			break;
		case "05":
			$spalte="H";
			break;
		case "06":
			$spalte="I";
			break;
		case "07":
			$spalte="J";
			break;
		case "08":
			$spalte="K";
			break;
		case "09":
			$spalte="L";
			break;
		case "10":
			$spalte="M";
			break;
		case "11":
			$spalte="N";
			break;
		case "12":
			$spalte="O";
			break;
		case "13":
			$spalte="P";
			break;
		case "14":
			$spalte="Q";
			break;
		case "15":
			$spalte="R";
			break;
		case "16":
			$spalte="S";
			break;
		case "17":
			$spalte="T";
			break;
		case "18":
			$spalte="U";
			break;
		case "19":
			$spalte="V";
			break;
		case "20":
			$spalte="W";
			break;
		case "21":
			$spalte="X";
			break;
		case "22":
			$spalte="Y";
			break;
		case "23":
			$spalte="Z";
			break;
	}
	return $spalte;
}
?>
