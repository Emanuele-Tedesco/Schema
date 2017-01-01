<?php
include ("functions.php");
session_start();
$path = basename( $_FILES['file']['name']);//creo il path per l'immagine
if ($path != null)
{
	$mod_field = "immagini/".$path."";
	move_uploaded_file($_FILES["file"]["tmp_name"], $mod_field);//upload del file
}
else
	$mod_field = 'immagini/default.jpg';
if ($conn = connection())
{
	switch ($_REQUEST['tipo'])
	{
		case 'id_paziente':
			$id_type = 'id_paziente';
			$table = 'Anagrafica_paziente';
			break;
			
		case 'id_operatore':
			$id_type = 'id_operatore';
			$table = 'Anagrafica_operatore';
			break;
	}
	mysql_select_db('Telemedicina');
	$query = "UPDATE  ".$table." SET  immagine =  '".$mod_field."' WHERE  $id_type = '".$_SESSION['ID']."'";
	$result = mysql_query ($query);
	$select = $_SESSION['selected'];
	$_SESSION["_".$select.""] = $mod_field;
	if ($result)
	{
		if ($_SESSION['admin'])
			header("Refresh: 0; URL=../Schema/anagrafica_p.php?tipo=".$id_type."&id=".$_SESSION['ID']."");
		else
			header("Refresh: 0; URL=../Schema/anagrafica_p.php?tipo=".$id_type."&case=1");
	}
	else
		echo ("Errore Sql: ".mysql_error());
}
connection_close($conn);
?>