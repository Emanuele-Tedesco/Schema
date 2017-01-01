<?php
session_start();
include("functions.php");
function mod($selected,$mod_field,$table,$id_type,$query)
{
	mysql_select_db('Telemedicina');
	$select = $_SESSION['selected'];
	if ($query == '')
		$query = "UPDATE  ".$table." SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
	$result = mysql_query ($query);
	$_SESSION["_".$select.""] = $mod_field;
	if ($result)
	{
		if ($_SESSION['admin'] || $_SESSION['operator'])
			header("Refresh: 0; URL=../Schema/anagrafica_p.php?tipo=".$id_type."&id=".$_SESSION['ID']."");
		else
			header("Refresh: 0; URL=../Schema/anagrafica_p.php?tipo=".$id_type."&case=1");
	}
	else
		echo ("Errore Sql: ".mysql_error());
}
if ($conn = connection())
{
	switch ($_REQUEST['tipo'])
	{
		case 'id_paziente':
			$id_type = 'id_paziente';
			$t_utente = 3;
			$table = 'Anagrafica_paziente';
			break;
			
		case 'id_operatore':
			$id_type = 'id_operatore';
			$t_utente = 2;
			$table = 'Anagrafica_operatore';
			break;
	}
	$mod_field = $_POST['mod_field'];
	$select = $_SESSION['selected'];
	$query = '';
	switch ($select)
	{
		case 2:
			$selected = 'nome';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
				mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 3:
			$selected = 'cognome';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
				mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 4:
			$selected = 'data_nascita';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
			{
				$cdate = explode('-', $mod_field);//scompongo la data
				$check_ko = check_date($cdate[1],$cdate[0],$cdate[2],$sdate);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=1&tipo=".$_SESSION['t_utente']."");
				else
				{
					$mod_field = $cdate[2].'-'.$cdate[1].'-'.$cdate[0];
					$query = "UPDATE  ".$table." SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
					mod($selected,$mod_field,$table,$id_type,$query);
				}
			}
			break;
		case 5:
			$selected = 'comune_nascita';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
				mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 6:
			$selected = 'sesso';
			mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 7:
			$selected = 'codice_fiscale';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
			{
				$check_ko = check_cf($mod_field);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err_cf&case=1&tipo=".$_SESSION['t_utente']."");
				else
					mod($selected,$mod_field,$table,$id_type,$query);
			}
			break;
		case 8:
			$selected = 'indirizzo';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
				mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 9:
			$selected = 'cap';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
			{
				$check_ko = check_cap($mod_field);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err_cap&case=1&tipo=".$_SESSION['t_utente']."");
				else
					mod($selected,$mod_field,$table,$id_type,$query);
			}
			break;
		case 10:
			$selected = 'città';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
				mod($selected,$mod_field,$table,$id_type,$query);
			break;
		case 11:
			$selected = 'numero_telefono';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
			{
				$check_ko = check_tel($mod_field);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err_tel&case=1&tipo=".$_SESSION['t_utente']."");
				else
				{
					mod($selected,$mod_field,$table,$id_type,$query);
				}
			}
			break;
		case 12:
			$selected = 'email';
			$check_ko = check_email($mod_field);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err_mail&case=1&tipo=".$_SESSION['t_utente']."");
				else
					mod($selected,$mod_field,$table,$id_type,$query);
			break;
		
		case 15:
			$selected = 'Q_neurologico';
			$query = "UPDATE  Cartella_clinica SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
			mod($selected,$mod_field,$table,$id_type,$query);
			break;
			
		case 16:
			$selected = 'Q_psicologico';
			$query = "UPDATE  Cartella_clinica SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
			mod($selected,$mod_field,$table,$id_type,$query);
			break;
			
		case 17:
			$selected = 'terapia';
			$query = "UPDATE  Cartella_clinica SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
			mod($selected,$mod_field,$table,$id_type,$query);
			break;
			
		case 18:
			$selected = 'data_riscontro';
			$check_ko = check_presenza($mod_field);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=".$_SESSION['t_utente']."");
			else
			{
				$cdate1 = explode('-', $mod_field);//scompongo la data
				$check_ko = check_date($cdate1[1],$cdate1[0],$cdate1[2],$sdate1);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=1&tipo=".$_SESSION['t_utente']."");
				else
				{
					$mod_field = $cdate1[2].'-'.$cdate1[1].'-'.$cdate1[0];
					$query = "UPDATE  Cartella_clinica SET  ".$selected." =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
					mod($selected,$mod_field,$table,$id_type,$query);
				}
			}
	}
}
connection_close($conn);
?>