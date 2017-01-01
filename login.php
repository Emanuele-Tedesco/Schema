<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$user = $_POST["user"];
	$pass = $_POST["pass"];
	$query = mysql_query ("select * from Utente where `username`= '$user' and `password` = sha1('$pass')");
	echo mysql_error();
	$risultato = mysql_fetch_row($query);
	$type = 4;
	echo $risultato[3];
	if ($risultato[3] == 1)
	{
		$type ="Amministratore";
		$_SESSION['admin'] = true;
	}
	elseif ($risultato[3] == 2)
	{
		$type ="Operatore";
		$_SESSION['operator'] = true;
	}
	elseif ($risultato[3] == 3)
	{
		$type ="Paziente";
		$_SESSION['pazien'] = true;
	}
	$_SESSION['id'] = $risultato[0];
	$_SESSION['type'] = $type;
	$_SESSION['user'] = $user;
	if ( $risultato != NULL ){
		header("Refresh: 0; URL=../Schema/index.php");		
	}
	else{
		header("Refresh: 0; URL=../Schema/index.php");
	}
}
connection_close($conn);
?>
