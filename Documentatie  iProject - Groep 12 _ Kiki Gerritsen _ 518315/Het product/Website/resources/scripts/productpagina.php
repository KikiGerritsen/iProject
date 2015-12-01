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
			include"resources/scripts/dbConnection.php";
			include"resources/scripts/productDisplay.php";
			include"resources/scripts/class.timer.php";
		?>
		<div id="pagina">
		<?php
			include"resources/scripts/zoeken.php";
		?>
			<div id="content">
				<?php
					$secondWhere='';
					$thirdWhere='';
					if(isset($_GET['zoekTerm']) || isset($_GET['rubriek'])){
						if(isset($_GET['zoekTerm'])){
							$zoekTerm=$_GET['zoekTerm'];
							$secondWhere=" AND Titel LIKE '%".$zoekTerm."%' ";
						}
						if(isset($_GET['rubriek'])){
							$rubriek=$_GET['rubriek'];
							if($rubriek>0){
								$thirdWhere=" AND RubriekOpLaagsteNiveau=".$rubriek;
							}
						}
					}
					if (isset($_GET["page"])) {
						$page  = $_GET["page"]; 
					}
					else { 
						$page=1; 
					}
					$start_from = ($page-1) * 20;
					$end_from = $start_from+20;
					$sql = "SELECT * FROM ( 
									SELECT V.Voorwerpnummer, V.Titel, V.Beschrijving, V.LooptijdeindeDag, V.LooptijdEindeTijdstip, ROW_NUMBER() OVER (ORDER BY Voorwerpnummer) as row FROM Voorwerp V INNER JOIN VoorwerpInRubriek R ON V.Voorwerpnummer = R.Voorwerp WHERE VeilingGesloten='Nee'".$secondWhere." ".$thirdWhere."
								) a WHERE a.row > ".$start_from." and a.row <= ".$end_from."";
					$stmt = sqlsrv_query($conn, $sql );
					if($stmt==false) {
						die(print_r( sqlsrv_errors(), true));
					}
					$aantalProductenBinnenSelect=0;
					$sqlTellen = "SELECT * FROM Voorwerp  V INNER JOIN VoorwerpInRubriek R ON V.Voorwerpnummer = R.Voorwerp WHERE VeilingGesloten='Nee'".$secondWhere." ".$thirdWhere."";
					$stmtTellen = sqlsrv_query($conn, $sqlTellen);
					while(($rowTellen = sqlsrv_fetch_array($stmtTellen, SQLSRV_FETCH_ASSOC))){
						$aantalProductenBinnenSelect++;
					}
					$n=0;
					while(($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
							
							$eindDatum = $row['LooptijdeindeDag'];
							$eindDatumApart = explode(":",$eindDatum);
							$eindTijd = $row['LooptijdEindeTijdstip'];
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
						$n++;
						$resterendeTijdDB=$c->getTimeRemaining();
						$sql2 = 'SELECT top 1 Filenaam
									FROM Bestand
									WHERE Voorwerp = '.$row['Voorwerpnummer'].'';
						$stmt2 = sqlsrv_query($conn, $sql2);
						$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
						tekenProduct($row['Titel'], $row2['Filenaam'],$row['Beschrijving'], $resterendeTijdDB, $row['Voorwerpnummer']);
					}
					if($aantalProductenBinnenSelect!=0&&$n!=0){
						$total_pages = ceil($aantalProductenBinnenSelect/$n);
					}
					else{
						$total_pages=1;
					}
				?>
				<div class="pageExtender">
					&nbsp;
				</div>
			</div>
			&nbsp;
			<?php
				echo "pagina's: ";
				for ($i=1; $i<=$total_pages; $i++) {
					echo "<a href='productpagina.php?page=".$i."'>"; if($i==$page){ echo '<b>'.$i.'</b>'; } else{ echo $i; } echo " </a> ";
				}; 
			?>
		</div>
		&nbsp;
	</div>
	</body>
</html>