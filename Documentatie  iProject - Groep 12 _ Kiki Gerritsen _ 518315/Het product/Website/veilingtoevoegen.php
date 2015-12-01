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
			$titelIngevuld=false;
			$afbeelding1ingevuld=false;
			$afbeelding2ingevuld=false;
			$afbeelding3ingevuld=false;
			$rubriekingevuld=false;
			$maandeningevuld=false;
			$jareningevuld=false;
			$dageningevuld=false;
			$ureningevuld=false;
			$minuteningevuld=false;
			$secondeningevuld=false;
			$verzendingingevuld=false;
			$startprijsingevuld=false;
			if(isset($_POST['Titel'])){
				$titel=$_POST['Titel'];
				$titelIngevuld=true;
			}
			if(isset($_POST['Omschrijving'])){
				$omschrijving=$_POST['Omschrijving'];
				$omschrijvingingevuld=true;
			}
			if ($_FILES["Afbeelding1"]["error"] > 0) {
			} else {
				$afbeelding1ingevuld=true;
			}
			if ($_FILES["Afbeelding2"]["error"] > 0) {
			} else {
				$afbeelding2ingevuld=true;
			}
			if ($_FILES["Afbeelding3"]["error"] > 0) {
			} else {
				$afbeelding3ingevuld=true;
			}
			if(isset($_POST['rubriekNummer'])){
				$rubriek=(int)$_POST['rubriekNummer'];
				$rubriekingevuld=true;
			}
			if(isset($_POST['jaar'])){
				$jaar=(int)$_POST['jaar'];
				$jareningevuld=true;
			}
			if(isset($_POST['maand'])){
				$maand=(int)$_POST['maand'];
				$maandeningevuld=true;
			}
			if(isset($_POST['dag'])){
				$dagen=(int)$_POST['dag'];
				$dageningevuld=true;
			}
			if(isset($_POST['uur'])){
				$uur=(int)$_POST['uur'];
				$ureningevuld=true;
			}
			if(isset($_POST['minuut'])){
				$minuut=(int)$_POST['minuut'];
				$minuteningevuld=true;
			}
			if(isset($_POST['seconde'])){
				$seconde=(int)$_POST['seconde'];
				$secondeningevuld=true;
			}
			if(isset($_POST['verzending'])){
				$verzending=$_POST['verzending'];
				$verzendingingevuld=true;
			}
			if(isset($_POST['startPrijs'])){
				$startPrijs=(int)$_POST['startPrijs'];
				$startprijsingevuld=true;
			}
			if(isset($_POST['voorwaarden'])){
				$voorwaarden=true;
			}
			if( $voorwaarden==true && $titelIngevuld==true && $omschrijvingingevuld==true && $rubriekingevuld==true && $dageningevuld==true && $verzendingingevuld==true && $startprijsingevuld==true && $ureningevuld==true && $secondeningevuld==true && $maandeningevuld==true && $jareningevuld==true && $minuteningevuld==true){
				$betalingswijze='iDeal';
				$betalingsInstructie=NULL;
				$plaatsnaam='Arnhem';
				$land='Nederland';
				$huidigDag = date('d');
				$huidigMaand = date('m');
				$huidigJaar = date('Y');
				$huidigUur = date("G");
				$huidigMinuut = date("i");
				$huidigSeconde = date("s");
				$looptijdBeginDag=''.$huidigJaar.'-'.$huidigMaand.'-'.$huidigDag.'';
				$looptijdBeginTijdstip=''.$huidigUur.':'.$huidigMinuut.':'.$huidigSeconde.'';
				$verzendkosten=NULL;
				$verkoper=$_SESSION['username'];
				$koper=NULL;
				$eindDatum=''.$jaar.'-'.$maand.'-'.$dagen.'';
				$eindTijd=''.$uur.':'.$minuut.':'.$seconde.'';
				$veilingGesloten='Nee';
				$verkoopprijs=0;
				
				$stmt = "insert into voorwerp (Titel, Beschrijving, Startprijs, Betalingswijze, Betalinginstructie, Plaatsnaam, Land, Looptijd, LooptijdBeginDag, LooptijdBeginTijdstip, Verzendkosten, VerzendingsInstructie, Verkoper, Koper, LooptijdeindeDag, looptijdEindeTijdstip, VeilingGesloten, Verkoopprijs) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$args = array($titel, $omschrijving, $startPrijs, $betalingswijze, $betalingsInstructie, $plaatsnaam, $land, 0, $looptijdBeginDag, $looptijdBeginTijdstip, $verzendkosten, $verzending, $verkoper, $koper, $eindDatum,  $eindTijd, $veilingGesloten, $verkoopprijs);
				$result = sqlsrv_query( $conn, $stmt, $args);
				if($afbeelding1ingevuld==true || $afbeelding2ingevuld==true || $afbeelding3ingevuld==true){
						$sql = "select top 1 Voorwerpnummer
									from Voorwerp
									where Titel='".$titel."' AND Beschrijving='".$omschrijving."' AND Plaatsnaam='".$plaatsnaam."' AND LooptijdBeginDag='".$looptijdBeginDag."' AND LooptijdBeginTijdstip='".$looptijdBeginTijdstip."'";
						$stmt = sqlsrv_query($conn, $sql);
						$uitvoer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
						$stmt = "INSERT INTO VoorwerpInRubriek VALUES (?,?)";
						$args = array($uitvoer['Voorwerpnummer'], $rubriek);
						$result = sqlsrv_query( $conn, $stmt, $args);
					if ($_FILES["Afbeelding1"]["error"] > 0) {
					} else {
						$allowedExts = array("gif", "jpeg", "jpg", "png");
						$temp = explode(".", $_FILES["Afbeelding1"]["name"]);
						$extension = end($temp);
						if ((($_FILES["Afbeelding1"]["type"] == "image/gif") || ($_FILES["Afbeelding1"]["type"] == "image/jpeg") || ($_FILES["Afbeelding1"]["type"] == "image/jpg") || ($_FILES["Afbeelding1"]["type"] == "image/pjpeg") || ($_FILES["Afbeelding1"]["type"] == "image/x-png")  || ($_FILES["Afbeelding1"]["type"] == "image/png")) && in_array($extension, $allowedExts)) {
							if ($_FILES["Afbeelding1"]["error"] > 0) {
								echo "Return Code: " . $_FILES["Afbeelding1"]["error"] . "<br>";
							} else {
								if (file_exists("upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding1"]["name"])) {
									echo $_FILES["Afbeelding1"]["name"] . " already exists. ";
								} else {
									mkdir("upload/".$uitvoer['Voorwerpnummer']."");
									move_uploaded_file($_FILES["Afbeelding1"]["tmp_name"], "upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding1"]["name"]);      
									$filenaamlocatie='../../upload/'.$uitvoer['Voorwerpnummer'].'/'.$_FILES["Afbeelding1"]["name"].'';
									$stmt = "INSERT INTO Bestand VALUES (?,?)";
									$args = array($uitvoer['Voorwerpnummer'], $filenaamlocatie);
									$result = sqlsrv_query( $conn, $stmt, $args);
								}
							}
						} else {
							echo "Invalid file";
					}
					}
					if ($_FILES["Afbeelding2"]["error"] > 0) {
					} else {
						$allowedExts = array("gif", "jpeg", "jpg", "png");
						$temp = explode(".", $_FILES["Afbeelding2"]["name"]);
						$extension = end($temp);
						if ((($_FILES["Afbeelding2"]["type"] == "image/gif") || ($_FILES["Afbeelding2"]["type"] == "image/jpeg") || ($_FILES["Afbeelding2"]["type"] == "image/jpg") || ($_FILES["Afbeelding2"]["type"] == "image/pjpeg") || ($_FILES["Afbeelding2"]["type"] == "image/x-png")  || ($_FILES["Afbeelding2"]["type"] == "image/png")) && in_array($extension, $allowedExts)) {
							if ($_FILES["Afbeelding2"]["error"] > 0) {
								echo "Return Code: " . $_FILES["Afbeelding2"]["error"] . "<br>";
							} else {
								if (file_exists("upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding2"]["name"])) {
									echo $_FILES["Afbeelding2"]["name"] . " already exists. ";
								} else {
									mkdir("upload/".$uitvoer['Voorwerpnummer']."");
									move_uploaded_file($_FILES["Afbeelding2"]["tmp_name"], "upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding2"]["name"]);
									$filenaamlocatie='../../upload/'.$uitvoer['Voorwerpnummer'].'/'.$_FILES["Afbeelding2"]["name"].'';                                                                                                                                                                                                                                 
									$stmt = "INSERT INTO Bestand VALUES (?,?)";
									$args = array($uitvoer['Voorwerpnummer'], $filenaamlocatie);
									$result = sqlsrv_query( $conn, $stmt, $args);
								}
							}
						} else {
							echo "Invalid file";
					}
					}
					if ($_FILES["Afbeelding3"]["error"] > 0) {
					} else {
						$allowedExts = array("gif", "jpeg", "jpg", "png");
						$temp = explode(".", $_FILES["Afbeelding3"]["name"]);
						$extension = end($temp);
						if ((($_FILES["Afbeelding3"]["type"] == "image/gif") || ($_FILES["Afbeelding3"]["type"] == "image/jpeg") || ($_FILES["Afbeelding3"]["type"] == "image/jpg") || ($_FILES["Afbeelding3"]["type"] == "image/pjpeg") || ($_FILES["Afbeelding3"]["type"] == "image/x-png")  || ($_FILES["Afbeelding3"]["type"] == "image/png")) && in_array($extension, $allowedExts)) {
							if ($_FILES["Afbeelding3"]["error"] > 0) {
								echo "Return Code: " . $_FILES["Afbeelding3"]["error"] . "<br>";
							} else {
								if (file_exists("upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding3"]["name"])) {
									echo $_FILES["Afbeelding3"]["name"] . " already exists. ";
								} else {
									mkdir("upload/".$uitvoer['Voorwerpnummer']."");
									move_uploaded_file($_FILES["Afbeelding3"]["tmp_name"], "upload/".$uitvoer['Voorwerpnummer']."/" . $_FILES["Afbeelding3"]["name"]);
									$filenaamlocatie='../../upload/'.$uitvoer['Voorwerpnummer'].'/'.$_FILES["Afbeelding3"]["name"].'';                                                                                                                                                                                                                                 
									$stmt = "INSERT INTO Bestand VALUES (?,?)";
									$args = array($uitvoer['Voorwerpnummer'], $filenaamlocatie);
									$result = sqlsrv_query( $conn, $stmt, $args);
								}
							}
						} else {
							echo "Invalid file";
					}
					}
				}
			}
		?>
		<div id="pagina">
			<div id="content">
				<div class="homepageBlokVeilingToevoegen">
				<form method = "POST" action ="veilingtoevoegen.php" enctype="multipart/form-data">
				<table id = "verplaatsingTabel">
					<tr>
						<td class = "VerwijderBorder"><h1>Nieuwe Veiling</h1></td>
					</tr>
					<tr class="omschrijving">
						<td><h4 class = "aanpassenTekstPositie"> Titel </h4>
						<input id = "titelVergroten" TYPE="text" NAME="Titel"></td>
						<td><h4>Afbeelding toevoegen</h4>
						<input type="file" name="Afbeelding1" id="file">
						<input type="file" name="Afbeelding2" id="file">
						<input type="file" name="Afbeelding3" id="file"></td>
					</tr>
					<tr>
						<td colspan="2"><h4 class = "aanpassenTekstPositie"> Omschrijving</h4>
						<textarea rows="4" cols="50" id = "omschrijvingVergroten" TYPE="text" NAME="Omschrijving">
						</textarea></td>
					</tr>
					<tr>
						<td>
							<h4 class = "aanpassenTekstPositie"> Catergorie </h4>
							<select id = "maakComboboxGroter" name="rubriekNummer" class = "aanpasenTekstPositie">
									<?php
										$sql2 =	'select *
													from Rubriek
													where Rubrieknummer>0';
										$stmt2 = sqlsrv_query($conn, $sql2 );
										while(($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))){
											echo '	<option value='.$row2['Rubrieknummer'].'>'; echo $row2['Rubrieknaam'].'</option>';
										}
									?>
							</select>
						</td>
						<td><h4 class = "aanpassenTekstPositie"> Verzending </h4>
						<select class="aanpasenTekstPositie" name="verzending">
							<option value="brengen">Brengen</option>
							<option value="afhalen">Afhalen</option>
							<option value="perpost">Per post</option>
						</select>	
						</td>
					</tr>
					<tr>
						<td><h4 class = "aanpassenTekstPositie">Startprijs </h4>
							<p>Bieden vanaf</p>
							<input TYPE="text" NAME="startPrijs" placeholder=0>
							<br>
							<p>Vul 0 in voor vrij bieden.</p>
						</td>
						<td><h4 class = "aanpasenTekstPositie">Einddatum veiling </h4>
							<table class="tableCeption"><tr>
							<td><input TYPE="text" NAME="dag" placeholder="Dag (getal)"></td>
							<td><input TYPE="text" NAME="maand" placeholder="Maand (getal)"></td>	
							<td><input TYPE="text" NAME="jaar" placeholder="Jaar (getal)"></td>
							</tr><tr>
							<td><input TYPE="text" NAME="uur" placeholder="Uur (getal)"></td>
							<td><input TYPE="text" NAME="minuut" placeholder="Minuut (getal)"></td>	
							<td><input TYPE="text" NAME="seconde" placeholder="Seconde (getal)"></td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class = "VerwijderBorder"><input TYPE="checkbox" NAME="voorwaarden" VALUE="CheckboxVoorwaarden"><p>Ik ga akkoord met de algemene voorwaarden...</p>
					<br>
						<a href="spelregels.php" id = "schuifVoorwaardeOp" href = "">Bekijk de algemene voorwaarden.</a></td>
					</tr>
					<tr>
						<td class = "VerwijderBorder"><input TYPE="submit" value="Open veiling"></td>
					</tr>	
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