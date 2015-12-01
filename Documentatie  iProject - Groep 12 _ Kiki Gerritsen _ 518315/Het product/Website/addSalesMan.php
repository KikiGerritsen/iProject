<?php
	//if(session_status() == PHP_SESSION_NONE){
		session_start();
	//}
	include 'resources/scripts/dbConnection.php';
	if($conn) {
			
		if(isset($_POST['BtnSubmit'])){
			if($_POST['bankOrCredit']=="BankRekening"){
				$formBank=true;
				$formCredit = false;
				$control = 'Post';
			}
			if($_POST['bankOrCredit']=="Creditcard"){
				$formCredit=true;
				$formBank=false;
				$control = 'Creditcard';
			}
		}
		if (isset($_POST['action'])){				
			$username = $_SESSION['username'];
			
			if(isset($_POST['bank'])){
				$insertControl = 'Post';
				$bank = $_POST['bank'];
			} else {
				$bank = NULL;
			}
			
			if(isset($_POST['bankAccount'])){
				$insertControl = 'Post';
				$bankAccount = $_POST['bankAccount'];
			} else {
				$bankAccount = NULL;
			}
			
			if(isset($_POST['creditcard'])){
				$insertControl = 'Creditcard';
				$creditcard = $_POST['creditcard'];
			} else {
				$creditcard = NULL;
			}
			
			$sql="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$username'";
			$params = array();
			$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
			$stmt = sqlsrv_query($conn, $sql, $params, $options);
			
			$rowCount = sqlsrv_num_rows($stmt);
			
			if($rowCount == 0){
				echo "<script type='text/javascript'>alert('Oude wachtwoord klopt niet. Probeer opnieuw of Registreer');</script>";
			} else if ($rowCount >= 1){
				//echo "<script type='text/javascript'>alert('ingevoerd');</script>";
				$stmtInsert = "INSERT INTO Verkoper VALUES (?,?,?,?,?)";
				$argsInsert = array($username, $bank, $bankAccount, $insertControl, $creditcard);
				
				$resultInsert = sqlsrv_query( $conn, $stmtInsert, $argsInsert);		
				sqlsrv_free_stmt($resultInsert);
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
					<td colspan="2"><h1>Inschrijven verkoper</h1></td>
					</tr>
					<tr>
						<td colspan="2">Hoe wilt u betaald krijgen:</td></td>
					</tr>
					<tr>
						<form name="UserInformationForm" method="POST" action="#">
							<td colspan="2">
							  Bankrekening: <input name="bankOrCredit" type="radio" value="BankRekening" <?php if($_POST['bankOrCredit']=="BankRekening") echo "checked=checked"; $setBank=true?> >
							  Creditcard: <input name="bankOrCredit" type="radio" value="Creditcard" <?php if($_POST['bankOrCredit']=="Creditcard") echo "checked=checked"; $setCreditcard=true?> >
							  <br/><br/>
							  <input name="BtnSubmit" type="submit" value="Submit">
							</td>
						</form>
					</tr>
					<form method = "POST" action ="addSalesMan.php">
					<?php 
						if ($formCredit==true){
							$formBank = false;
							echo '
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2">Voer uw 16 cijferige creditcard nummer in.</td></tr>
							<tr>
								<td class = "tekstUitlijningInloggen">Creditcard</td>
								<td><input name="creditcard" type="text"></td>
							</tr>
							<tr>
								<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="addSale"></td>
							</tr>
							';
						} else {
							echo '';
						}
						
						if($formBank==true){
							$formCredit = false;
							echo '
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2">Voer uw bank en rekeningnummer in.</td></tr>
							<tr>
								<td class = "tekstUitlijningInloggen">Bank</td> 
								<td><input name="bank" type="text"></td>
							</tr>
							<tr>
								<td class = "tekstUitlijningInloggen">Bankrekening</td>
								<td><input name="bankAccount" type="text" placeholder="Bijv. Rabobank"></td>
							</tr>
							<tr>
								<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Voeg mij toe"></td>
							</tr>
							';
						} else {
							echo '';
						}
					?>
					</form>
				</table>
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