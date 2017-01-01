<?php
session_start();
include("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$id = $_SESSION['id_ins_m'];
	$type = $_POST['type'];
	$path = ''. basename( $_FILES['file']['name']);
	if ($path == '')
		header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_nn_file_m");
	else
	{
		$data = addslashes(fread(fopen($_FILES["file"]["tmp_name"], "rb"), $_FILES["file"]["size"]));
		$query = "INSERT INTO `Misurazioni` (`id`, `id_paziente`, `id_strumentazione`, `tipo_misurazione`, `file`, `path`, `data`) VALUES (NULL, '$id', '$type', NULL, '$data', '$path', CURRENT_TIMESTAMP )";
		$result = mysql_query($query);
		echo mysql_error();
		if (!$result)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&tipo=err_q_ins_m");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=m");
	}
	unset($_SESSION['id_ins_m']);
}
connection_close($conn);
?>