<!DOCTYPE html>
<?php session_start(); ?>
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
	?>
		<div id="pagina">
			<div id="content">
				<?php
					if(isset($_GET['verkoperID'])){
						$verkoper = $_GET['verkoperID'];
					}
					echo 'Verkoper: '.$verkoper.'<br>';
					$sql ="	select F.Feedbacksoort
						from Feedback F INNER JOIN Voorwerp V
												ON F.Voorwerp = V.Voorwerpnummer
						where V.Verkoper='".$verkoper."'";
					$stmt = sqlsrv_query($conn, $sql);
					$percentage=0;
					$aantalRecords=0;
					$aantalPositief=0;
					$aantalNegatief=0;
					$aantalNeutraal=0;
					while( ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))) {
						$aantalRecords++;
						if($row['Feedbacksoort']=='Negatief' || $row['Feedbacksoort']=='negatief'){
							$aantalNegatief++;
							$percentage=$percentage;
						}
						if($row['Feedbacksoort']=='Positief' || $row['Feedbacksoort']=='positief'){
							$aantalPositief++;
							$percentage=$percentage+100;
						}
						if($row['Feedbacksoort']=='Neutraal' || $row['Feedbacksoort']=='neutraal'){
							$aantalNeutraal++;
							$percentage=$percentage+50;
						}
					}
					if($aantalRecords>0){
						if($percentage>0){
							echo 'Tevredenheids percentage: '.round($percentage/$aantalRecords, 2).'&#37;';
							echo '<br>';
							echo $verkoper .' heeft '.$aantalRecords.' keer feedback gekregen.';
						}
						if($percentage==0){
							echo 'Tevredenheids percentage: '; echo 0; echo '&#37;';
							echo '<br>';
							echo $verkoper .' heeft '.$aantalRecords.' keer feedback gekregen.';
						}
					}
					else{
						echo 'Deze verkoper heeft nog geen feedback gekregen.';
					}
				?>
				&nbsp;
			</div>
			<div class="pageExtender">
				&nbsp;
			</div>
		</div>
	</body>
</html>
					