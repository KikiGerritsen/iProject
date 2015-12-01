<?php
	//if(session_status() == PHP_SESSION_NONE){
		session_start();
	//}
	include 'resources/scripts/dbConnection.php';
	if($conn) {
			
		if (isset($_POST['action'])){	
			$name = $_POST['name'];
			$lastname = $_POST['lastname'];
			$adres = $_POST['adres'];
			$town = $_POST['town'];
			$zip = $_POST['zip'];
			$land = $_POST['land'];
			$birthdate = $_POST['birthdate'];
			$email = $_POST['email'];
			
			$username = $_SESSION['username'];
			
			$sql="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$username'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$stmt = sqlsrv_query($conn, $sql, $params, $options);
			
			$rowCount = sqlsrv_num_rows($stmt);
			
			if($rowCount == 1){
				$sqlUpdate = "UPDATE GEBRUIKER SET Voornaam='$name', Achternaam='$lastname', Adresregel='$adres', Postcode='$zip', Plaatsnaam='$town', Land='$land', GeboorteDag='$birthdate', Mailbox='$email' WHERE GEBRUIKERSNAAM='$username'";
				$stmtUpdate = sqlsrv_prepare($conn, $sqlUpdate);
				sqlsrv_execute($stmtUpdate);			
				
				header ('location: profiel.php');
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
					<td colspan="2"><h1>Gegevens veranderen</h1></td>
					</tr>
					<form method = "POST" action ="changeData.php">
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Voornaam</p></td> 
						<td><input name="name" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Achternaam</p></td> 
						<td><input name="lastname" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Adres</p></td>
						<td><input name="adres" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Postcode</p></td>
						<td><input name="zip" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Woonplaats</p></td>
						<td><input name="town" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Land</p></td>
						<td><input name="land" type="text" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>Geboorte datum</p></td>
						<td><input name="birthdate" type="date" required ></td>
					</tr>
					<tr>
						<td class = "tekstUitlijningInloggen"><p>E-mail</p></td>
						<td><input name="email" type="email" required ></td>
					</tr>
					<tr><td></td>
						<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Pas gegevens aan"></td>
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