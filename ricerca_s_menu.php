<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$check_ko = false;
	$query = "SELECT * FROM Strumentazioni";
	mysql_select_db('Telemedicina');
	$result = mysql_query ($query);		
	$n_rows=mysql_num_rows($result);
	echo mysql_error();
	if ($_GET['id'])
		$_SESSION['id_ins_m'] = $_GET['id'];
	else
		$_SESSION['id_ins_m'] = $_SESSION['id'];
	if ($n_rows > 0)
	{
		$_SESSION['option'.$count_ricerca_s]='';
		$count_ricerca_s = 0;
		while($count_ricerca_s != $n_rows)
		{
			$riga=mysql_fetch_assoc($result);
			$_SESSION['modello'.$count_ricerca_s] = $riga['modello'];
			if ($riga['id'] != 0)
				$_SESSION['option']=$_SESSION["option"]."<option value=".$riga['id'].">".$_SESSION['modello'.$count_ricerca_s]."</option>";
			$count_ricerca_s++;
		}
		$_SESSION['count_ricerca_s']=$count_ricerca_s-1;
		header("Refresh: 0; URL=../Schema/index.php?case=get_s");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=4");
}
connection_close($conn);
?>