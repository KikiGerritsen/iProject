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
				//echo "<script type='text/javascript'>alert('Oude wachtwoord klopt niet. Probeer opnieuw of Registreer');</script>";
			} else if ($rowCount >= 1){
				$sqlUpdate = "UPDATE Verkoper SET Bank='$bank', Bankrekening='$bankAccount', ControleOptie='$insertControl', Creditcard='$creditcard' WHERE Gebruiker='$username'";
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
			<div id="content">
				<div class="homepageBlokLogin">
				<table>
					<tr>
					<td colspan="2"><h1>Gegevens wijzigen</h1></td>
					</tr>
					<tr>
						<td colspan="2"><p>Hoe wilt u betaald krijgen:</p></td></td>
					</tr>
					<tr>
						<form name="UserInformationForm" method="POST" action="#">
							<tr>
								<td><p>Bankrekening: </p></td><td><input name="bankOrCredit" type="radio" value="BankRekening" <?php if($_POST['bankOrCredit']=="BankRekening") echo "checked=checked"; $setBank=true?> ></td>
							</tr>
							<tr>
								<td><p>Creditcard: </p></td><td><input name="bankOrCredit" type="radio" value="Creditcard" <?php if($_POST['bankOrCredit']=="Creditcard") echo "checked=checked"; $setCreditcard=true?> ></td>
							 </tr><tr></tr><tr>
							  <td></td><td><input name="BtnSubmit" type="submit" value="Volgende"></td>
							</tr>
						</form>
					</tr>
					<form method = "POST" action ="editData.php">
					<?php 
						if ($formCredit==true){
							$formBank = false;
							echo '
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2"><p>Voer uw 16 cijferige creditcard nummer in.</p></td></tr>
							<tr>
								<td class = "tekstUitlijningInloggen"><p>Creditcard</p></td>
								<td><input name="creditcard" type="text"></td>
							</tr>
							<tr><td></td>
								<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Voeg toe"></td>
							</tr>
							';
						} else {
							echo '';
						}
						
						if($formBank==true){
							$formCredit = false;
							echo '
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td colspan="2"><p>Voer uw bank en rekeningnummer in.</p></td></tr>
							<tr>
								<td class = "tekstUitlijningInloggen"><p>Bank</p></td> 
								<td><input name="bank" type="text"></td>
							</tr>
							<tr>
								<td class = "tekstUitlijningInloggen"><p>Bankrekening</p></td>
								<td><input name="bankAccount" type="text" placeholder="Bijv. Rabobank"></td>
							</tr>
							<tr><td></td>
								<td id = "tekstUitlijningInlogKnop"><input TYPE="submit" NAME="action" VALUE="Voeg toe"></td>
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