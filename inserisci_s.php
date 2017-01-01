<?php
session_start();
include("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina',$conn);
	$campi = array();
	$campi[0] = $_POST['seriale'];
	$campi[1] = $_POST['modello'];
	$campi[2] = $_POST['costruttore'];
	$campi[3] = mysql_escape_string($_POST['descrizione']);
	$check_ko = false;//valore di controllo sulla coerenza dei campi in seriti
	$check_ko = check_presenza($campi);
	if ($check_ko)
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_campi&tipo=ins_s");
	else
	{
		$check_ko = check_cf($campi[0]);
		if ($check_ko)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_seriale");
		else
		{
			$check_ko = check_len_descr($campi[3]);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_lung_descr");
			else
			{
				$query1 = "INSERT INTO `Strumentazioni` (`id`, `seriale`, `modello`, `costruttore`, `descrizione`) VALUES (NULL, '$campi[0]', '$campi[1]', '$campi[2]', '$campi[3]' )";
				$result = mysql_query($query1);
				if (!$result)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err_query_s");
				else
				{
					header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=s");
				}
			}
		}				
	}
}
connection_close($conn);
?>