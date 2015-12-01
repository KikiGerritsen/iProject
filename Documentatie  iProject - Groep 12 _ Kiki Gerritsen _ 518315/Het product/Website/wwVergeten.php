<?php
	session_start();
	include 'resources/scripts/dbConnection.php';
	if(isset($_POST['gebruikersnaam'])){
		$gebruiker=$_POST['gebruikersnaam'];
		$sql2 = "SELECT *
					FROM Gebruiker
					WHERE Gebruikersnaam = '".$gebruiker."'";
		$stmt2 = sqlsrv_query($conn, $sql2);
		$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
		$wachtwoord=rand(0,999999999);
		$msg = "Uw wachtwoord is: ".$wachtwoord."";
		$msg = wordwrap($msg,70);
		$email=$row2['Mailbox'];
		mail( $email,"Wachtwoord",$msg);
		$sql3="update Gebruiker
					set Wachtwoord = '".md5($wachtwoord)."'
				where Gebruikersnaam='".$gebruiker."'";
		$stmt3 = sqlsrv_query($conn, $sql3);
		$row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);
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
			<div id="content">
			
				<div class="homepageBlokLogin">
				<table>
					<tr>
					<td colspan=2><h1>Wachtwoord vergeten</h1></td>
					</tr>
					<form method = "POST" action ="wwVergeten.php">
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Gebruikersnaam</p></td> 
						<td><input name="gebruikersnaam" type="text" placeholder="Vul hier uw gebruikersnaam in..."></td>
					</tr>
					<tr>
						<td><input type="submit" value="Verzenden"></td>
					</tr>
				</table>
					</form>
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