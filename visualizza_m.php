<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	$name = $_GET['name'];
	$surname = $_GET['surname'];
	$check_ko = true;
	$case = false;
	mysql_select_db('Telemedicina');
	switch ($_REQUEST['case'])
	{		
		case 'tot':
			$filtro = false;
			$tot = true;
			$case = true;
			break;
			
		case 'filtro':
			$tot = false;
			$filtro = true;
			$case = true;
			break;

	}
	if (!$case)
	{
		$check_ko = false;
		$_SESSION['id_specifico1'] = $id = $_GET['id'];
		$query = "SELECT `path`, `data`, `id_paziente`, `Misurazioni`.`id`, `modello`, costruttore
			FROM `Misurazioni`, `Strumentazioni` 
			WHERE `id_paziente` = '$id' and `Misurazioni`.`id_strumentazione` = `Strumentazioni`.`id`
			order by data DESC";
	}
	elseif($tot)
	{
		$check_ko = false;
		$query = "SELECT  `Anagrafica_paziente`.`id_paziente`, `username`, `Misurazioni`.`id` , `nome`,  `cognome`,  `codice_fiscale` ,  `modello`,  `costruttore`,  `data` 
			FROM  `Anagrafica_paziente` ,  `Misurazioni` ,  `Strumentazioni` 
			WHERE  `Misurazioni`.`id_strumentazione` =  `Strumentazioni`.`id` 
			AND  `Misurazioni`.`id_paziente` =  `Anagrafica_paziente`.`id_paziente`
			order by data DESC";
	}
	elseif($filtro)
	{
		if (!empty($_POST['dispositivo']) && !empty($_POST['data']))//entrambi i filtri
		{
			$type = $_POST['type'];
			if (($campo_f = $_POST['campo_f']) && ($range_data1 = $_POST['range_data1']) && ($range_data2 = $_POST['range_data2']))
			{
				$check_ko = check_presenza($campo_f);
				if($check_ko)
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
				else
				{
					$range_1 = explode('-', $range_data1);
					$range_2 = explode('-', $range_data2);
					$check_data1 = mktime(0,0,0,$range_1[1],$range_1[0],$range_1[2],0);
					$check_data2 = mktime(23,59,59,$range_2[1],$range_2[0],$range_2[2]);
					$check_data2 = date('Y-m-d H:i:s',$check_data2);
					$check_ko = check_date($range_1[1],$range_1[0],$range_1[2],$check_data1);
					if ($check_ko)
						header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
					else
					{
						$check_ko = check_date($range_2[1],$range_2[0],$range_2[2],$check_data2);
						if ($check_ko)
							header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
					}
					$range_data1 = $range_1[2].'-'.$range_1[1].'-'.$range_1[0];
					$range_data2 = $range_2[2].'-'.$range_2[1].'-'.$range_2[0];
					$type = $_POST['type'];
					$query = "SELECT  Anagrafica_paziente.id_paziente, username, Misurazioni.id , nome,  cognome,  codice_fiscale ,  modello,  costruttore,  data 
						FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
						WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id 
						AND  Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
						AND $type = '$campo_f'
						AND data >= '$range_data1' AND  data <= '$check_data2'
						order by data DESC";
				}
			}
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=7");
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
					$query = "SELECT  Anagrafica_paziente.id_paziente, username, Misurazioni.id , nome,  cognome,  codice_fiscale ,  modello,  costruttore,  data 
						FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
						WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id 
						AND  Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
						AND $type = '$campo_f'
						order by data DESC";
				}
			}
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=7");
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
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
				else
				{
					$check_ko = check_date($range_2[1],$range_2[0],$range_2[2],$check_data2);
					if ($check_ko)
						header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=2");
				}
				$range_data1 = $range_1[2].'-'.$range_1[1].'-'.$range_1[0];
				$range_data2 = $range_2[2].'-'.$range_2[1].'-'.$range_2[0];
				$query = "SELECT  Anagrafica_paziente.id_paziente, username, Misurazioni.id , nome,  cognome,  codice_fiscale ,  modello,  costruttore,  data 
					FROM  Anagrafica_paziente ,  Misurazioni ,  Strumentazioni 
					WHERE  Misurazioni.id_strumentazione =  Strumentazioni.id 
					AND  Misurazioni.id_paziente =  Anagrafica_paziente.id_paziente 
					AND data >= '$range_data1' 
					AND  data <= '$check_data2'
					order by data DESC";
			}
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_campo_ric&utente=7");
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_check_filtro");
	}
	if(!$check_ko)
	{	
		$result = mysql_query ($query);
		if (($n_rows=mysql_num_rows($result)) > 0)
		{
			if(!$case)
			{
				$_SESSION['visualizza_m']='';
				$count_vis_m = 0;
				while($count_vis_m != $n_rows)
				{
					$rs=mysql_fetch_assoc($result);
					$_SESSION['modello'.$count_vis_m] = $rs['modello'];
					if ($_SESSION['modello'.$count_vis_m] == 'NULL')
						$_SESSION['modello'.$count_vis_m] = 'Dispositivo non più presente';
					$_SESSION['costruttore'.$count_vis_m] = $rs['costruttore'];
					if ($_SESSION['costruttore'.$count_vis_m] == 'NULL')
						$_SESSION['costruttore'.$count_vis_m] = 'Dispositivo non più presente';
					$_SESSION['path'.$count_vis_m] = $rs['path'];
					$data1 = explode(' ',$rs['data']);
					$ora = " alle ".$data1[1];
					$data1 = explode('-',$data1[0]);
					$data = $_SESSION['data'.$count_vis_m] = $data1[2].'-'.$data1[1].'-'.$data1[0].' '.$ora;
					$_SESSION['visualizza_m']=$_SESSION["visualizza_m"]."<tr><td>[modello".$count_vis_m."]</td><td>[costruttore".$count_vis_m."]</td><td>[path".$count_vis_m."]</td><td>[data".$count_vis_m."]</td><td><a href='misurazione.php?case=read&name=".$name."&surname=".$surname."&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."' title="."Visualizza".">Visualizza</a></td><td><a href='misurazione.php?case=download&name=".$rs['nome']."&surname=".$rs['cognome']."&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."' title="."Download".">Download</a></td><td><a href='index.php?refresh=conf_el_m&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."&data=".$data."' title="."Elimina".">Elimina</a></td></tr>";
					$count_vis_m++;
				}
				$_SESSION['count_vis_m']=$count_vis_m-1;
				header("Refresh: 0; URL=../Schema/index.php?case=visualizza_m&name=".$name."&surname=".$surname."&id=".$id."");
			}
			else
			{
				$_SESSION['visualizza_m_tot']='';
				$count_vis_p_m = 0;
				while($count_vis_p_m != $n_rows)
				{
					$rs=mysql_fetch_assoc($result);
					$_SESSION['nome'.$count_vis_p_m] = $rs['nome'];
					$_SESSION['costruttore'.$count_vis_p_m] = $rs['costruttore'];
					$_SESSION['cognome'.$count_vis_p_m] = $rs['cognome'];
					$_SESSION['codice_fiscale'.$count_vis_p_m] = $rs['codice_fiscale'];
					$_SESSION['modello'.$count_vis_p_m] = $rs['modello'];
					if ($_SESSION['modello'.$count_vis_p_m] == 'NULL')
						$_SESSION['modello'.$count_vis_p_m] = 'Dispositivo non più presente';
					$data1 = explode(' ',$rs['data']);
					$ora = " alle ".$data1[1];
					$data1 = explode('-',$data1[0]);
					$data = $_SESSION['data'.$count_vis_p_m] = $data1[2].'-'.$data1[1].'-'.$data1[0].' '.$ora;
					$_SESSION['visualizza_m_tot']=$_SESSION["visualizza_m_tot"]."<tr><td>[nome".$count_vis_p_m."]</td><td>[cognome".$count_vis_p_m."]</td><td>[codice_fiscale".$count_vis_p_m."]</td><td>[modello".$count_vis_p_m."]</td><td>[costruttore".$count_vis_p_m."]</td><td>[data".$count_vis_p_m."]</td><td><a href='misurazione.php?case=read&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."&name=".$rs['nome']."&surname=".$rs['cognome']."' title="."Visualizza".">Visualizza</a></td><td><a href='misurazione.php?case=download&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."' title="."Download".">Download</a></td><td><a href='index.php?refresh=conf_el_m2&id_p=".$rs['id_paziente']."&id_m=".$rs['id']."&data=".$data."&name=".$rs['nome']."&surname=".$rs['cognome']."' title="."Elimina".">Elimina</a></td></tr>";
					$count_vis_p_m++;
				}
				$_SESSION['count_vis_p_m']=$count_vis_p_m-1;
				header("Refresh: 0; URL=../Schema/index.php?case=vis_p_m");
			}
		}
		else
			if (!$case)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_mis&caso=1");
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=7&tipo=1");
	}
	else
		echo 'ko';
}
connection_close($conn);
?>