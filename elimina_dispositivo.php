<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$query = "DELETE FROM `Strumentazioni` WHERE `id` = ".$_SESSION['id_s']."";
	echo mysql_error();
	$result = mysql_query($query);
	if ($result)
	{
		$query= "UPDATE `Misurazioni` SET  `id_strumentazione` =  '0' WHERE  `id_strumentazione` = ".$_SESSION['id_s']."";
		$result = mysql_query($query);
		if ($result)
			header("Refresh: 0; URL=../Schema/index.php?refresh=conf_el&utente=4");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
}
unset($_SESSION['id_s']);
connection_close($conn);
?>