<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$check_ko = true;
	$id = $_SESSION['id_specifico1'];
	mysql_select_db('Telemedicina');
	if (!empty($_POST['dispositivo']) && !empty($_POST['data']))//entrambi i filtri
	{
		$type = $_POST['type'];
		if (($campo_f = $_POST['campo_f']) && ($range_data1 = $_POST['range_data1']) && ($range_data2 = $_POST['range_data2']))
		{
			$check_ko = check_presenza($campo_f);
			if($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=3");
			else
			{
				$range_1 = explode('-', $range_data1);
				$range_2 = explode('-', $range_data2);
				$check_data1 = mktime(0,0,0,$range_1[1],$range_1[0],$range_1[2],0);
				$check_data2 = mktime(23,59,59,$range_2[1],$range_2[0],$range_2[2]);
				$check_data2 = date('Y-m-d H:i:s',$check_data2);
				$check_ko = check_date($range_1[1],$range_1[0],$range_1[2],$check_data1);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=3");
				else
				{
					$check_ko = check_date($range_2[1],$range_2[0],$range_2[2],$check_data2);
					if ($check_ko)
						header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=3");
				}
				$range_data1 = $range_1[2].'-'.$range_1[1].'-'.$range_1[0];
				$range_data2 = $range_2[2].'-'.$range_2[1].'-'.$range_2[0];
				$type = $_POST['type'];
				$query = "SELECT  Anagrafica_paziente.id_paziente, Misurazioni.id , nome,  path, cognome,  modello,  costruttore,  Misurazioni.data 
					FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
					WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id 
					AND Anagrafica_paziente.id_paziente = '$id'
					AND Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
					AND $type = '$campo_f'
					AND data >= '$range_data1' AND  data <= '$check_data2'
					order by data DESC";
			}
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=8");
	}
	elseif(!empty($_POST['dispositivo']) && empty($_POST['data']))//filtro dispositivo
	{
		if ($campo_f = $_POST['campo_f'])
		{
			$check_ko = check_presenza($campo_f);
			if($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
			else
			{
				$type = $_POST['type'];
				$query = "SELECT   Anagrafica_paziente.id_paziente, Misurazioni.id , nome,  path, cognome,  modello,  costruttore,  Misurazioni.data 
					FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
					WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id
					AND Anagrafica_paziente.id_paziente = '$id'
					AND  Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
					AND $type = '$campo_f'
					order by data DESC";
			}
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=8");
	}
	elseif(empty($_POST['dispositivo']) && !empty($_POST['data']))//filtro data
	{
		if (($range_data1 = $_POST['range_data1']) && ($range_data2 = $_POST['range_data2']))
		{
			$range_1 = explode('-', $range_data1);
			$range_2 = explode('-', $range_data2);
			$check_data1 = mktime(0,0,0,$range_1[1],$range_1[0],$range_1[2],0);
			$check_data2 = mktime(23,59,59,$range_2[1],$range_2[0],$range_2[2]);
			$check_data2 = date('Y-m-d H:i:s',$check_data2);
			$check_ko = check_date($range_1[1],$range_1[0],$range_1[2],$check_data1);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=3");
			else
			{
				$check_ko = check_date($range_2[1],$range_2[0],$range_2[2],$check_data2);
				if ($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=3");
			}
			$range_data1 = $range_1[2].'-'.$range_1[1].'-'.$range_1[0];
			$range_data2 = $range_2[2].'-'.$range_2[1].'-'.$range_2[0];
			$query = "SELECT   Anagrafica_paziente.id_paziente, Misurazioni.id , nome,  path, cognome,  modello,  costruttore,  Misurazioni.data
				FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
				WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id
				AND  Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
				AND Anagrafica_paziente.id_paziente = '$id'
				AND data >= '$range_data1' 
				AND  data <= '$check_data2'
				order by data DESC";
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=8");
	}
	else
		header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_check_filtro&type=1");
	if(!$check_ko)
	{	
		$result = mysql_query ($query);
		if (($n_rows=mysql_num_rows($result)) > 0)
		{
			$_SESSION['visualizza_m'.$count_vis_m]='';
			$count_vis_m = 0;
			while($count_vis_m != $n_rows)
			{
				$rs=mysql_fetch_assoc($result);
				$_SESSION['name'] = $rs['nome'];
				$_SESSION['surname'] = $rs['cognome'];
				$_SESSION['costruttore'.$count_vis_m] = $rs['costruttore'];
				if ($_SESSION['costruttore'.$count_vis_m] == 'NULL')
					$_SESSION['costruttore'.$count_vis_m] = 'Non specificato';
				$_SESSION['modello'.$count_vis_m] = $rs['modello'];
				if ($_SESSION['modello'.$count_vis_m] == 'NULL')
					$_SESSION['modello'.$count_vis_m] = 'Dispositivo non più presente';
				$_SESSION['path'.$count_vis_m] = $rs['path'];
				$data1 = explode(' ',$rs['data']);
				$ora = " alle ".$data1[1];
				$data1 = explode('-',$data1[0]);
				$data = $_SESSION['data'.$count_vis_m] = $data1[2].'-'.$data1[1].'-'.$data1[0].' '.$ora;
				$_SESSION['visualizza_m']=$_SESSION["visualizza_m"]."<tr><td>[modello".$count_vis_m."]</td><td>[costruttore".$count_vis_m."]</td><td>[path".$count_vis_m."]</td><td>[data".$count_vis_m."]</td><td><a href='misurazione.php?case=read&name=".$rs['nome']."&surname=".$rs['cognome']."&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."' title="."Visualizza".">Visualizza</a></td><td><a href='misurazione.php?case=download&name=".$rs['nome']."&surname=".$rs['cognome']."&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."' title="."Download".">Download</a></td><td><a href='index.php?refresh=conf_el_m&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."&data=".$data."' title="."Elimina".">Elimina</a></td></tr>";
				$count_vis_m++;
			}
			$_SESSION['count_vis_m']=$count_vis_m-1;
			header("Refresh: 0; URL=../Schema/index.php?case=visualizza_m&name=".$rs['nome']."&surname=".$rs['cognome']."&id=".$_SESSION['id_specifico1']."");
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=8");
	}
	else
		echo 'ko';
}
connection_close($conn);
?>