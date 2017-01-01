<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$check_ko = false;
	$type = $_POST['type'];
	$campo_r = $_POST['campo_r'];
	switch ($type)
	{
		case 'seriale':
			$query = "SELECT * FROM Strumentazioni WHERE $type = '$campo_r'";
			break;
			
		case 'costruttore':
			$query = "SELECT * FROM Strumentazioni WHERE $type = '$campo_r'";
			break;
			
		default:
			$query = "SELECT * FROM Strumentazioni";
			break;
	}
	if ($type != '*')
		if (!$campo_r)
		{
			$check_ko = true;
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=4");
		}
	mysql_select_db('Telemedicina');
	if (!$check_ko)
	{	
		$result = mysql_query ($query);		
		$n_rows=mysql_num_rows($result);
		echo mysql_error();
		if ($n_rows > 0)
		{
			$_SESSION['stamparighe'.$count_ricerca_s]='';
			$count_ricerca_s = 0;
			while($count_ricerca_s != $n_rows)
			{
				$riga=mysql_fetch_assoc($result);
				$_SESSION['seriale'.$count_ricerca_s] = $riga['seriale'];
				$_SESSION['modello'.$count_ricerca_s] = $riga['modello'];
				$_SESSION['costruttore'.$count_ricerca_s] = $riga['costruttore'];
				$_SESSION['descrizione'.$count_ricerca_s] = $riga['descrizione'];
				if ($riga['id'] != 0 && isset($_SESSION['admin']))
					$_SESSION['stamparighe']=$_SESSION["stamparighe"]."<tr><td>[seriale".$count_ricerca_s."]</td><td>[modello".$count_ricerca_s."]</td><td>[costruttore".$count_ricerca_s."]</td><td>[descrizione".$count_ricerca_s."]</td><td><a href='index.php?refresh=conf_el_s&id=".$riga['id']."&seriale=".$riga['seriale']."&modello=".$riga['modello']."' title="."Elimina".">Elimina</a></td></tr>";
				elseif ($riga['id'] != 0)
					$_SESSION['stamparighe']=$_SESSION["stamparighe"]."<tr><td>[seriale".$count_ricerca_s."]</td><td>[modello".$count_ricerca_s."]</td><td>[costruttore".$count_ricerca_s."]</td><td>[descrizione".$count_ricerca_s."]</td></tr>";
				$count_ricerca_s++;
			}
			$_SESSION['count_ricerca_s']=$count_ricerca_s-1;
			header("Refresh: 0; URL=../Schema/index.php?case=research_done_s");
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=4");
	}
}
connection_close($conn);
?>