<?php
	include"resources/scripts/dbConnection.php";
	$aantalProductenGeteld=0;
	$sql =	"SELECT Voorwerpnummer
				FROM Voorwerp
				WHERE VeilingGesloten='Nee'
				ORDER BY Voorwerpnummer";
	$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	while( ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))) {
		$aantalProductenGeteld++;
	}
	echo'<div id="zoeken">
				<form action="productpagina.php" method="get">
				<input type="text" name="zoekTerm" placeholder="'; if(isset($_GET['zoekTerm'])){ echo $_GET['zoekTerm']; } echo '">
				<select name="Rubrieknummer">
					<option value="0">Overal zoeken</option>';
				$sql2 =	'select Rubrieknaam, Rubrieknummer
							from Rubriek
							where Rubrieknummer>0';
			$stmt2 = sqlsrv_query($conn, $sql2 );
			if($stmt==false) {
				die(print_r( sqlsrv_errors(), true));
			}
			while(($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))){
			echo'<option value='; if(isset($_GET['Rubrieknummer'])){
												if($row2['Rubrieknummer']==$_GET['Rubrieknummer']){
													echo '"'.$row2['Rubrieknummer'].'" selected>'.$row2['Rubrieknaam'].'</option>';
												}
												else{
													echo '"'.$row2['Rubrieknummer'].'">'.$row2['Rubrieknaam'].'</option>';
												}
											}
											else{
												echo '"'.$row2['Rubrieknummer'].'">'.$row2['Rubrieknaam'].'</option>';
											}
			}	
			echo '
				</select>
				<input type="submit" name="search" value="Zoeken">
				<div id="aantalVeilingen">
					<p>Aantal actieve veilingen: '.$aantalProductenGeteld.'</p>
				</div>
				</form>
			</div>';
?>