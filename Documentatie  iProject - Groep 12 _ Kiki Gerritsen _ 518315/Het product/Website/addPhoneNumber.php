<?php
	//if(session_status() == PHP_SESSION_NONE){
		session_start();
	//}
	include 'resources/scripts/dbConnection.php';
	if($conn) {
			
		$username = $_SESSION['username'];
			
		if (isset($_POST['action'])){
			$phone = $_POST['newPhone'];
			$stmt = "INSERT INTO Gebruikerstelefoon VALUES (?,?)";
			$args = array($username,$phone);
			$result = sqlsrv_query( $conn, $stmt, $args);
			//echo $phone;
			header('location: profiel.php');
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
			
				<div class="homepageBlokLogin"><h1>Telefoonnummer toevoegen</h1>
					<form method = "POST" action ="#">
					<p>Voer uw toe te voegen telefoonnummer in de lege tekstbox in en druk op toevoegen.</p>
				<table>
					<tr><td>
						<?php 
							//telefoon gebruiker
							$sqlTell = "SELECT * FROM Gebruikerstelefoon where gebruiker='$username'";
							$paramsTell = array();
							$optionsTell = array("Scrollable"=> SQLSRV_CURSOR_KEYSET);
							$stmtTell = sqlsrv_query($conn, $sqlTell, $paramsTell, $optionsTell); 
							
							$rowCountTell = sqlsrv_num_rows($stmtTell);
							while($rowTell = sqlsrv_fetch_array($stmtTell, SQLSRV_FETCH_ASSOC)){
								$Teltell = $rowTell['Telefoonnummer'];
								echo '<tr><td><input type="text" name="PhoneNumbers" placeholder="'.$Teltell.'" disabled ></td></tr>';
								$counterl ++;
								if ($counterl == $rowCountTell || $counterl < 0){
									
								}
							}echo '<tr><td><input type="text" name="newPhone"></td></tr>';
						?></td>
					</tr>
					<tr>
						<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Toevoegen"></td>
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