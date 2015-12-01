<?php
	//if(session_status() == PHP_SESSION_NONE){
		session_start();
	//}
	include 'resources/scripts/dbConnection.php';
	if($conn) {
		if (isset($_POST['action'])){			
			$oldPass = md5($_POST['oldPass']);
			$newPass = md5($_POST['newPass']);
			$confirmPass = md5($_POST['confirmPass']);
			
			$username = $_SESSION['username'];
			
			$sql="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$username' and WACHTWOORD='$oldPass'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$stmt = sqlsrv_query($conn, $sql, $params, $options);
			
			$rowCount = sqlsrv_num_rows($stmt);
			
			if($rowCount == 0){
				echo "<script type='text/javascript'>alert('Oude wachtwoord klopt niet. Probeer opnieuw of Registreer');</script>";
			} else if ($rowCount >= 1){
				if($newPass != $confirmPass){
					echo "<script type='text/javascript'>alert('Wachtwoorden komen niet overeen. Probeer opnieuw of Registreer');</script>";
				} else if($newPass == $confirmPass){
					$sqlUpdate = "UPDATE GEBRUIKER SET WACHTWOORD='$newPass' WHERE GEBRUIKERSNAAM='$username'";
					$stmtUpdate = sqlsrv_prepare($conn, $sqlUpdate);
					sqlsrv_execute($stmtUpdate);						
					header ('location: profiel.php');
				}
			}
		} 
	sqlsrv_close($conn);
	} else {
		die( print_r( sqlsrv_errors(), true));
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
				<table>
					<tr>
					<td colspan="2"><h1>Wachtwoord veranderen</h1></td>
					</tr>
					<form method = "POST" action ="changePass.php">
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Oud wachtwoord</p></td> 
						<td><input name="oldPass" type="password"></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Nieuw wachtwoord</p></td>
						<td><input name="newPass" type="password"></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Bevestig wachtwoord</p></td>
						<td><input name="confirmPass" type="password"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
						<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Pas wachtwoord aan"></td>
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