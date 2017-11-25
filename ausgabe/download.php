<?php
$dirname=$_SERVER["DOCUMENT_ROOT"]."/services/diabetes/ausgabe/";
$filename=$_REQUEST["file"];
$type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
makeDownload($filename, $dirname, $type);

function makeDownload($file, $dir, $type){
	//header("Content-Type: ".$type);
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8;");
	//header($type);
	header("Content-Disposition: attachment; filename=\"$file\"");
	readfile($dir.$file);
echo $dir.$file;
}
?>