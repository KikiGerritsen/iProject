<?php
	session_start();
	include 'resources/scripts/dbConnection.php';
	if($conn) {
		if (isset($_POST['action'])){
			if ($_POST['action'] == "Login"){
				// username and password sent from form 
				$username = $_POST['Gebruikersnaam']; 
				$password = md5($_POST['Wachtwoord']);
				
				$sql="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$username' and WACHTWOORD='$password' and ISNONACTIEF = 0";
				$params = array();
				$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
				$stmt = sqlsrv_query($conn, $sql, $params, $options);
				
				$rowCount = sqlsrv_num_rows($stmt);
				
				if($rowCount == 0){
					echo "<script type='text/javascript'>alert('Gebruikersnaam/wachtwoord komen niet overeen of u heeft uw account nog niet geactiveerd. Probeer opnieuw, registreer of kijk in uw mail/spam voor de activatie mail.');</script>";
				} else if ($rowCount >= 1){
					$_SESSION['username'] = $username;	
					$_SESSION['password'] = $password;
					header ('location: index.php');
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
			<div id="content">
			
				<div class="homepageBlokLogin">
				<table>
					<tr>
					<td><h1>Log in</h1></td>
					</tr>
					<form method = "POST" action ="inloggen.php">
					<tr>
						<td class = "tekstUitlijningInloggen">Gebruikersnaam</td> 
						<td><input name="Gebruikersnaam" type="text" placeholder="Gebruikersnaam..."></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen">Wachtwoord</td>
						<td><input name="Wachtwoord" type="password" placeholder="Wachtwoord..."></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><input TYPE="checkbox" NAME="Onthouden" ID="Onthouden" VALUE="Onthouden"><div class ="tekstgroteVeranderen">Onthouden</div></td>
						<td id = "tekstUitlijningWwVergeten"><a href = "wwVergeten.php"><div class = "tekstgroteVeranderen">Wachtwoord vergeten?</div></a></td>
					</tr>
					<tr>
						<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Login"></td>
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