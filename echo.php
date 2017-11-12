<?php
	$DBH=new PDO("mysql:host=rdbms.strato.de;dbname=DB2465298", "U2465298", "Abraham1");
	if(isset($_POST["username"])&&isset($_POST["passwd"])){
		$sql="SELECT name, gebdat, password FROM dia_user WHERE username='".$_POST["username"]."'";
		$stmt=$DBH->prepare($sql);
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		if($stmt->execute()){
			$result=$stmt->fetchAll();
			$cmp=strcmp(trim($result[0]->password), trim($_POST["passwd"]));
			if($cmp==0){
				$sql="INSERT INTO dia_blutdruck (username, datum, zeit, syst, diast, puls) VALUES(:username, :datum, :zeit, :syst, :diast, :puls)";
				$sql1="INSERT INTO dia_blutzucker (username, datum, zeit, bz, ke, ie, lantus) VALUES(:username, :datum, :zeit, :bz, :ke, :ie, :lantus)";
				$werte=json_decode($_POST["werte"]);
				$fehler=false;
				for($i=0;$i<count($werte);$i++){
					$datum=trim($werte[$i]->datum);
					$zeit=trim($werte[$i]->zeit);
					$bz=trim($werte[$i]->bz);
					$ke=trim($werte[$i]->ke);
					$ie=trim($werte[$i]->ie);
					$lantus=trim($werte[$i]->lantus);
					$syst=trim($werte[$i]->syst);
					$diast=trim($werte[$i]->diast);
					$puls=trim($werte[$i]->puls);
					if(strlen($datum)==0){
						continue;
					}
					if(strlen($syst)>0&&strlen($diast)>0&&strlen($puls)){
						$stmt=$DBH->prepare($sql);
						$stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
						$stmt->bindParam(":datum", $datum, PDO::PARAM_STR);
						$stmt->bindParam(":zeit", $zeit, PDO::PARAM_STR);
						$stmt->bindParam(":syst", $syst, PDO::PARAM_INT);
						$stmt->bindParam(":diast", $diast, PDO::PARAM_INT);
						$stmt->bindParam(":puls", $puls, PDO::PARAM_INT);
						if(!$stmt->execute()){
							$fehler=true;
						}
					} 
					if(strlen($bz)>0){
						$stmt=$DBH->prepare($sql1);
						$stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
						$stmt->bindParam(":datum", $datum, PDO::PARAM_STR);
						$stmt->bindParam(":zeit", $zeit, PDO::PARAM_STR);
						$stmt->bindParam(":bz", $bz, PDO::PARAM_STR);
						$stmt->bindParam(":ke", $ke, PDO::PARAM_STR);
						$stmt->bindParam(":ie", $ie, PDO::PARAM_STR);
						$stmt->bindParam(":lantus", $lantus, PDO::PARAM_STR);
						if(!$stmt->execute()){
							$fehler=true;
						}
					}
				}
				if($fehler){
					echo "Daten nicht gespeichert";
				} else {
					echo "OK";
				}
			} else {
				echo "Login failed";
			}
		} else {
			echo $stmt->errorCode();
		}
	} else {
		echo "Daten nicht gespeichert";
	}
?>