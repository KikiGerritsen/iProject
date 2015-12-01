<?php
	$serverName = "mssql.iproject12.icasites.nl";
	$connectionInfo = array( "Database"=>"iproject12", "UID"=>"iproject12", "PWD"=>"wbBEJwxS", "CharacterSet"=> "UTF-8");
	$conn = sqlsrv_connect( $serverName, $connectionInfo );
	if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
	}
?>