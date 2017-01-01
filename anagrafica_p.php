<?php
session_start();
include ("functions.php");
switch ($_REQUEST['tipo'])
{		
	case 'id_paziente':
		$_SESSION['id_type'] = $id_type = 'id_paziente';
		$table = 'Anagrafica_paziente';
		break;
	
	case 'id_operatore':
		$_SESSION['id_type'] = $id_type = 'id_operatore';
		$table = 'Anagrafica_operatore';
		break;
}
$case = null;
if (!empty($_GET['case']))
	$case = $_GET['case'];
switch ($case)
{		
	case '1':
		$id = $_SESSION['id'];
		break;
	
	default:
		$id = $_GET['id'];
		break;
}
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	if ($id_type == 'id_paziente')
		$query = mysql_query ("select * FROM `$table`, Cartella_clinica WHERE `$table`.`$id_type` = $id and `Cartella_clinica`.`$id_type` = $id");
	else
		$query = mysql_query ("select * FROM `$table` WHERE `$id_type` = $id");
	echo mysql_error();
	if ($query)
	{
		$n_rows=mysql_num_rows($query);
		if  ($n_rows==1)
		{
			$riga=mysql_fetch_assoc($query);
			$_SESSION['_1'] = $riga['username'];
			$_SESSION['_2'] = $riga['nome'];
			$_SESSION['_3'] = $riga['cognome'];
			$data = explode('-',$riga['data_nascita']);
			$_SESSION['_4'] = $data[2].'-'.$data[1].'-'.$data[0];
			$_SESSION['_5'] = $riga['comune_nascita'];
			if ($riga['sesso']==1)
				$_SESSION['_6'] = 'Uomo';
			elseif ($riga['sesso']==2)
				$_SESSION['_6'] = 'Donna';
			$_SESSION['_7'] = $riga['codice_fiscale'];
			$_SESSION['_8'] = $riga['indirizzo'];
			$_SESSION['_9'] = $riga['cap'];
			$_SESSION['_10'] = $riga['città'];
			$_SESSION['_11'] = $riga['numero_telefono'];
			if ($riga['email'])
				$_SESSION['_12'] = $riga['email'];
			else
				$_SESSION['_12'] = '"Nessuna email specificata"';
			$data1 = explode(' ',$riga['data_registrazione']);
			$ora = $data1[1];
			$data1 = explode('-',$data1[0]);
			if ($id_type == 'id_paziente')
			{
				if ($riga['Q_neurologico'])
					$_SESSION['_15'] = $riga['Q_neurologico'];
				else
					$_SESSION['_15'] = '"Non Specificato"';
				if ($riga['Q_psicologico'])
					$_SESSION['_16'] = $riga['Q_psicologico'];
				else
					$_SESSION['_16'] = '"Non Specificato"';
				if ($riga['terapia'])
					$_SESSION['_17'] = $riga['terapia'];
				else
					$_SESSION['_17'] = '"Non Specificato"';
				$data2 = explode('-',$riga['data_riscontro']);
				$_SESSION['_18'] = $data2[2].'-'.$data2[1].'-'.$data2[0];
			}
			$_SESSION['_13'] = $data1[2].'-'.$data1[1].'-'.$data1[0].' '.$ora;
			$_SESSION['_14'] = $riga['immagine'];
			$_SESSION['ID'] = $riga[$id_type];
			header("Refresh: 0; URL=../Schema/index.php?case=research_done1");
		}
		elseif ($n_rows==0)
			header("Refresh: 0; URL=../Schema/index.php?refresh=no_match");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
}
connection_close($conn);
?>