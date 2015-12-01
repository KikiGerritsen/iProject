<?php
	session_start();
	include 'resources/scripts/dbConnection.php';
	if($conn) {
		// username and password sent from form 
		$email = $_SESSION['mail'];

		function curPageURL() {
			$pageURL = 'http';
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
		$end = end(explode('?', curPageURL()));
		//$decriptUser = md5($end)
		//Zet gebruiker op actief
		$sql="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$end'";
		$params = array();
		$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$stmt = sqlsrv_query($conn, $sql, $params, $options);
		
		$rowCount = sqlsrv_num_rows($stmt);
		
		if($rowCount == 0){
		//
		} else if ($rowCount >= 1){
			$sqlUpdate= "UPDATE gebruiker
						SET isNonActief = 0
						WHERE gebruikersnaam = '".$end."'";
			$stmtUpdate = sqlsrv_prepare($conn, $sqlUpdate);
			sqlsrv_execute($stmtUpdate);
			$_SESSION['username'] = $end;
			header ('location: index.php');			
		}
	}

?>
<!DOCTYPE html>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
    <meta charset="utf-8">
    <title>Eenmaal Andermaal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
	<div id="container">
		<?php
			include"resources/scripts/header.php";
			include"resources/scripts/menu.php";
		?>
		<div id="pagina">
			<?php
				include"resources/scripts/zoeken.php";
			?>
			<div id="content">
			
				<div class="homepageBlokLogin">
					<h1>Registratie voltooid</h1>
					<p>Uw registratie is voltooid. U kunt hier inloggen.</p>
				</div>
				&nbsp;
				<div id="pageextender">
				&nbsp;
				</div>
				
			</div>
			&nbsp;
		</div>
	</div>
  </body>
</html>