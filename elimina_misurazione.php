<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$query = "DELETE FROM `Misurazioni` WHERE `id` = ".$_SESSION['id_m']."";
	echo mysql_error();
	$result = mysql_query($query);
	if ($result)
		header("Refresh: 0; URL=../Schema/index.php?refresh=conf_el&utente=3");
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
}
unset($_SESSION['id_m']);
connection_close($conn);
?>