<?php
	$sql =	'SELECT V.Voorwerpnummer, V.Titel, V.Beschrijving, V.LooptijdeindeDag, V.looptijdEindeTijdstip
				FROM Productvandedag P INNER JOIN Voorwerp V
											ON P.Productvandedag = V.Voorwerpnummer';
	$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	$nummer=0;
	while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)){
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
		$c->setFormat('%d:%h:%m:%s');
		$c->setDay($eindDag);
		$c->setMonth($eindMaand);
		$c->setYear($eindJaar);
		$c->setHour($eindTijdUren);
		$c->setMinute($eindTijdMinuten);
		$c->setSecond($eindTijdSeconde);
		$resterendeTijdDB=$c->getTimeRemaining();
		$sql2 = 'SELECT top 1 Filenaam
					FROM Bestand
					WHERE Voorwerp = '.$row['Voorwerpnummer'].'';
		$stmt2 = sqlsrv_query($conn, $sql2);
		$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
		$nummer++;
		tekenProductTopTrending($row['Titel'], $row['Beschrijving'], $row2['Filenaam'], $resterendeTijdDB, $nummer, $row['Voorwerpnummer']);
	}
?>