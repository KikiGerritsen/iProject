<?php
	function tekenProduct($productNaam, $image, $beschrijving, $resterendeTijd, $productNummer){
		if($resterendeTijd=='0:0:0:0'){
			$sql99="update Voorwerp set VeilingGesloten='Ja' where Voorwerpnummer = ".$productNummer."";
			$stmt99 = sqlsrv_query($conn, $sql99);
			$uitvoer99 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
			$where = "WHERE Voorwerpnummer = ".$productNummer."";
			$sql = 'select V.Titel, V.Verkoper
						from Voorwerp V
						'.$where.'';
			$stmt = sqlsrv_query($conn, $sql);
			$uitvoer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
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
				$msg = "Gefeliciteerd! U heeft de veiling met de naam: ".rtrim($uitvoer['Titel'])." gewonnen! \n Wanneer u uw product ontvangen hebt kunt u feedback achterlaten op de verkoper met deze link: iproject12.icasites.nl/feedbackAchterlaten.php?voorwerp=".$productNummer."&gebruiker=".$uitvoer['Verkoper']."\n Bewaar deze email goed.";
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
		$class="product";
		echo'<div class="'.$class.'">
					<img src="resources/images/'.$image.'" alt="'.$image.'">
					<h1>'.$productNaam.'</h1>
					<div class="productoverzicht">
						'.$beschrijving.'
					</div>
					<h4>'.$resterendeTijd.'</h4>
					<a href="productDetailPagina.php?id='.$productNummer.'">Bekijk veiling &nbsp;</a> 
				</div>';
	}
	
	function tekenProductBlok($naam, $beschrijving, $image, $resterendeTijd, $nummer, $productNummer){
		$divClass="productBlok";
		if($nummer==3){
			$divClass="productBlok , lastProductBlok";
		}
		echo'<div class="'.$divClass.'">
					<div class="block">
						<div class="timer">
							<h4><a href="productDetailPagina.php?id='.$productNummer.'">Bekijk veiling &nbsp;</a>'.$resterendeTijd.'</h4> 
						</div>
						<div class="imgClass">
							<a href="productDetailPagina.php?id='.$productNummer.'"><img src="resources/images/'.$image.'" alt="'.$image.'"></a>
						</div>
						<div class="productTitle">
							<h2>'.$naam.'</h2>
						</div>
						<div class="productBlokTekst">
							'.$beschrijving.'
						</div>
					</div>
				</div>';
	}
	
	function tekenProductTopTrending($naam, $beschrijving, $image, $resterendeTijd, $nummer, $productNummer){
		$divClass="mainProductBlok";
		if($nummer==2){
			$divClass="mainProductBlok , lastProductBlok";
		}
		echo'<div class="'.$divClass.'">
					<div class="block">
						<div class="timer">
							<h4><a href="productDetailPagina.php?id='.$productNummer.'">Bekijk veiling &nbsp;</a>'.$resterendeTijd.'</h4> 
						</div>
						<div class="imgClass">
							<a href="productDetailPagina.php?id='.$productNummer.'"><img src="resources/images/'.$image.'" alt="'.$image.'"></a>
						</div>
						<div class="productTitle">
							<h1>'.$naam.'</h1>
						</div>
						<div class="productBlokTekst">
							'.$beschrijving.'
						</div>
					</div>
				</div>';
	}
?>