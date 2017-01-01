<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$id = $_SESSION['id'];
	mysql_select_db('Telemedicina');
	$query = mysql_query ("select * FROM Anagrafica_paziente WHERE id_paziente = '$id'");
	echo mysql_error();
	if ($query)
	{
		$n_rows=mysql_num_rows($query);
		if  ($n_rows==1)
		{
			$riga=mysql_fetch_assoc($query);
			$_SESSION['nome_paziente'] = $riga['nome'];
			$_SESSION['cognome_paziente'] = $riga['cognome'];
			switch ($_REQUEST['case'])
			{
				case 'vis_anagrafica':
					header("Refresh: 0; URL=../Schema/visualizza_m.php?&name=".$_SESSION['nome_paziente']."&surname=".$_SESSION['cognome_paziente']."&id=".$id."");
					break;
				
				case 'vis_ultima_mis':
					header("Refresh: 0; URL=../Schema/ultima_mis.php");
					break;
			}
		}
		elseif ($n_rows==0)
			echo mysql_error();
	}
	else
		echo mysql_error();
}
connection_close($conn);
?>