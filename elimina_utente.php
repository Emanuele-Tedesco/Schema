<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$t_utente = $_SESSION['t_utente'];
	if ($t_utente == 2)
	{
		$id_type = 'id_operatore';
		$table = 'Anagrafica_operatore';
	}
	elseif ($t_utente == 3)
	{
		$id_type = 'id_paziente';
		$table = 'Anagrafica_paziente';
	}
	mysql_select_db('Telemedicina');
	$query = "DELETE FROM `Utente` WHERE `id` = ".$_SESSION['id_specifico1']."";
	$result = mysql_query($query);
	if ($result)
	{
		$query = "DELETE FROM $table WHERE $id_type = ".$_SESSION['id_specifico1']."";
		$result = mysql_query($query);
		if ($result)
			{// nonostante il paziente venga rimosso i dati vengono conservati per statistca
				/*if ($id_type == 'id_paziente')
				{
					$query = "DELETE FROM Misurazioni WHERE $id_type = ".$_SESSION['id_specifico1']."";
					$result = mysql_query($query);
					$query = "DELETE FROM Cartella_clinica WHERE $id_type = ".$_SESSION['id_specifico1']."";
					$result = mysql_query($query);
				}*/
				header("Refresh: 0; URL=../Schema/index.php?refresh=conf_el&utente=$t_utente");
			}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
}
connection_close($conn);
?>
