<?php
	session_start();
	$DBH=new PDO("mysql:host=rdbms.strato.de;dbname=DB2465298", "U2465298", "Abraham1");

	if(isset($_POST["username"])&&$_POST["username"]!=""){
		if(isset($_POST["password"])&&$_POST["password"]!=""){
			$password=base64_encode(hash("sha256",$_POST["password"], True));
			$sql="SELECT username, name, gebdat FROM dia_user WHERE username=:user AND password=:pwd";
			$stmt=$DBH->prepare($sql);
			$stmt->setFetchMode(PDO::FETCH_OBJ);
			$stmt->bindParam(":user", $_POST["username"], PDO::PARAM_STR);
			$stmt->bindParam(":pwd", $password, PDO::PARAM_STR);
			if($stmt->execute()){
				$result=$stmt->fetchAll();
				foreach($result as $res){
					$_SESSION['username'] = $res->username;
					$_SESSION['name']=$res->name;
					$_SESSION['gebdat']=$res->gebdat;
					header('Location: http://mkservices.de/diabetes/');
				}
			}
		}
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
<link rel="stylesheet" href="css/login.css">
<link href="css/font/css/open-iconic-bootstrap.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="Absolute-Center is-Responsive">
      <div id="logo-container"></div>
      <div class="col-sm-12 col-md-10 col-md-offset-1">
        <form action="" id="loginForm" method="POST">
          <div class="form-group input-group">
            <span class="input-group-addon"><span class="oi oi-person" title="person" aria-hidden="true"></span></i></span>
            <input class="form-control" type="text" name='username' placeholder="Benutzername"/>          
          </div>
          <div class="form-group input-group">
            <span class="input-group-addon"><span class="oi oi-lock-locked" title="person" aria-hidden="true"></span></span>
            <input class="form-control" type="password" name='password' placeholder="Passwort"/>     
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-def btn-block" value="Login">
          </div>
          <div class="form-group text-center">
            <a href="newuser.html">Noch keine Login-Daten?</a>
          </div>
        </form>        
      </div>  
    </div>    
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>