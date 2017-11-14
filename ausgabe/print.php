<?php
	echo "Hallo";
	echo "Wochentag des 12.11.2017 ist ".getDayOfWeek("14.11.2017");
	
	function getDayOfWeek($datum){
		$dat_arr=explode(".", $datum);
		$dat=strtotime($dat_arr[0]."-".$dat_arr[1]."-".$dat_arr[2]);
		$kw=date("w", $dat);
		return $kw;
	}
?>