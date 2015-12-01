<?php
	if(isset($_GET['id'])){
		$nummer = $_GET['id'];
	}
	if(isset($_SESSION['username'])){
		$ingelogdeGebruiker=$_SESSION['username'];
	}
	$where = "WHERE Voorwerpnummer = ".$nummer."";
	$sql = 'select V.Voorwerpnummer, V.Titel, V.Beschrijving, V.Startprijs, V.Betalingswijze, V.LooptijdeindeDag, V.looptijdEindeTijdstip, V.Verkoper, V.VeilingGesloten
				from Voorwerp V
				'.$where.'';
	$stmt = sqlsrv_query($conn, $sql);
	if( $stmt == false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	$uitvoer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
							$eindDatum = $uitvoer['LooptijdeindeDag'];
							$eindDatumApart = explode("-",$eindDatum);
							$eindTijd = $uitvoer['looptijdEindeTijdstip'];
							$eindTijdApart = explode(":", $eindTijd);
							
							
							//values to use
							
							$huidigDag = date('d');
							$huidigMaand = date('m');
							$huidigJaar = date('Y');
							
							$huidigUur = date("G");
							$huidigMinuut = date("i");
							$huidigSeconde = date("s");
							
							$eindDag = $eindDatumApart[2];
							$eindMaand = $eindDatumApart[1];
							$eindJaar = $eindDatumApart[0];
							
							$eindTijdUren = $eindTijdApart[0];
							$eindTijdMinuten = $eindTijdApart[1];
							$eindTijdSeconde = $eindTijdApart[2];
$c = new countdown();
$c->setFormat('%d dagen %h:%m:%s');
$c->setDay($eindDag);
$c->setMonth($eindMaand);
$c->setYear($eindJaar);
$c->setHour($eindTijdUren);
$c->setMinute($eindTijdMinuten);
$c->setSecond($eindTijdSeconde);
	$sqlBekijkHoogsteBod = 'select top 1 B.Bodbedrag, V.Voorwerpnummer, B.Gebruiker
										from Voorwerp V INNER JOIN Bod B
																ON V.Voorwerpnummer=B.Voorwerp
										'.$where.'
										ORDER BY B.Bodbedrag DESC';
	$stmtHoogsteBod = sqlsrv_query($conn, $sqlBekijkHoogsteBod);
	$uitvoerHoogsteBod = sqlsrv_fetch_array($stmtHoogsteBod, SQLSRV_FETCH_ASSOC);
	$hoogsteBod=$uitvoerHoogsteBod['Bodbedrag'];
	$huidigeDag=date("d:m:Y");
	$huidigeTijd=date("G:i:s");
	$bodNietHoogGenoeg=false;
	if($c->getTimeRemaining()=='0 dagen 0:0:0'&& $uitvoer['VeilingGesloten']=='Nee'){
		$sql99="update Voorwerp set VeilingGesloten='Ja' where Voorwerpnummer = ".$uitvoer['Voorwerpnummer']."";
		$stmt99 = sqlsrv_query($conn, $sql99);
		$uitvoer99 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		$sql2 = 'select Gebruikersnaam, Voornaam, Achternaam, Mailbox
					from Gebruiker
					where Gebruikersnaam=(select top 1 B.Gebruiker
														from Voorwerp V INNER JOIN Bod B
																				ON V.Voorwerpnummer=B.Voorwerp
														'.$where.'
														ORDER BY B.Bodbedrag DESC)';
		$stmt2=sqlsrv_query($conn, $sql2);
		$rows=sqlsrv_has_rows($stmt2);
		if($rows==true){
			$row=sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
			$email=$row['Mailbox'];
			$msg = "Gefeliciteerd! U heeft de veiling met de naam: ".rtrim($uitvoer['Titel'])." gewonnen! \n Wanneer u uw product ontvangen hebt kunt u feedback achterlaten op de verkoper met deze link: iproject12.icasites.nl/feedbackAchterlaten.php?voorwerp=".$nummer."&gebruiker=".$uitvoer['Verkoper']."\n Bewaar deze email goed.";
			mail( $email,"Gewonnen veiling",$msg);
		}
		if($rows==false){
			$sql2 = "select Mailbox
						from Gebruiker
						where Gebruikersnaam='".$uitvoer['Verkoper']."'";
			$stmt2=sqlsrv_query($conn, $sql2);
			$row=sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
			$email=$row['Mailbox'];
			$msg = "Helaas! uw veiling met de naam: ".rtrim($uitvoer['Titel'])." heeft geen biedingen gekregen. \n Uw veiling is gesloten.";
			mail( $email,"Uw veiling is gesloten",$msg);
		}
	}
	if(isset($_POST['gebruikerBieding'])){
		$gebodenDoorUser = $_POST['gebruikerBieding'];
		if($hoogsteBod<$gebodenDoorUser && $uitvoer['Startprijs']<=$gebodenDoorUser && $ingelogdeGebruiker!=$uitvoer['Verkoper'] && $uitvoer['VeilingGesloten']=='Nee'){
			$stmt = "INSERT INTO Bod VALUES (?,?,?,?,?)";
			$args = array($nummer, $gebodenDoorUser, $ingelogdeGebruiker, $huidigeDag, $huidigeTijd);
			$result = sqlsrv_query( $conn, $stmt, $args);
		}
		else{
			$bodNietHoogGenoeg=true;
		}
	}
	$sql2 = 'select top 4 B.Bodbedrag, V.Voorwerpnummer, B.Gebruiker
				from Voorwerp V INNER JOIN Bod B
										ON V.Voorwerpnummer=B.Voorwerp
				'.$where.'
				ORDER BY B.Bodbedrag DESC';
	$stmt2 = sqlsrv_query($conn, $sql2);
	$hoogsteBod=0;
	$bod1=0;
	$bod2=0;
	$bod3=0;
	$gebruikerBodHoogst='';
	$gebruikerBod1='';
	$gebruikerBod2='';
	$gebruikerBod3='';
	$welkeInvullen=0;
	while(($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))){
		switch ($welkeInvullen) {
			case 0:
				$hoogsteBod=$row['Bodbedrag'];
				$gebruikerBodHoogst=$row['Gebruiker'];
				$welkeInvullen++;
			break;
			case 1:
				$bod1=$row['Bodbedrag'];
				$gebruikerBod1=$row['Gebruiker'];
				$welkeInvullen++;
			break;
			case 2:
				$bod2=$row['Bodbedrag'];
				$gebruikerBod2=$row['Gebruiker'];
				$welkeInvullen++;
			break;
			case 3:
				$bod3=$row['Bodbedrag'];
				$gebruikerBod3=$row['Gebruiker'];
			break;
		}
	}
	echo'
		<div class="contentPD">
			<div class="productTitlePD">
				<h1><a href="verkoperDetails.php?verkoperID='.$uitvoer['Verkoper'].'">'; echo 'Verkoper: '.$uitvoer['Verkoper'] .'</a>- '; echo 'Titel: '.$uitvoer['Titel']; echo '</h1>
			</div>
			<div class="imgClass">
				<div class="mainImg">	';
				if($uitvoer['VeilingGesloten']=='Ja'){
					echo 'Deze veiling is gesloten. Er kan niet meer geboden worden.';
				}
				$sqlIMG = 'select top 3 Filenaam
							from Bestand
							where Voorwerp='.$uitvoer['Voorwerpnummer'];
				$stmtIMG = sqlsrv_query($conn, $sqlIMG);
				$imgInvullen=0;
				while(($rowIMG = sqlsrv_fetch_array($stmtIMG, SQLSRV_FETCH_ASSOC))){
					switch ($imgInvullen) {
						case 0:
							echo '<img src="resources/images/'.$rowIMG['Filenaam'].'" alt="'.$rowIMG['Filenaam'].'">
										</div>
										<div class="subImg">';
							$imgInvullen++;
						break;
						case 1:
							echo '<div class="subImgA">
										<img src="resources/images/'.$rowIMG['Filenaam'].'" alt="'.$rowIMG['Filenaam'].'">
									</div>';
							$imgInvullen++;
						break;
						case 2:
							echo '<div class="subImgB">
										<img src="resources/images/'.$rowIMG['Filenaam'].'" alt="'.$rowIMG['Filenaam'].'">
									</div>';
							$imgInvullen++;
						break;
					}
				}
				echo'</div>
				</div>'; //closing imgClass
			
			echo '
			<div class="omschrijving">
				<p>
					<h2>Er kan geboden worden vanaf: &#8364;'.$uitvoer['Startprijs'].',-</h2><p>'.$uitvoer['Beschrijving'].'</p><br>
				</p>
			</div>
			<div class="bieden">
				<div class="geboden">
					<table>
						<tr>
							<td><p>Hoogste bod:</p></td>
							<td><input type="text" name="hoogsteBod" placeholder="&#8364;'.$hoogsteBod.'"disabled>&nbsp;<p>'.$gebruikerBodHoogst.'</p></td>
						</tr>
						<tr>';
						if($bod1!=0){
						echo '
							<td><p>Vorige biedingen:</p></td>
							<td><input type="text" name="hoogsteBod" placeholder="&#8364;'.$bod1.'"disabled>&nbsp;<p>'.$gebruikerBod1.'</p></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="text" name="hoogsteBod" placeholder="&#8364;'.$bod2.'"disabled>&nbsp;<p>'.$gebruikerBod2.'</p></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="text" name="hoogsteBod" placeholder="&#8364;'.$bod3.'"disabled>&nbsp;<p>'.$gebruikerBod3.'</p></td>
						</tr>';
						}
				echo	'</table>
				</div>
				<div class="bied">
					<table>
					';
					if(isset($_SESSION['username'])){
						if($bodNietHoogGenoeg=true){
							echo '<p>Uw bod is niet hoog genoeg. U moet minimaal &#8364;';
								if($hoogsteBod<$uitvoer['Startprijs']){
									echo $uitvoer['Startprijs']+1; echo ' bieden.</p>';
								}
								else{
									echo $hoogsteBod+1; echo ' bieden.</p>';
								}
						}
						echo '
					<form action="productDetailPagina.php?id='.$nummer.'" method="post">
						<tr>
							<td><p>Uw bod:</p></td>
							<td><input type="text" name="gebruikerBieding" placeholder="'.$hoogsteBod.'"></td>
						</tr>
						<tr>
							<td></td>
							<td><button type="text" name="submit">Bied</button></td>
						</tr>
					</form>
					</table>
					<p class="greenText">Let op! Het bedrag dat u bied moet u betalen wanneer u de veiling wint!<br>U kunt niet op uw eigen veilingen bieden.</p>
				</div>';
				}
				else{
					echo '
					<form action="productDetailPagina.php?id='.$nummer.'" method="post">
						<tr>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
						</tr>
					</form>
					</table>
					<p class="greenText">U moet ingelogd zijn om te kunnen bieden!</p>
				</div>';
			}
			echo '
			</div>
			<div class="timer">
				<h1>'; print $c->getTimeRemaining(); echo '</h1>
			</div>
				<div class="pageExtender">
					&nbsp;
				</div>
		</div>';

		$sql3 = "select top 3 V.Voorwerpnummer, V.Titel, V.Beschrijving, V.Startprijs, V.Betalingswijze, V.looptijdEindeTijdstip, V.Verkoper, V.LooptijdeindeDag, V.VeilingGesloten
				from Voorwerp V
				WHERE V.VeilingGesloten='Nee' AND Voorwerpnummer IN (select Voorwerp
										from VoorwerpInRubriek
										where RubriekOpLaagsteNiveau = (select RubriekOpLaagsteNiveau
																							from VoorwerpInRubriek
																							where Voorwerp = ".$nummer."
																							)
															)";
	$stmt3 = sqlsrv_query($conn, $sql3);
		?>

<div class="contentPD relatedProducts">
	<div class="productTitle">
		<h1>Gerelateerde Veilingen</h1>
	</div>
	<?php
		while(($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC))){
							$eindDatum = $row['LooptijdeindeDag'];
							$eindDatumApart = explode("-",$eindDatum);
							$eindTijd = $row['looptijdEindeTijdstip'];
							$eindTijdApart = explode(":", $eindTijd);
							
							
							//values to use
							
							$huidigDag = date('d');
							$huidigMaand = date('m');
							$huidigJaar = date('Y');
							
							$huidigUur = date("G");
							$huidigMinuut = date("i");
							$huidigSeconde = date("s");
							
							$eindDag = $eindDatumApart[2];
							$eindMaand = $eindDatumApart[1];
							$eindJaar = $eindDatumApart[0];
							
							$eindTijdUren = $eindTijdApart[0];
							$eindTijdMinuten = $eindTijdApart[1];
							$eindTijdSeconde = $eindTijdApart[2];

$c = new countdown();
$c->setFormat('%d dagen %h:%m:%s');
$c->setDay($eindDag);
$c->setMonth($eindMaand);
$c->setYear($eindJaar);
$c->setHour($eindTijdUren);
$c->setMinute($eindTijdMinuten);
$c->setSecond($eindTijdSeconde);
				$sql2 = 'SELECT top 1 Filenaam
					FROM Bestand
					WHERE Voorwerp = '.$row['Voorwerpnummer'].'';
				$stmt2 = sqlsrv_query($conn, $sql2);
				$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
		echo '	<div class="RPWrapper">
		<div class="productRP">
			<div class="productImgRP">
			';
				echo '<img src="resources/images/'.$row2['Filenaam'].'" alt="'.$row2['Filenaam'].'">';
			echo '
			</div>
			<div class="productTitleRP">
				<h3>'.$row['Titel'].'</h3>
			</div>
			<div class="productOmschrijvingRP">
				<p>
					'.$row['Beschrijving'].'
				</p>
			</div>
			<div class="timerRP">
				<h3>'; print $c->getTimeRemaining(); echo '</h3>
			</div>
			<a href="productDetailPagina.php?id='.$row['Voorwerpnummer'].'">Bekijk veiling &nbsp;</a> 
		</div>
	</div>';
	}
	?>
</div>
&nbsp;