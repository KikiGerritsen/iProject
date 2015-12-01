<?php 
	include 'resources/scripts/dbConnection.php';
	//if(session_status() == PHP_SESSION_NONE){
		session_start();
	//}
	if ($conn){			
		$user = $_SESSION['username'];
		$sqlCheck = "SELECT * FROM Gebruiker WHERE Gebruikersnaam='$user'";
		$stmt = sqlsrv_query($conn, $sqlCheck); 
		
		$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		
		$usersName = $row['Gebruikersnaam'];
		$name = $row['Voornaam'];
		$lastName = $row['Achternaam'];
		$adres = $row['Adresregel'];
		$zip = $row['Postcode'];
		$town = $row['Plaatsnaam'];
		$land = $row['Land'];
		$birthDay = $row['GeboorteDag'];
		$email = $row['Mailbox'];
		$currentPassword = $row['Wachtwoord'];
		$wachtwoord = "wachtwoord";
		
		$sqlCheckVerkoper = "SELECT * FROM Verkoper WHERE Gebruiker='$user'";
		$stmtVerkoper = sqlsrv_query($conn, $sqlCheckVerkoper); 
		
		$rowVerkoper = sqlsrv_fetch_array($stmtVerkoper, SQLSRV_FETCH_ASSOC);
		$salesMan = $rowVerkoper['Gebruiker'];		
		$payment = $rowVerkoper['ControleOptie'];		
		if($salesMan == NULL){
			$setCheckbox = "";
			$verkoper = false;
		} else {
			$verkoper = true;
			$setCheckbox = "checked='checked'";
		}
		if($payment == "Post"){
			$payment = "Bank rekening";
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
			<div id="content">
				<div class="profielBlok">
					<?php echo '<h1>'.$name.' '.$lastName.'</h1> '; ?>
					<form action="" method="POST">
						<div class="omschrijving">
						<table class="tableProfiel">
							<tr>
								<th colspan ="2">Log in gegevens</th><th></th>
							</tr>
							<tr>
								<td><p>Gebruikersnaam:</p> </td><td> <input type="text" name="gebruikersnaam" placeholder="<?php echo $usersName; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>Wachtwoord:</p> </td><td> <input type="password" name="wachtwoord" value="<?php echo $wachtwoord; ?>" disabled ></td>
							</tr>
							<tr>
								</td><td><td><a class="right" href="changePass.php">Wijzig wachtwoord</a></td>
							</tr>
						</table>
						</div>
						<div class="omschrijving">
						<table class="tableProfiel">
							<tr>
								<th colspan ="2">Algemene gegevens<th><th></th>
							</tr>
							<tr>
								<td><p>Adres:</p> </td><td> <input type="text" name="adres" placeholder="<?php echo $adres; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>Postcode:</p> </td><td> <input type="text" name="postcode" placeholder="<?php echo $zip; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>Woonplaats:</p> </td><td> <input type="text" name="woonplaats" placeholder="<?php echo $town; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>Land:</p> </td><td> <input type="text" name="land" placeholder="<?php echo $land; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>Geboorte datum:</p> </td><td> <input type="text" name="geboortedatum" placeholder="<?php echo $birthDay; ?>" disabled></td>
							</tr>
							<tr>
								<td><p>E-mail:</p> </td><td> <input type="text" name="email" placeholder="<?php echo $email; ?>" disabled></td>
							</tr>
							<tr>
								</td><td><td><a class="right" href="changeData.php">Wijzig gegevens</a></td>
							</tr>
						</table>
						</div>
						<div class="omschrijving">
						<table class="tableProfiel">
							<tr>
								<th colspan ="2">Telefoon gegevens</th><th></th>
							</tr>
							<tr>
								<td><p>Telefoon:</p></td> </td><td>
							</tr>
							<tr>
								<?php		//telefoon gebruiker
									$sqlTel = "SELECT * FROM Gebruikerstelefoon where Gebruiker='$user'";
									$paramsTel = array();
									$optionsTel = array("Scrollable"=> SQLSRV_CURSOR_KEYSET);
									$stmtTel = sqlsrv_query($conn, $sqlTel, $paramsTel, $optionsTel); 
									
									$rowCountTel = sqlsrv_num_rows($stmtTel);
									
									$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
									$getFirstEnd = end(explode('?', $actual_link));
									$getSecondEnd = substr($getFirstEnd, 0, 6);
									
									if ($getSecondEnd =='remove'){
										$end = end(explode('?remove=', $actual_link));
									
										$sqlDelete = "DELETE FROM Gebruikerstelefoon WHERE Telefoonnummer = '$end' AND Gebruiker = '$user'";
										$stmtDelete = sqlsrv_prepare($conn, $sqlDelete);
										sqlsrv_execute($stmtDelete);			
									}
									while($rowTel = sqlsrv_fetch_array($stmtTel, SQLSRV_FETCH_ASSOC)){
										$Teltel = $rowTel['Telefoonnummer'];
										echo '<tr> </td><td><td><input type="text" name="telefoon" placeholder="'.$Teltel.'" disabled><a style="float: right;" href="?remove='.$Teltel.'">Verwijder&nbsp;</a></td></tr>';
										//$counter ++;
										//if ($counter == $rowCountTel || $counter <= 1){
										//}
									}
									echo '<tr><td> </td><td><a class="right" href="addPhoneNumber.php">Telefoonnummer toevoegen</a></td></tr>';

								?>								
							</tr>
						</table>
						</div>
						<div class="omschrijving">
						<table class="tableProfiel">
							<tr>
								<th colspan ="2">Verkoop gegevens</th><th></th>
							</tr>
							<tr>
								<td><p>Verkoper </p></td><td> <input type="checkbox" disabled="disabled" <?php echo $setCheckbox; ?>></td>
							</tr>
							<tr>
								<td><p>Betaalwijze van de koper: </p></td><td> <input type="text" name="bank" placeholder="<?php if ($payment == "Post"){ echo "Bankrekening"; }else{ echo $payment;} ?>" disabled></td>
							</tr>
							<tr>
								<td> </td><td><?php if ($verkoper == true){echo "<a class='right' href='editData.php'>Wijzig bankgegevens</a>";} if ($verkoper == false){ echo "<a href='addSalesMan.php'>Meld mij aan als verkoper</a>";}?></td>
							</tr>
						</table>
						</div>
					</form>
				</div>
				
				<div class="profielBlok">
					<h1>Mijn veilingen</h1>	
						<table class="tableHeader">
							<tr>
								<th>Productnaam</th>
								<th>Bieden vanaf</th>
								<th>Hoogste bod</th>
								<th>Afgelopen</th>
							</tr>
						</table>
					<div class="scroll">
						<?php echo $createRow; ?>
						<table class="scrollTable" cellspacing="0">
							<?php //Veilingen gebruiker
								$sqlVeiling = "SELECT * FROM Voorwerp WHERE Verkoper='$user'";
								$stmtVeiling = sqlsrv_query($conn, $sqlVeiling); 
		
								while($rowVeiling = sqlsrv_fetch_array($stmtVeiling, SQLSRV_FETCH_ASSOC)){
									$productName = $rowVeiling['Titel'];
									$startPrice = $rowVeiling['Startprijs'];
									$highestBod = $rowVeiling['Verkoopprijs'];
									$closed = $rowVeiling['VeilingGesloten'];
									$salesMan = $rowVeiling['Verkoper'];
									$voorwerpnummer = $rowVeiling['Voorwerpnummer'];
									if ($closed == 'Nee'){
										$checked = '';
									} else {
										$checked = "checked";
									}
									echo '<tr><td><a href="productDetailPagina.php?id='.$voorwerpnummer.'">'. $productName .'</a></td><td><p>'. $startPrice .'</p></td><td>'. $hightestBod .'</td><td><INPUT TYPE=CHECKBOX NAME="jaofnee" '.$checked.' disabled></td></tr>';
								}
							?>
						</table>
					</div>
				</div>
				<div class="profielBlok">
					<h1>Mijn biedgeschiedenis</h1>
						<table class="tableHeader">
							<tr>
								<th width="271px">Productnaam</th>
								<th width="155px">Verkoper</th>
								<th>Mijn bod</th>
								<th width="20px">Voltooid</th>
							</tr>
						</table>
					<div class="scroll">
						<table class="scrollTable" cellspacing="0">
							<?php //biedgeschiedenis gebruiker
								$sqlHistory = "SELECT * FROM Bod B INNER JOIN Voorwerp V ON B.Voorwerp=V.Voorwerpnummer WHERE b.gebruiker='$user'";
								$stmtHistory = sqlsrv_query($conn, $sqlHistory); 
								
								while($rowHistory = sqlsrv_fetch_array($stmtHistory, SQLSRV_FETCH_ASSOC)){
									$HproductName = $rowHistory['Titel'];
									$HstartPrice = $rowHistory['Bodbedrag'];
									$HhighestBod = $rowHistory['Verkoopprijs'];
									$Hclosed = $rowHistory['VeilingGesloten'];
									$HsalesMan = $rowHistory['Verkoper'];
									$Hvoorwerpnummer = $rowHistory['Voorwerpnummer'];
									if ($Hclosed == 'Nee'){
										$Hchecked = '';
									} else {
										$Hchecked = "checked";
									}
									echo '<tr><td width="250px"><a href="productDetailPagina.php?id='.$Hvoorwerpnummer.'">'. $HproductName .'</a></td><td width="200px"><a href="verkoperDetails.php?verkoperID='. $HsalesMan .'">'. $HsalesMan .'</a></td><td class="maxwidth"><p>'. $HstartPrice .'</p></td><td width="100"><INPUT TYPE=CHECKBOX NAME="jaofnee" '.$Hchecked.' disabled></td></tr>';
								}
							?>
						</table>
					</div>
				</div>
				<div id="pageextender">
					&nbsp;
				</div>
			</div>
			&nbsp;
		</div>
	</div>
  </body>
</html>