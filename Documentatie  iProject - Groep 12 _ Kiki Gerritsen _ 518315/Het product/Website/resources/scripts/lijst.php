<div id="blok">
	<div class="Rubriek">Rubrieken</div>
	<div class="icon"><img src="resources/images/menuitem.png" alt="Open menu"></div>
	<div class="contentMenu">
		<ul>
		<?php
			include"resources/scripts/dbConnection.php";
			$sql =	'select *
						from Rubriek';
			$stmt = sqlsrv_query($conn, $sql );
			if($stmt==false) {
				die(print_r( sqlsrv_errors(), true));
			}
			while(($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
			echo'<li><a href="?rubriek='.$row['Rubrieknummer'].'">'.$row['Rubrieknaam'].'</a></li>';
			}
		?>
		</ul>
	</div>
</div>