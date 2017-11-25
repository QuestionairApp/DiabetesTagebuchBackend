<?php
session_start();
if(!isset($_SESSION['name'])){
	header('Location: http://mkservices.de/diabetes/login.php');
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Diabetes-Tagebuch</title>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link href="css/font/css/open-iconic-bootstrap.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="navbar-brand">Diabetes-Auswertung</div>
	<ul class="navbar-nav mr-auto">
		<li class="nav-item">
		<a class="nav-link" href="logout.php">Logout</a>
		</li>
	</ul>
</nav>
<div class="container">
	<div class="row">
		<div class="col-sm">
			Anfang: <span id="anfangsdatum"></span></span><div id="datepicker_start"></div>
		</div>
		<div class="col-sm">
		</div>
		<div class="col-sm">
			Ende: <span id="endedatum"></span></span><div id="datepicker_end"></div>
		</div>
  </div>
  <div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input" id="blutzucker_chk" type="checkbox" value="" checked>
				Blutzuckerwerte
			</label>
		</div>
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input" id="blutdruck_chk" type="checkbox" value="" checked>
				Blutdruckwerte
			</label>
		</div>
	</div>
	<div class="col-sm"></div>
  </div>
  <div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<button type="button" class="btn btn-outline-primary" id="printTable">Tabelle herunterladen</button>
	</div>
	<div class="col-sm"></div>
  </div>
  <div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<span id="downloadlink"></span>
	</div>
	<div class="col-sm"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
$.fn.datepicker.dates['de'] = {
    days: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"],
    daysShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
    daysMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
    months: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
    monthsShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
    today: "Heute",
    clear: "Löschen",
    format: "dd.mm.yyyy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 1
};
$("#datepicker_start").datepicker({
	language: "de"
});
$("#datepicker_end").datepicker({
	language: "de"
});
$("#datepicker_start").on("changeDate", function(e){
	console.log(e);
	var jahr=e.date.getFullYear();
	var monat=e.date.getMonth()+1;
	if(monat<10) monat="0"+monat;
	var tag=e.date.getDate();
	if(tag>9){
		startdatum=jahr+"-"+monat+"-"+tag;
	} else {
		startdatum=jahr+"-"+monat+"-0"+tag;
	}
	$("#anfangsdatum").text(startdatum);
});
$("#datepicker_end").on("changeDate", function(e){
	console.log(e);
	var jahr=e.date.getFullYear();
	var monat=e.date.getMonth()+1;
	if(monat<10) monat="0"+monat;
	var tag=e.date.getDate();
	if(tag>9){
		enddatum=jahr+"-"+monat+"-"+tag;
	} else {
		enddatum=jahr+"-"+monat+"-0"+tag;
	}
	$("#endedatum").text(enddatum);
});
$(document).ready(function(){
	$.ajax({
		type: "POST",
		url: "getStartAndEndDate.php",
		success: function(data){
			var anfEnd=JSON.parse(data);
			var anfang=new Date(anfEnd.start);
			var ende=new Date(anfEnd.ende);
			$("#anfangsdatum").text(anfEnd.start);
			$("#endedatum").text(anfEnd.ende);
		},
		error: function(data){
			console.log(data);
		}
	});
	$("#printTable").click(function(){
		var start=$("#anfangsdatum").text();
		var ende=$("#endedatum").text();
		if($("#blutzucker_chk").prop("checked")){
			var bz=1;
		} else {
			bz=0;
		}
		if($("#blutdruck_chk").prop("checked")){
			var bd=1;
		} else {
			bd=0;
		}
		
		$.ajax({
			type: "POST",
			url: "ausgabe/print.php",
			data: {
				anfang: start,
				ende: ende,
				bz: bz,
				bd: bd
			},
			success: function(data){
				var link="<a href='ausgabe/"+data+"'>Download</a>"
				$("#downloadlink").html(link);
			},
			error: function(data){
				alert(data);
			}
		});
	});
});
</script>

</body>
</html>