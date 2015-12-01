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
	?>
		<div id="pagina">
			<div id="content">
				<div class="largeText">
				<div class="Meestgesteldevragenblok">
					<h1>Meest gestelde vragen</h1>
					<div id="listbox">
					<select name="sometext" multiple="multiple" >
					<?php
						$sql =	'select *
									FROM Vraag';
						$stmt = sqlsrv_query($conn, $sql );
						$i=0;
						$style='style="background-color: #DCDCDC;"';
						while(($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
							$i++;
							if($i%2==0){
								$style='';
							}
							else{
								$style='style="background-color: #DCDCDC;"';
							}
							echo'<option '.$style.'>'.$row['Tekstvraag'].'</option>';
						}
					?>
					 </select>
					</div>
					<div id="submitvraag">
						<input type="submit" name="Stel een Vraag" value="Stel een Vraag">
					</div>
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