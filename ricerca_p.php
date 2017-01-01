<?php
session_start();
include ("functions.php");
$user = $_SESSION['user'];
function multiplo($n_rows,$result,$id_type)
{
	$_SESSION['paziente'.$count]='';
	$count = 0;
	while($count != $n_rows)
	{
		$riga=mysql_fetch_assoc($result);
		$_SESSION['username'.$count] = $riga['username'];
		$_SESSION['nome'.$count] = $riga['nome'];
		$_SESSION['cognome'.$count] = $riga['cognome'];
		$data = explode('-',$riga['data_nascita']);
		$_SESSION['data_nascita'.$count] = $data[2].'-'.$data[1].'-'.$data[0];
		$_SESSION['codice_fiscale'.$count] = $riga['codice_fiscale'];
		$data1 = explode(' ',$riga['data_registrazione']);
		$ora = $data1[1];
		$data1 = explode('-',$data1[0]);
		$_SESSION['registrazione'.$count] = $data1[2].'-'.$data1[1].'-'.$data1[0].' '.$ora;
		$_SESSION['id_specifico'.$count] = $riga[$id_type];
		if ($_SESSION['ins_m'])
			$_SESSION['paziente']=$_SESSION["paziente"]."<tr><td>[username".$count."]</td><td>[nome".$count."]</td><td>[cognome".$count."]</td><td>[data_nascita".$count."]</td><td>[codice_fiscale".$count."]</td><td>[registrazione".$count."]</td><td><a href="."ricerca_s_menu.php?id=".$_SESSION['id_specifico'.$count].""." title="."inserisci".">Inserisci misurazione</a></td></tr>";
		elseif ($_SESSION['vis_m'])
			$_SESSION['paziente']=$_SESSION["paziente"]."<tr><td>[username".$count."]</td><td>[nome".$count."]</td><td>[cognome".$count."]</td><td>[data_nascita".$count."]</td><td>[codice_fiscale".$count."]</td><td>[registrazione".$count."]</td><td><a href="."visualizza_m.php?name=".$riga['nome']."&surname=".$riga['cognome']."&id=".$_SESSION['id_specifico'.$count].""." title="."Visualizza misurazioni".">Visualizza misurazioni</a></td></tr>";
		elseif ($_SESSION['invia_mess'])
			$_SESSION['paziente']=$_SESSION["paziente"]."<tr><td>[username".$count."]</td><td>[nome".$count."]</td><td>[cognome".$count."]</td><td>[data_nascita".$count."]</td><td>[codice_fiscale".$count."]</td><td>[registrazione".$count."]</td><td><a href="."ricerca_contatti.php?name=".$riga['nome']."&surname=".$riga['cognome']."&id=".$_SESSION['id_specifico'.$count].""." title="."Invia messaggio".">Invia messaggio</a></td></tr>";
		else
			$_SESSION['paziente']=$_SESSION["paziente"]."<tr><td>[username".$count."]</td><td>[nome".$count."]</td><td>[cognome".$count."]</td><td>[data_nascita".$count."]</td><td>[codice_fiscale".$count."]</td><td>[registrazione".$count."]</td><td><a href="."anagrafica_p.php?tipo=".$id_type."&id=".$_SESSION['id_specifico'.$count]." title="."Visualizza anagrafica".">Visualizza angrafica</a></td></tr>";
		$count++;
	}
	$_SESSION['count']=$count-1;
	header("Refresh: 0; URL=../Schema/index.php?case=research_done");
}
function singolo($result,$id_type)
{
	$riga=mysql_fetch_assoc($result);
	$_SESSION['id_specifico'] = $riga[$id_type];
	$_SESSION['id_specifico1'] = $_SESSION['id_specifico'];
	$_SESSION['name'] = $riga['nome'];
	$_SESSION['surname'] = $riga['cognome'];
	if ($_SESSION['ins_m'])
		header("Refresh: 0; URL=../Schema/ricerca_s_menu.php?id=".$_SESSION['id_specifico']."");
	elseif ($_SESSION['vis_m'])
		header("Refresh: 0; URL=../Schema/visualizza_m.php?name=".$riga['nome']."&surname=".$riga['cognome']."&id=".$_SESSION['id_specifico']."");
	elseif ($_SESSION['invia_mess'])
		header("Refresh: 0; URL=../Schema/ricerca_contatti.php?name=".$_SESSION['name']."&surname=".$_SESSION['surname']."&id=".$_SESSION['id_specifico']."");
	else	
		header("Refresh: 0; URL=../Schema/anagrafica_p.php?tipo=".$id_type."&id=".$_SESSION['id_specifico']."");
}
if ($conn = connection())
{
	$t_utente = $_SESSION['t_utente'];
	if ($t_utente == 2)
	{
		$_SESSION['id_type'] = 'id_operatore';
		$table = 'Anagrafica_operatore';
		$_SESSION['invia_mess'] = false;
		$_SESSION['ins_m'] = false;
		$_SESSION['vis_m'] = false;
	}
	elseif ($t_utente == 3)
	{
		$_SESSION['id_type'] = 'id_paziente';
		$table = 'Anagrafica_paziente';
	}
	$check_ko = false;
	$type = $_POST['type'];
	$campo_r = $_POST['campo_r'];
	if ($type == 1)
		$type_sel = 'codice_fiscale';
	else
		$type_sel = 'cognome';
	if (!$campo_r)
	{
		$check_ko = true;
		if ($_SESSION['ins_m'])
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=5");
		elseif ($_SESSION['vis_m'])
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=6");
		elseif ($_SESSION['invia_mess'])
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=9");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=$t_utente");
	}
	mysql_select_db('Telemedicina');
	$query = "SELECT * FROM $table WHERE $type_sel = '$campo_r'";
	$result = mysql_query ($query);
	if (!$check_ko)
	{
		if ($result)
		{
			$n_rows=mysql_num_rows($result);
			if  ($n_rows>1)
				multiplo($n_rows,$result,$_SESSION['id_type']);
			elseif ($n_rows==1)
				singolo($result,$_SESSION['id_type']);
			elseif ($n_rows==0)
			{
				if ($_SESSION['ins_m'])
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=5");
				elseif ($_SESSION['vis_m'])
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=6");
				elseif ($_SESSION['invia_mess'])
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=11");
				else
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=$t_utente");
			}
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_sql");
	}
}
connection_close($conn);
?>