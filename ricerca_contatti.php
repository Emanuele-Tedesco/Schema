<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$check_ko = false;
	$name = $_REQUEST['name'];
	$surname = $_REQUEST['surname'];
	$id = $_REQUEST['id'];
	echo $query = "SELECT numero_telefono, email FROM Anagrafica_paziente where id_paziente = '$id'";
	mysql_select_db('Telemedicina');
	$rs = mysql_query ($query);	
	$n_rows=mysql_num_rows($rs);
	echo mysql_error();
	$rs=mysql_fetch_assoc($rs);
	$email = $rs['email'];
	$telefono = $rs['numero_telefono'];
	if ($email != '')
		$_SESSION['email']="<option value=".$email." selected="."selected".">Email</option>";
	else
		unset($_SESSION['email']);
	$_SESSION['tel']="<option value=".$telefono.">Telefono</option>";
	header("Refresh: 0; URL=../Schema/index.php?case=invia_mess&name=".$name."&surname=".$surname."&id=".$id."");
}
connection_close($conn);
?>