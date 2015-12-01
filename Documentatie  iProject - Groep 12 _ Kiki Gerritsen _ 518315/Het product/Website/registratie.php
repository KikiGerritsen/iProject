<?php
	include "resources/scripts/dbConnection.php";
	
	error_reporting(E_ALL);
	
	function checkLength($min,$max,$name,$tekst){
		if(strlen($name) < $min || strlen($name) > $max){ 
			echo "<p style= 'font-size: 11px; color: red; float: right;'>".$tekst."</p>"; 
		}
	}
	
	$gebruikersnaam 	= $_POST['Gebruikersnaam'];
	$voornaam 	 		= $_POST['Voornaam'];
	$achternaam  		= $_POST['Achternaam'];
	$straatnaam  		= $_POST['Straatnaam'];
	$huisnummer  		= $_POST['Huisnummer'];
	$postcode    		= $_POST['Postcode'];
	$woonplaats			= $_POST['Woonplaats'];
	$land				= $_POST['Land'];
	
	$leeftijd	  		= $_POST['Leeftijd'];			
	$email				= $_POST['E-mail'];
	
	session_start();
	$_SESSION['mail'] 	= $email;
	
	$vraag				= '1';			
	$leesvraagnummer	= "SELECT Vraagnummer FROM vraag WHERE Tekstvraag = '$vraag'";			
	$vraagwaarde		= sqlsrv_query( $conn, $leesvraagnummer);
	
	$nummerVraag 		= preg_replace('/Resource id #/', '', $vraagwaarde);
	
	$verkoper			= 'nee';
	$wachtwoord			= $_POST['Wachtwoord'];	
	$antwoordtekst		= $_POST['Antwoordtekst'];
	$nonactief			= 1;

	$bevestigWachtwoord	= $_POST['Bevestig_Wachtwoord'];
	$BevestigEmail		= $_POST['BevestigEmail'];
	$Telefoon			= $_POST['Telefoon'];

	$error = false;
		
	$sqlUser="SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM='$gebruikersnaam'";
	$paramsUser = array();
	$optionsUser = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
	$stmtUser = sqlsrv_query($conn, $sqlUser, $paramsUser, $optionsUser);
	$rowCountUser = sqlsrv_num_rows($stmtUser);
	
	if($rowCountUser >= 1){
		$messageUser = "Gebruikersnaam bestaat al.";
		$error = true;
	}
	
	$sqlMail="SELECT * FROM GEBRUIKER WHERE MAILBOX='$email'";
	$paramsMail = array();
	$optionsMail = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
	$stmtMail = sqlsrv_query($conn, $sqlMail, $paramsMail, $optionsMail);
	$rowCountMail = sqlsrv_num_rows($stmtMail);
	
	if($rowCountMail >= 1){
		$messageMail = "E-mail adres bestaat al.";
		$error = true;
	}
	
	if(strlen($gebruikersnaam) < 5 || strlen($gebruikersnaam) > 20){
		$error = true;
	}
	if(strlen($wachtwoord) < 5 || strlen($wachtwoord)> 15){
		$error = true;
	}
	if(strlen($voornaam) < 2 || strlen($voornaam)> 20){
		$error = true;
	}
	if(strlen($achternaam) < 2 || strlen($achternaam)> 20){
		$error = true;
	}
	if(strlen($straatnaam) < 2 || strlen($straatnaam)> 45){
		$error = true;
	}
	if(strlen($huisnummer) < 1 || strlen($huisnummer)> 5){
		$error = true;
	}
	if(strlen($postcode) < 6 || strlen($postcode)> 7){
		$error = true;
	}
	if(strlen($woonplaats) < 2 || strlen($woonplaats)> 30){
		$error = true;
	}
	if(strlen($land) < 2 || strlen($land)> 25){
		$error = true; 
	}
	if($bevestigWachtwoord != $wachtwoord){
		$error = true;
	}
	if($email != $BevestigEmail){
		$error = true;      
	}
	
	if ($error == false){
		//voor het invoeren van de gebruiker
		$stmt = "INSERT INTO Gebruiker VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$args = array($gebruikersnaam,$voornaam,$achternaam,$straatnaam." ".$huisnummer,$postcode,$woonplaats,$land,$leeftijd,$email,md5($wachtwoord),$nummerVraag,$verkoper,$antwoordtekst,$nonactief);
		$result = sqlsrv_query( $conn, $stmt, $args);
		
		// Voor het invoeren van de gebruikerstelefoon
		$stmtGT = "INSERT INTO Gebruikerstelefoon VALUES (?,?)";
		$argsGT = array($gebruikersnaam, $Telefoon);
		$resultGT = sqlsrv_query( $conn, $stmtGT, $argsGT);
		
		//if (!$result) echo "Query kan niet worden uitgevoerd\n";
		sqlsrv_free_stmt($result);
		sqlsrv_free_stmt($resultGT);
		sqlsrv_close ($conn);
		//header ('location: index.php');
	
		// the message
		$msg = "Als u op deze link drukt wordt u account geactiveerd, en u wordt direct ingelogd.\nhttp://iproject12.icasites.nl/userregistered.php?".$gebruikersnaam."";

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);

		// send email
		mail( $email,"My subject",$msg);
		header('Location: registered.php');
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
			
				<div class="registratie">
					<h1>Registreren</h1>
						<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
							<table id="tableregistratie">
								<tr>
									<td>Gebruikersnaam*
										<?php 
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 0, 1,$gebruikersnaam, $messageUser);
												checkLength( 5, 20,$gebruikersnaam, 'Lengte (5-20) tekens');
											}
										} 
										
										?> 
									</td> 
									<td><input type="text" name="Gebruikersnaam" placeholder="Gebruikersnaam" value ="<?php if(isset($_POST['Gebruikersnaam'])){ echo htmlentities($_POST['Gebruikersnaam']);} ?>" required ></td>
								</tr>
								<tr>
									<td>Wachtwoord*
										<?php										
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 5, 15,$wachtwoord, 'Lengte (5-15) tekens');
											}
										} 
										
										?> 
									</td>
									<td><input type="password" name="Wachtwoord" placeholder="Wachtwoord" required></td>
								</tr>
								<tr>
									<td>Bevestig Wachtwoord*
									<?php										
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												if ( $wachtwoord != $bevestigWachtwoord){
													echo "<p style= 'font-size: 11px; color: red; float: right;'>Wachtwoord komt niet overeen</p>";
													$error = true;
												}
											}
										}
									?>
										
									</td>
									<td><input type="password" name="Bevestig_Wachtwoord" placeholder="Bevestig Wachtwoord" required></td>
								</tr>
								<tr>
								<tr>
									<td>Vraag*</td>
									<td>
										<select name="sometext" >
											<?php
												$sql =	'select Tekstvraag FROM Vraag';
												$connect = sqlsrv_query($conn, $sql );

												$style='style="background-color: #DCDCDC;"';
												while(($row = sqlsrv_fetch_array($connect, SQLSRV_FETCH_ASSOC))){
												
													echo'<option >'.$row['Tekstvraag'].'</option>';
													
												}
											?>	
										</select>	
									</td>
								</tr>
								<tr>
									<td>Antwoordtekst*
										<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer" )
											{
												checkLength( 6, 15,$antwoordtekst, 'Lengte (6-20) tekens');
											}
										} 
										
										?>
									</td>
									<td><input type="text" name="Antwoordtekst" placeholder="Antwoordtekst" value ="<?php if(isset($_POST['Antwoordtekst'])){ echo htmlentities($_POST['Antwoordtekst']);} ?>" required></td>
								</tr>
								<tr>
									<td colspan="2" style="height = 50px; border-bottom: solid 2px #060">
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>Voornaam*
										<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 2, 20,$voornaam, 'Lengte (2-20) tekens');
											}
										} 
										
										?>
									</td>
									<td><input type="text" name="Voornaam" placeholder="voornaam" value ="<?php if(isset($_POST['Voornaam'])){ echo htmlentities($_POST['Voornaam']);} ?>" required></td>
								</tr>
								<tr>
									<td>Achternaam*
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 2, 20,$achternaam, 'Lengte (2-20) tekens');
											}
										} 
										
									?>
									</td>
									<td><input type="text" name="Achternaam" placeholder="Achternaam" value ="<?php if(isset($_POST['Achternaam'])){ echo htmlentities($_POST['Achternaam']);} ?>" required></td>
								</tr>
								<tr>
									<td>Geboortedatum*
									
									<?php 
									
									if(isset ($_POST['action'])){ 
									if($_POST['action'] == "registreer")
									{ 
									$date1 	= date('Y/m/d');
									$diff 	= abs(strtotime($date1) - strtotime($leeftijd));
									$years = floor($diff / (365*60*60*24));
									if( $years < 18)
									{ 
									echo "<p style= 'font-size: 11px; color: red; float: right;'>De minimale leeftijd bedraagt 18 jaar</p>"; $error = true;} else{echo"";}}} 
									?> 
									</td>															
									<td><input type="date" name="Leeftijd" placeholder="Geboortedatum" value ="<?php if(isset($_POST['Leeftijd'])){ echo htmlentities($_POST['Leeftijd']);} ?>"  required></td>
								</tr>
								<tr>
									<td colspan="2" style="height = 50px; border-bottom: solid 2px #060">
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>Straatnaam*									
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 2, 45,$straatnaam, 'Lengte (2-45) tekens');
											}
										} 
										
									?>
									</td>
									<td><input type="text" name="Straatnaam" placeholder="Straatnaam" value ="<?php if(isset($_POST['Straatnaam'])){ echo htmlentities($_POST['Straatnaam']);} ?>" required></td>
								</tr>
								<tr>
									<td>Huisnummer*
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 1, 5,$huisnummer, 'Lengte (1-5) tekens');
											}
										} 
										
									?>
									</td>
									<td><input type="text" name="Huisnummer" placeholder="Huisnummer" value ="<?php if(isset($_POST['Huisnummer'])){ echo htmlentities($_POST['Huisnummer']);} ?>" required></td>
								</tr>
								<tr>
									<td>Postcode*
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 6, 7,$postcode, 'Lengte (6-7) tekens');
											}
										} 
										
									?>									
									</td>
									<td><input type="text" name="Postcode" placeholder="Postcode" value ="<?php if(isset($_POST['Postcode'])){ echo htmlentities($_POST['Postcode']);} ?>" required></td>
								</tr>
								<tr>
									<td>Woonplaats*
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 2, 30,$woonplaats, 'Lengte (2-30) tekens');
											}
										} 
										
									?>
									</td>
									<td><input type="text" name="Woonplaats" placeholder="Woonplaats" value ="<?php if(isset($_POST['Woonplaats'])){ echo htmlentities($_POST['Woonplaats']);} ?>" required></td>
								</tr>
								<tr>
									<td>Land*
									<?php 
										
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 2, 25,$land, 'Lengte (2-20) tekens');
											}
										} 
										
									?>
									</td>
									<td><input type="text" name="Land" placeholder="Land" value ="<?php if(isset($_POST['Land'])){ echo htmlentities($_POST['Land']);} ?>" required></td>
								</tr>
								<tr>
									<td colspan="2" style="height = 50px; border-bottom: solid 2px #060">
									&nbsp;
									</td>
								</tr>								
								<tr>
									<td>E-mail*
									<?php
			
										if(isset ($_POST['action'])){ if($_POST['action'] == "registreer")
											{
												checkLength( 0, 1,$email, $messageMail);
											}
										} 
										if ($email != $BevestigEmail){
											echo "<p style= 'font-size: 11px; color: red; float: right;'>email komen niet overeen</p>";
											$error = true;
										}
									?>
									</td>
									<td><input type="email" name="E-mail" placeholder="E-mail" value ="<?php if(isset($_POST['E-mail'])){ echo htmlentities($_POST['E-mail']);} ?>" required></td>
								</tr>
								<tr>
									<td>Bevestig E-mail*</td>
									<td><input type="email" name="BevestigEmail" placeholder="Bevestig E-mail" value ="<?php if(isset($_POST['BevestigEmail'])){ echo htmlentities($_POST['BevestigEmail']);} ?>"></td>
								</tr>
								<tr>
									<td>Telefoon*</td>
									<td><input type="tel" name="Telefoon" placeholder="Telefoon" value ="<?php if(isset($_POST['Telefoon'])){ echo htmlentities($_POST['Telefoon']);} ?>" required></td>
								</tr>
								<tr>
									<td colspan="2" style="height = 50px; border-bottom: solid 2px #060">
									&nbsp;
									</td>
								</tr>	
								<tr>
									<td style="float:left;"><input type="submit" name="action" value="registreer"></td>
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