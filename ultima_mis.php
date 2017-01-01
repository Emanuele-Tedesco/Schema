<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$id = $_SESSION['id'];
	$name = $_SESSION['nome_paziente'];
	$surname = $_SESSION['cognome_paziente'];
	mysql_select_db('Telemedicina');
	$query = "SELECT  `path` ,  `data` ,  `id_paziente` ,  `Misurazioni`.`id` ,  `modello` , costruttore
		FROM  `Misurazioni` ,  `Strumentazioni` 
		WHERE  `id_paziente` =  '$id'
		AND Misurazioni.id_strumentazione = Strumentazioni.id
		ORDER BY data DESC 
		LIMIT 1";
	$result = mysql_query ($query);
	if (($n_rows=mysql_num_rows($result)) > 0)
	{
		$rs=mysql_fetch_assoc($result);
		header("Refresh: 0; URL=../Schema/misurazione.php?case=read&name=".$name."&surname=".$surname."&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_mis&caso=2");
}
connection_close($conn);
?>