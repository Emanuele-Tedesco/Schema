<?
session_start();
include("functions.php");
//array che contengono i template delle pagine (impostazione generale)
$template=array();
//i vari contenuti della pagina(login,menu,corpo)
$contenuti=array();
//questo imposta la pagina generale e richiama un file in cui c'Ã¨ l'impostazione della pagina e dove devono essere inseriti i contenuti
$template['pagina_generale']=file_get_contents('../Schema/template/index.php');


//questo Ã¨ come Ã¨ costituito il menu
$contenuti['menu']=file_get_contents('../Schema/contenuti/navig.pag');
$contenuti['login']=file_get_contents('../Schema/contenuti/login.pag');

//in funzione di ciÃ² che viene cliccato si apre la pagina php specificata
switch ($_REQUEST['pag'])
{
	case 'servizi': 
		$contenuti['corpo']=file_get_contents('contenuti/servizi.php');
		$contenuti['path']="<b>\"Servizi Offerti\"</b>";
		break;		

	case 'progetti': 
		$contenuti['corpo']=file_get_contents('contenuti/progetti.pag');
		$contenuti['path']="<b>\"Progetti & Ricerca\"</b>";
		break;

	case 'stampa': 
		$contenuti['corpo']=file_get_contents('contenuti/stampa.pag');
		$contenuti['login']=file_get_contents('../Schema/contenuti/login.pag');
		$contenuti['path']="<b>\"Rassegna Stampa\"</b>";
		break;		

	case 'contatti': 
		$contenuti['corpo']=file_get_contents('contenuti/contatti.pag');
		$contenuti['path']="<b>\"Contatti\"</b>";
		break;

	default://homepage
		$contenuti['corpo']=file_get_contents('contenuti/telemed.pag');
		$contenuti['path']="<b>\"La Telemedicina\"</b>";
		break;
}
switch ($_REQUEST['case'])
{
	case 'recupera_passwd':
		$contenuti['corpo']=file_get_contents('contenuti/recupero_passwd.pag');
		$contenuti['path']="<b>\"Recupero password\"</b>";
		break;
		
	case 'invia_mess':
		$contenuti['corpo']=file_get_contents('contenuti/componi_mess.pag');
		$contenuti['path']="<b>\"Invia messaggio a [cognome] [nome]\"</b>";
		$_SESSION['id_paz_sms'] = $_REQUEST['id'];
		$_SESSION['surname_mess'] = $_REQUEST['surname'];
		$_SESSION['name_mess'] = $_REQUEST['name'];
		$contenuti['path']=str_replace("[cognome]",$_SESSION['surname_mess'],$contenuti['path']);
		$contenuti['path']=str_replace("[nome]",$_SESSION['name_mess'],$contenuti['path']);
		$contenuti['corpo']=str_replace("[tel]",$_SESSION['tel'],$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[email]",$_SESSION['email'],$contenuti['corpo']);
		break;
		
	case 'recupero_pass':
		if ($_SESSION['id_rec_pass'][1] == '')
			$_SESSION['id_rec_pass'] = explode('-',($id = base64_decode($_REQUEST['valore'])));//decodifica il link generato per la mail di modifica passwrd
		$contenuti['corpo']=file_get_contents('contenuti/ins_new_passwd.pag');
		$contenuti['path']="<b>\"Modifica password\"</b>";
		break;
		
	case 'recupera_username':
		$contenuti['corpo']=file_get_contents('contenuti/recupero_username.pag');
		$contenuti['path']="<b>\"Recupero nome utente\"</b>";
		break;
		
	case 'recupero_us':
		if ($_SESSION['id_rec_usr'][1] == '')
			$_SESSION['id_rec_usr'] = explode('-',($id = base64_decode($_REQUEST['valore'])));//decodifica il link genrato per la mail di modifica passwrd
		$contenuti['corpo']=file_get_contents('contenuti/ins_new_username.pag');
		$contenuti['path']="<b>\"Modifica nome utente\"</b>";
		break;
	
	case 'anagrafica_p':
		$contenuti['corpo']=file_get_contents('contenuti/anagrafica_p.pag');
		$contenuti['path']="<b>\"Area personale\"</b>";
		break;
		
	case 'inserisci_s':
		$contenuti['corpo']=file_get_contents('contenuti/inserisci_s.pag');
		$contenuti['path']="<b>\"Aggiungi strumentazione\"</b>";
		break;
		
	case 'get_s':
		$contenuti['corpo']=file_get_contents('contenuti/inserisci_m.pag');
		$contenuti['path']="<b>\"Aggiungi misurazione\"</b>";
		$option = $_SESSION['option'];
		$contenuti['corpo']=str_replace("[option]",$option,$contenuti['corpo']);
		break;
		
	case 'vis_p_m':
		$contenuti['corpo']=file_get_contents('contenuti/vis_p_m.pag');
		$contenuti['path']="<b>\"Panoramica misurazioni\"</b>";
		$contenuti['corpo']=str_replace("[visualizza_m_tot]",$_SESSION['visualizza_m_tot'],$contenuti['corpo']);
		$option1 = $_SESSION['option1'];
		$contenuti['corpo']=str_replace("[option_pan]",$option1,$contenuti['corpo']);
		$count_vis_p_m = $_SESSION['count_vis_p_m'];
		while($count_vis_p_m >= 0 )
		{
			$contenuti['corpo']=str_replace("[nome".$count_vis_p_m."]",$_SESSION['nome'.$count_vis_p_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[cognome".$count_vis_p_m."]",$_SESSION['cognome'.$count_vis_p_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[codice_fiscale".$count_vis_p_m."]",$_SESSION['codice_fiscale'.$count_vis_p_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[modello".$count_vis_p_m."]",$_SESSION['modello'.$count_vis_p_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[costruttore".$count_vis_p_m."]",$_SESSION['costruttore'.$count_vis_p_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[data".$count_vis_p_m."]",$_SESSION['data'.$count_vis_p_m],$contenuti['corpo']);
			$count_vis_p_m --;
		}
		break;
		
	case 'stampa_m':
		$contenuti['corpo']=file_get_contents('contenuti/stampa_m.pag');
		$count = $_SESSION['count'];
		$contenuti['corpo']=str_replace("[visualizza_mis]",$_SESSION['visualizza_mis'],$contenuti['corpo']);
		$contenuti['path']="<b>\"Misurazione di [cognome] [nome] registrata il [data]\"</b>";
		$data_m = $_GET['data'];
		$data_m = explode(' ', $data_m);
		$data_m1 = explode('-', $data_m[0]);
		$data_m = $data_m1[2]."-".$data_m1[1]."-".$data_m1[0]." alle ".$data_m[1];
		$nome = $_GET['name'];
		$cognome = $_GET['surname'];
		$contenuti['path']=str_replace("[nome]",$nome,$contenuti['path']);
		$contenuti['path']=str_replace("[cognome]",$cognome,$contenuti['path']);
		$contenuti['path']=str_replace("[data]",$data_m,$contenuti['path']);
		break;
	
	case 'visualizza_m':
		$contenuti['path']="<b>\"Misurazioni di [cognome] [nome]\"</b>";
		$_SESSION['name_v_m'] = $_GET['name'];
		if ($_SESSION['name_v_m'] == '')
			$_SESSION['name_v_m'] = $_SESSION['nome_paziente'];
		$_SESSION['surname_v_m'] = $_GET['surname'];
		if ($_SESSION['surname_v_m'] == '')
			$_SESSION['surname_v_m'] = $_SESSION['cognome_paziente'];
		if ($_SESSION['id_specifico1'] != '')
			$_SESSION['id_specifico1'] = $_GET['id'];
		$contenuti['path']=str_replace("[cognome]",$_SESSION['surname_v_m'],$contenuti['path']);
		$contenuti['path']=str_replace("[nome]",$_SESSION['name_v_m'],$contenuti['path']);
		$contenuti['corpo']=file_get_contents('contenuti/visualizza_m.pag');
		$count_vis_m = $_SESSION['count_vis_m'];
		$contenuti['corpo']=str_replace("[visualizza_m]",$_SESSION['visualizza_m'],$contenuti['corpo']);
		while($count_vis_m >= 0 )
		{
			$contenuti['corpo']=str_replace("[modello".$count_vis_m."]",$_SESSION['modello'.$count_vis_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[costruttore".$count_vis_m."]",$_SESSION['costruttore'.$count_vis_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[path".$count_vis_m."]",$_SESSION['path'.$count_vis_m],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[data".$count_vis_m."]",$_SESSION['data'.$count_vis_m],$contenuti['corpo']);
			$count_vis_m --;
		}		
		$contenuti['corpo']=str_replace("[form]",'<form name="defiltra" action="visualizza_m.php?name='.$_SESSION['name_v_m'].'&surname='.$_SESSION['surname_v_m'].'&id='.$_SESSION['id_specifico1'].'" method="post">',$contenuti['corpo']);
		break;
	
	case 'research_done1':
		$contenuti['path']="<b>\"Area personale di [utente]\"</b>";
		$contenuti['path']=str_replace("[utente]",$_SESSION['_1'],$contenuti['path']);
		$contenuti['corpo']=file_get_contents('contenuti/anagrafica_p.pag');
		if ($_SESSION['t_utente'] == 2)
			$contenuti['corpo']=str_replace("[ris_amm]",'',$contenuti['corpo']);
		for ($i = 2; $i < 13; $i++)
		{	
			if ($_SESSION['sel'])
			{
				$contenuti['corpo']=str_replace("[form]",'',$contenuti['corpo']);
				$contenuti['corpo']=str_replace("[e]",'',$contenuti['corpo']);
				if ($_SESSION['sel'] == $i)
				{
					if ($_SESSION['sel'] == 6)
						$contenuti['corpo']=str_replace("[$i]",'<form name="input" action="mod_valore_anagrafica_p.php?tipo='.$_SESSION['id_type'].'" method="post"><td><input type="radio" name="mod_field" value=1 checked="checked"/> Uomo <input type="radio" name="mod_field" value=2 /> Donna <td><input type="submit" value="Conferma"/></td></form><form name="input" action="index.php?case=research_done1" method="post"><td><input type="submit" value="Annulla"/></td></form>',$contenuti['corpo']);
					else
						$contenuti['corpo']=str_replace("[$i]",'<form name="input" action="mod_valore_anagrafica_p.php?tipo='.$_SESSION['id_type'].'" method="post"><td><input type="text" name="mod_field" value="'.$_SESSION["_".$_SESSION["sel"].""].'"/><input type="submit" value="Conferma"/></td></form><form name="input" action="index.php?case=research_done1" method="post"><td><input type="submit" value="Annulla"/></td></form>',$contenuti['corpo']);
				}
				else
					$contenuti['corpo']=str_replace("[$i]","<td>".$_SESSION['_'.$i.""].'</td>',$contenuti['corpo']);
			}
			else
			{
				$contenuti['corpo']=str_replace("[form]",'<form name="modifica" action="mod_anagrafica_p.php" method="post">',$contenuti['corpo']);
				$contenuti['corpo']=str_replace("[$i]","<td>".$_SESSION['_'.$i.""].'</td><td><input type="submit" value="Modifica" name="'.$i.'"/></td>',$contenuti['corpo']);
			}
		}
		if ($_SESSION['admin'] || ( $_SESSION['operator'] && $_SESSION['id_type'] == 'id_paziente'))
		{
			$contenuti['corpo']=str_replace("[ris_amm]",'<tr><td>Quadro neurologico:</td>[15]</tr><tr><td>Quadro psicologico:</td>[16]</tr><tr><td>Terapia:</td>[17]</tr><tr><td>Data riscontro patologia:</td>[18]</tr>',$contenuti['corpo']);
			for ($i = 15; $i < 19; $i++)
			{	
				if ($_SESSION['sel'])
				{
					$contenuti['corpo']=str_replace("[form]",'',$contenuti['corpo']);
					$contenuti['corpo']=str_replace("[e]",'',$contenuti['corpo']);
					if ($_SESSION['sel'] == $i)
					{
						if ($_SESSION['sel'] != 18)
							$contenuti['corpo']=str_replace("[$i]",'<form name="input" action="mod_valore_anagrafica_p.php?tipo='.$_SESSION['id_type'].'" method="post"><td><textarea name="mod_field" rows="4" cols="35">'.$_SESSION["_".$_SESSION["sel"].""].'</textarea></td><td><input type="submit" value="Conferma"/></td></form><form name="input" action="index.php?case=research_done1" method="post"><td><input type="submit" value="Annulla"/></td></form>',$contenuti['corpo']);
						else
							$contenuti['corpo']=str_replace("[$i]",'<form name="input" action="mod_valore_anagrafica_p.php?tipo='.$_SESSION['id_type'].'" method="post"><td><input type="text" name="mod_field" value="'.$_SESSION["_".$_SESSION["sel"].""].'"/><input type="submit" value="Conferma"/></td></form><form name="input" action="index.php?case=research_done1" method="post"><td><input type="submit" value="Annulla"/></td></form>',$contenuti['corpo']);
					}
					else
						$contenuti['corpo']=str_replace("[$i]","<td>".$_SESSION['_'.$i.""].'</td>',$contenuti['corpo']);
				}
				else
				{
					$contenuti['corpo']=str_replace("[form]",'<form name="modifica" action="mod_anagrafica_p.php" method="post">',$contenuti['corpo']);
					$contenuti['corpo']=str_replace("[$i]","<td>".$_SESSION['_'.$i.""].'</td><td><input type="submit" value="Modifica" name="'.$i.'"/></td>',$contenuti['corpo']);
				}
			}
		}
		if ($_SESSION['admin'] || ( $_SESSION['operator'] && $_SESSION['id_type'] == 'id_paziente'))
			$contenuti['corpo']=str_replace("[e]",'<form name="input" action="index.php?refresh=conferma_eliminazione_u" method="post" enctype="multipart/form-data"><tr><td></td><td></td><td><input type="submit" value="Elimina utente"/></td></tr></form>',$contenuti['corpo']);
		else
			$contenuti['corpo']=str_replace("[e]",'',$contenuti['corpo']);
		$_SESSION['selected']=$_SESSION['sel'];
		unset($_SESSION['sel']);
		$contenuti['corpo']=str_replace("[13]","<td>".$_SESSION['_13'],$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[14]",$_SESSION['_14'],$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[ris_amm]",'',$contenuti['corpo']);
		break;
	
	case 'research_done':
		$contenuti['corpo']=file_get_contents('contenuti/research_done.pag');
		$count = $_SESSION['count'];
		$contenuti['corpo']=str_replace("[paziente]",$_SESSION['paziente'],$contenuti['corpo']);
		$contenuti['path']="<b>\"Visualizza pazienti\"</b>";
		while($count >= 0 )
		{
			$_SESSION['id_specifico1'.$count] = $_SESSION['id_specifico1'.$count];
			$contenuti['corpo']=str_replace("[username".$count."]",$_SESSION['username'.$count],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[nome".$count."]",$_SESSION['nome'.$count],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[cognome".$count."]",$_SESSION['cognome'.$count],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[data_nascita".$count."]",$_SESSION['data_nascita'.$count],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[codice_fiscale".$count."]",$_SESSION['codice_fiscale'.$count],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[registrazione".$count."]",$_SESSION['registrazione'.$count],$contenuti['corpo']);
			$count --;
		}
		break;
		
	case 'research_done_s':
		$contenuti['corpo']=file_get_contents('contenuti/research_done_s.pag');
		$count_ricerca_s = $_SESSION['count_ricerca_s'];
		$contenuti['corpo']=str_replace("[stamparighe]",$_SESSION['stamparighe'],$contenuti['corpo']);
		$contenuti['path']="<b>\"Visualizza dispositivi\"</b>";
		while($count_ricerca_s >= 0 )
		{
			$contenuti['corpo']=str_replace("[seriale".$count_ricerca_s."]",$_SESSION['seriale'.$count_ricerca_s],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[modello".$count_ricerca_s."]",$_SESSION['modello'.$count_ricerca_s],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[costruttore".$count_ricerca_s."]",$_SESSION['costruttore'.$count_ricerca_s],$contenuti['corpo']);
			$contenuti['corpo']=str_replace("[descrizione".$count_ricerca_s."]",$_SESSION['descrizione'.$count_ricerca_s],$contenuti['corpo']);
			$count_ricerca_s --;
		}
		break;

	case 'gestione_p':
		$contenuti['corpo']=file_get_contents('contenuti/gestione_p.pag');
		$contenuti['path']="<b>\"Gestione paziente\"</b>";
		break;
		
	case 'gestione_o':
		$contenuti['corpo']=file_get_contents('contenuti/gestione_o.pag');
		$contenuti['path']="<b>\"Gestione operatore\"</b>";
		break;
		
	case 'gestione_d':
		if (isset($_SESSION['admin']))
			$contenuti['corpo']=file_get_contents('contenuti/gestione_d.pag');
		else
			$contenuti['corpo']=file_get_contents('contenuti/gestione_d_o.pag');
		$contenuti['path']="<b>\"Gestione dispositivo\"</b>";
		break;
	
	case 'ricerca_s':
		$contenuti['corpo']=file_get_contents('contenuti/ricerca_s.pag');
		$contenuti['path']="<b>\"Ricerca dispositivo\"</b>";
		break;
		
	case 'ricerca_p':
		$contenuti['corpo']=file_get_contents('contenuti/ricerca_p.pag');
		switch ($_REQUEST['utente'])
		{
			case 'o':
				$_SESSION['t_utente'] = 2;
				$contenuti['path']="<b>\"Ricerca operatore\"</b>";
				break;
				
			default:
				$_SESSION['t_utente'] = 3;
				switch ($_REQUEST['tipo'])
				{
					case 'ins_m':
						$contenuti['path']="<b>\"Ricerca paziente per inserimento misurazione\"</b>";
						$_SESSION['invia_mess'] = false;
						$_SESSION['vis_m'] = false;
						$_SESSION['ins_m'] = true;
						break;
						
					case 'vis_m':
						$contenuti['path']="<b>\"Ricerca paziente per visualizzazione misurazioni\"</b>";
						$_SESSION['invia_mess'] = false;
						$_SESSION['ins_m'] = false;
						$_SESSION['vis_m'] = true;
						break;
						
					case 'invia_mess':
						$contenuti['path']="<b>\"Ricerca paziente per invio messaggio\"</b>";
						$_SESSION['ins_m'] = false;
						$_SESSION['vis_m'] = false;
						$_SESSION['invia_mess'] = true;
						break;
						
					default:
						$contenuti['path']="<b>\"Ricerca paziente\"</b>";
						$_SESSION['vis_m'] = false;
						$_SESSION['ins_m'] = false;
						$_SESSION['invia_mess'] = false;
						break;
				}
				break;
		}
		break;

	case 'inserisci_p':
		$contenuti['corpo']=file_get_contents('contenuti/inserisci_utente.pag');
		switch ($_REQUEST['utente'])
		{
			case 'o':
				$_SESSION['t_utente'] = 2;
				$contenuti['path']="<b>\"Inserisci nuovo operatore\"</b>";
				$contenuti['corpo']=str_replace("[ris_ins_paz]",'',$contenuti['corpo']);
				break;
				
			default:
				$_SESSION['t_utente'] = 3;
				$contenuti['path']="<b>\"Inserisci nuovo paziente\"</b>";
				$ris_ins_paz = "<tr><td>Quadro neurologico:</td><td><textarea name="."q_neurologico"." rows="."4"." cols="."35"."></textarea></td><td>(massimo 1000 caratteri)</td>
					<tr><td>Quadro psicologico:</td><td><textarea name="."q_psicologico"." rows="."4"." cols="."35"."></textarea></td><td>(massimo 1000 caratteri)</td>
					<tr><td>Terapia:</td><td><textarea name="."terapia"." rows="."4"." cols="."35"."></textarea></td><td>(massimo 1000 caratteri)</td>
					<tr><td>Data riscontro patologia*:</td><td><input type="."text"." name="."date_ris"." value="."gg-mm-aaaa"."><br />";
				$contenuti['corpo']=str_replace("[ris_ins_paz]",$ris_ins_paz,$contenuti['corpo']);
				break;
		}
		break;
		
}
switch ($_REQUEST['refresh'])
{
	case 'conf_el_s':
		$contenuti['path']="<b>\"Conferma eliminazione\"</b>";
		$_SESSION['id_s'] = $_GET['id'];
		$seriale = $_GET['seriale'];
		$modello = $_GET['modello'];
		$contenuti['corpo']=file_get_contents('contenuti/conferma_eliminazione.pag');
		$contenuti['corpo']=str_replace("[corpo]","<td>Desideri eliminare il dispositivo [modello] con seriale [seriale]?</td></tr><tr><td></td></tr><tr>
		<form name="."input"." action="."elimina_dispositivo.php"." method="."post".">
		<td><input type="."submit"." value="."Conferma"."></td>
		</form>
		<form name="."input"." action="."index.php?case=research_done_s"." method="."post".">
		<td><input type="."submit"." value="."Annulla"."></td>
		</form>",$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[seriale]",$seriale,$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[modello]",$modello,$contenuti['corpo']);
		break;
		
	case 'conf_el_m':
		$_SESSION['id_m'] = $_GET['id_m'];
		$_SESSION['id_p'] = $_GET['id_p'];
		$contenuti['path']="<b>\"Conferma eliminazione\"</b>";
		$data_m = $_GET['data'];
		$contenuti['corpo']=file_get_contents('contenuti/conferma_eliminazione.pag');
		$case = "visualizza_m&name=".$_SESSION['name_v_m']."&surname=".$_SESSION['surname_v_m']."&id=".$_SESSION['id_specifico1']."";
		$contenuti['corpo']=str_replace("[corpo]","<td>Desideri eliminare la misurazione registrata in data [data]?</td></tr><tr><td></td></tr><tr>
		<form name="."input"." action="."elimina_misurazione.php"." method="."post".">
		<td><input type="."submit"." value="."Conferma"."></td>
		</form>
		<form name="."input"." action="."index.php?case=".$case." method="."post".">
		<td><input type="."submit"." value="."Annulla"."></td>
		</form>",$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[data]",$data_m,$contenuti['corpo']);
		break;
		
	case 'conf_el_m2':
		$_SESSION['id_m'] = $_GET['id_m'];
		$_SESSION['id_p'] = $_GET['id_p'];
		$name_pan_mis = $_GET['name'];
		$surname_pan_mis = $_GET['surname'];
		$contenuti['path']="<b>\"Conferma eliminazione\"</b>";
		$data_m = $_GET['data'];
		$contenuti['corpo']=file_get_contents('contenuti/conferma_eliminazione.pag');
		$contenuti['corpo']=str_replace("[corpo]","<td>Desideri eliminare la misurazione del paziente [cognome] [nome]</td></tr><tr><td>registrata in data [data]?</td></tr><tr><td></td></tr><tr>
		<form name="."input"." action="."elimina_misurazione.php"." method="."post".">
		<td><input type="."submit"." value="."Conferma"."></td>
		</form>
		<form name="."input"." action="."visualizza_m.php?case=tot"." method="."post".">
		<td><input type="."submit"." value="."Annulla"."></td>
		</form>",$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[data]",$data_m,$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[cognome]",$surname_pan_mis,$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[nome]",$name_pan_mis,$contenuti['corpo']);
		break;
		
	case 'conferma_eliminazione_u':
		$contenuti['corpo']=file_get_contents('contenuti/conferma_eliminazione.pag');
		$contenuti['path']="<b>\"Conferma eliminazione\"</b>";
		$contenuti['corpo']=str_replace("[corpo]","<td>Desideri eliminare l'utente [utente]?</td></tr><tr><td></td></tr><tr>
		<form name="."input"." action="."elimina_utente.php?tipo=id_paziente"." method="."post".">
		<td><input type="."submit"." value="."Conferma"."></td>
		</form>
		<form name="."input"." action="."index.php?case=research_done1"." method="."post".">
		<td><input type="."submit"." value="."Annulla"."></td>
		</form>",$contenuti['corpo']);
		$contenuti['corpo']=str_replace("[utente]",$_SESSION['_1'],$contenuti['corpo']);
		break;
		
	case 'conf_el':
		$contenuti['corpo']=file_get_contents('contenuti/conf_el.pag');
		$contenuti['path']="<b>\"Eliminazione riuscita\"</b>";
		switch ($_REQUEST['utente'])
		{
			case '2':
				header("Refresh: 2; URL=../Schema/index.php?case=gestione_o");
				break;
				
			case '3':
				header("Refresh: 2; URL=../Schema/index.php?case=gestione_p");
				break;
				
			case '4':
				header("Refresh: 2; URL=../Schema/index.php?case=gestione_d");
				break;
		}
		break;
		
	case 'err':
		switch ($_REQUEST['case'])
		{
			case 'err_lung_mod':
				$contenuti['path']="<b>\"Errore\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				switch ($_REQUEST['type'])
				{
					case 'user':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Il nome utente inserito è troppo corto.</h2></table>',$contenuti['corpo']);
						header("Refresh: 2; URL=../Schema/index.php?case=recupero_us");
						break;
						
					case 'pass':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. La password inserita è troppo corta.</h2></table>',$contenuti['corpo']);
						header("Refresh: 2; URL=../Schema/index.php?case=recupero_pass");
						break;
				}
				break;
			
			case 'err_check_filtro':
				$contenuti['path']="<b>\"Nessun filtro specificato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Nessun filtro specificato.</h2></table>',$contenuti['corpo']);
				switch ($_REQUEST['type'])
				{
					case '1':
						header("Refresh: 2; URL=../Schema/index.php?case=visualizza_m&name=".$_SESSION['name_vis_m']."&surname=".$_SESSION['surname_vis_m']."&id=".$_SESSION['id_defiltra']."");
						break;
						
					default:
						header("Refresh: 2; URL=../Schema/index.php?case=vis_p_m");
						break;
				}
				break;
				
			case 'err_ins_file_m':
				$contenuti['path']="<b>\"Nessun file specificato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Non è stato possibile caricare il file.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=get_s");
				break;
				
			case 'lung_text':
				$contenuti['path']="<b>\"Attenzione!\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Il testo immesso per il messaggio è troppo lungo!.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/ricerca_contatti.php?name=".$_SESSION['name_mess']."&surname=".$_SESSION['surname_mess']."&id=".$_SESSION['id_paz_sms']."");
				break;
				
			case 'sms_ok':
				$contenuti['path']="<b>\"Sms inviato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>L\' sms sta per essere inviato.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
				break;
				
			case 'sms_ko':
				$contenuti['path']="<b>\"Sms non inviato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Non è stato possibile inviare l\'sms.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
				break;
				
			case 'mail_ok':
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['path']="<b>\"Richiesta modifica credenziali\"</b>";
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Una mail per il proseguimento della procedura di recupero è stata inviata al suo indirizzo di posta elettronica.</h2></table>',$contenuti['corpo']);
				switch ($_REQUEST['ok'])
				{
					case '1':
						header("Refresh: 2; URL=../Schema/index.php");
						break;
					case '2':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
						break;
				}
				break;
				
			case 'mail_ok2':
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['path']="<b>\"Richiesta modifica credenziali\"</b>";
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Email inviata correttamente.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
				unset($_SESSION['email']);
				unset($_SESSION['tel']);
				break;
				
			case 'mail_ko':
				$contenuti['path']="<b>\"Impossibile inviare la mail di conferma\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Si è verificato un errore nell\' invio dell\' email.</h2></table>',$contenuti['corpo']);
				switch ($_REQUEST['ko'])
				{
					case '1':
						header("Refresh: 2; URL=../Schema/index.php?case=recupera_passwd");
						break;
						
					case '2':
						header("Refresh: 2; URL=../Schema/index.php?case=recupera_username");
						break;
						
					case '3':
						header("Refresh: 2; URL=../Schema/index.php?case=invia_mess");
						break;
				}
				break;
				
			case 'err_q_file_m':
				$contenuti['path']="<b>\"Errore\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Non è stato possibile caricare il file.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=get_s");
				break;
				
			case 'err_lett_file_m':
				$contenuti['path']="<b>\"Impossibile leggere il file specificato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Non è stato possibile caricare il file.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=visualizza_m");
				break;
				
			case 'err_nn_file_m':
				$contenuti['path']="<b>\"Nessun file specificato\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Specificare il file della misurazione da inserire.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=get_s");
				break;
				
			case 'ok_mod_cred':
				$contenuti['path']="<b>\"Credenziali modificate con successo\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Le proprie credenziali sono state modificate con successo. Utilizzare le nuove credenziali per accedere al proprio account.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?pag=telemed");
				break;
				
			case 'ko_mod_cred':
				$contenuti['path']="<b>\"Errore nella modifica delle credenziali\"</b>";
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Non è stato possibile modificare le proprie credenziali. Se il problema persiste contattare un amministratore.</h2></table>',$contenuti['corpo']);
				header("Refresh: 2; URL=../Schema/index.php?case=get_s");
				break;
				
			case 'err_campo_ric':
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Inserire un valore per la ricerca e riprovare.</h2></table>',$contenuti['corpo']);
				$contenuti['path']="<b>\"Errore\"</b>";
				switch ($_REQUEST['utente'])
				{
					case '2':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&utente=o");
						break;
						
					case '3':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p");
						break;
						
					case '4':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_s");
						break;
						
					case '5':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=ins_m");
						break;
						
					case '6':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=vis_m");
						break;
						
					case '7':
						header("Refresh: 2; URL=../Schema/index.php?case=vis_p_m");
						break;
						
					case '8':
						header("Refresh: 2; URL=../Schema/index.php?case=visualizza_m&name=".$_SESSION['name_vis_m']."&surname=".$_SESSION['surname_vis_m']."&id=".$_SESSION['id_specifico1']."");
						break;
						
					case '9':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
						break;
				}
				break;
			
			case 'no_mis';
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Nessuna misurazione inserita per il paziente selezionato.</h2></table>',$contenuti['corpo']);
				$contenuti['path']="<b>\"Nessuna misuazione trovata\"</b>";
				switch ($_REQUEST['caso'])
				{
					case '1':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=vis_m");
						break;
				
					case '2':
						header("Refresh: 2; URL=../Schema/index.php?pag=logged");
						break;
				}				
				break;
				
			case 'resend_sms';
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				switch ($_REQUEST['status'])
				{
					case 'ok':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Gli sms non inviati stanno per essere reinviati.</h2></table>',$contenuti['corpo']);
						$contenuti['path']="<b>\"Reinvio in corso\"</b>";
						header("Refresh: 2; URL=../Schema/index.php?pag=logged");
						break;
				
					case 'ko':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Si è verificato un errore durante il reinvio degli sms.</h2></table>',$contenuti['corpo']);
						$contenuti['path']="<b>\"Reinvio non riuscito\"</b>";
						header("Refresh: 4; URL=../Schema/index.php?pag=logged");
						break;
				}				
				break;
			
			case 'no_match':
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Attenzione. Nessuna corrispodenza trovata.</h2></table>',$contenuti['corpo']);
				$contenuti['path']="<b>\"Errore\"</b>";
				switch ($_REQUEST['utente'])
				{
					case '2':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&utente=o");
						break;
						
					case '3':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p");
						break;
						
					case '4':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_s");
						break;
						
					case '5':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=ins_m");
						break;
						
					case '6':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=vis_m");
						break;
						
					case '7':
						switch ($_REQUEST['tipo'])
						{
							case '1':
							header("Refresh: 2; URL=../Schema/index.php?case=vis_p_m");
							break;
							
							default:
							header("Refresh: 2; URL=../Schema/index.php?case=gestione_p");
							break;
						}
						break;
						
					case '8':
						header("Refresh: 2; URL=../Schema/index.php?case=visualizza_m&name=".$_SESSION['name_vis_m']."&surname=".$_SESSION['surname_vis_m']."&id=".$_SESSION['id_specifico1']."");
						break;
						
					case '9':
						header("Refresh: 2; URL=../Schema/index.php?case=recupera_passwd");
						break;
						
					case '10':
						header("Refresh: 2; URL=../Schema/index.php?case=recupera_username");
						break;
						
					case '11':
						header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=invia_mess");
						break;
				}
				break;
				
			case 'err_data':
				$contenuti['corpo']=file_get_contents('contenuti/err_data.pag');
				$contenuti['path']="<b>\"Errore\"</b>";
				switch ($_REQUEST['caso'])
				{
				case '1':
					switch ($_REQUEST['tipo'])
					{
					case '2':
						header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
						break;
					case '3':
						header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
						break;
					}
					break;
					
				case '2':
					header("Refresh: 2; URL=../Schema/index.php?case=vis_p_m");
					break;
					
				case '3':
					header("Refresh: 2; URL=../Schema/index.php?case=visualizza_m&name=".$_SESSION['name_vis_m']."&surname=".$_SESSION['surname_vis_m']."&id=".$_SESSION['id_specifico1']."");
					break;
					
				default:
					header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
					break;
				}
				break;
				
			case 'sms_rec';
				$contenuti['corpo']=file_get_contents('contenuti/errore.pag');
				$contenuti['path']="<b>\"Recupero credenziali\"</b>";
				switch ($_REQUEST['ok'])
				{
					case '1':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Un sms contenente la nuova password sta per essere inviato al suo numero di cellulare.</h2></table>',$contenuti['corpo']);
						break;
				
					case '2':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Si è verificato un errore durante l\'invio dell\' sms contente la sua nuova password.</h2></table>',$contenuti['corpo']);
						break;
						
					case '3':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Un sms contenente il suo nome utente e la sua nuova password sta per essere inviato al suo numero di cellulare.</h2></table>',$contenuti['corpo']);
						break;
						
					case '4':
						$contenuti['corpo']=str_replace("[errore]",'<table class="corpo_testo."><h2>Si è verificato un errore durante l\'invio dell\' sms contente il suo nome utente.</h2></table>',$contenuti['corpo']);
						break;
				}
				header("Refresh: 2; URL=../Schema/index.php?pag=logged");
				break;
		}
		break;
//*********************************************
	case 'err_sql':
		$contenuti['corpo']=file_get_contents('contenuti/err_sql.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php");
		break;
		
	case 'err_cap':
		$contenuti['corpo']=file_get_contents('contenuti/err_cap.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['tipo'])
		{
		case '2':
			header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
			break;
		case '3':
			header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
			break;
		}
		break;
		
	case 'err_seriale':
		$contenuti['corpo']=file_get_contents('contenuti/err_seriale.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_s");
		break;
		
	case 'err_campo':
		$contenuti['corpo']=file_get_contents('contenuti/err_campo.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['case'])
		{
		case '2':
			header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
			break;
		case '3':
			header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
			break;
		case '4':
			header("Refresh: 2; URL=../Schema/index.php?case=recupera_passwd");
			break;
		case '5':
			header("Refresh: 2; URL=../Schema/index.php?case=recupero_pass");
			break;
		case '6':
			header("Refresh: 2; URL=../Schema/index.php?case=recupera_username");
			break;
		case '7':
			header("Refresh: 2; URL=../Schema/index.php?case=recupero_us");
			break;
		}
		break;
	
	case 'err_campi':
		$contenuti['corpo']=file_get_contents('contenuti/err_campi.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['tipo'])
		{
			case 'ins_s':
				header("Refresh: 2; URL=../Schema/index.php?case=inserisci_s");
				break;
				
			case 'ins_o':
				header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p&utente=o");
				break;
				
			case 'send_mess':
				header("Refresh: 2; URL=../Schema/ricerca_contatti.php?name=".$_SESSION['name_mess']."&surname=".$_SESSION['surname_mess']."&id=".$_SESSION['id_paz_sms']."");
				break;
				
			default:
				header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
				break;
		}
		break;

	case 'err_lung':
		$contenuti['corpo']=file_get_contents('contenuti/err_lung.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
		break;
		
	case 'err_lung_descr':
		$contenuti['corpo']=file_get_contents('contenuti/err_lung_descr.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_s");
		break;

	case 'err_pass':
		$contenuti['corpo']=file_get_contents('contenuti/err_pass.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['case'])
		{
		case '2':
			header("Refresh: 2; URL=../Schema/index.php?case=recupero_pass");
			break;
		case '1':
			header("Refresh: 2; URL=../Schema/anagrafica_p.php?valore='".$_SESSION['id_anagrafica']."'&case=1");
			break;
		default:
			header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
			break;
		}
		break;

	case 'err_cf':
		$contenuti['corpo']=file_get_contents('contenuti/err_cf.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['case'])
		{
			case '1':
				switch ($_REQUEST['tipo'])
				{
				case '2':
					header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
					break;
				case '3':
					header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
					break;
				}
				break;
				
			case '2':
				header("Refresh: 2; URL=../Schema/index.php?case=recupera_username");
				break;
				
			default:
				header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
				break;
		}
		break;

	case 'err_tel':
		$contenuti['corpo']=file_get_contents('contenuti/err_tel.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['case'])
		{
		case '1':
			switch ($_REQUEST['tipo'])
			{
			case '2':
				header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
				break;
			case '3':
				header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
				break;
			}
			break;
		default:
			header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
			break;
		}
		break;

	case 'err_mail':
		$contenuti['corpo']=file_get_contents('contenuti/err_mail.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		switch ($_REQUEST['case'])
		{
		case '1':
			switch ($_REQUEST['tipo'])
			{
			case '2':
				header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_operatore&case=1");
				break;
			case '3':
				header("Refresh: 2; URL=../Schema/anagrafica_p.php?tipo=id_paziente&case=1");
				break;
			}
			break;
		default:
			header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
			break;
		}
		break;
		
	case 'err_query':
		$contenuti['corpo']=file_get_contents('contenuti/err_query.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
		break;
		
	case 'err_query_s':
		$contenuti['corpo']=file_get_contents('contenuti/err_query_s.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_s");
		break;
		
	case 'err_img':
		$contenuti['corpo']=file_get_contents('contenuti/err_img.pag');
		$contenuti['path']="<b>\"Errore\"</b>";
		header("Refresh: 2; URL=../Schema/index.php?case=inserisci_p");
		break;

	case 'eseguito':
		$contenuti['corpo']=file_get_contents('contenuti/eseguito.pag');
		$contenuti['path']="<b>\"Registrazione avvenuta\"</b>";
		switch ($_REQUEST['tipo'])
		{
			case '2':
				header("Refresh: 2; URL=../Schema/index.php?case=gestione_o");
				break;
				
			case '3':
				header("Refresh: 2; URL=../Schema/index.php?case=gestione_p");
				break;
				
			case 's':
				header("Refresh: 2; URL=../Schema/index.php?case=inserisci_s");
				break;
				
			case 'm':
				header("Refresh: 2; URL=../Schema/index.php?case=ricerca_p&tipo=ins_m");
				break;
		}
		break;
}
if (isset($_SESSION['type'])){
	if ($_SESSION['type']==4){//controlla che l'autenticazione vada a buon fine
		$contenuti['login']=file_get_contents('../Schema/contenuti/nlogged.pag');
		$contenuti['path']="<b>\"Login errato\"</b>";
		unset($_SESSION['type']);
		header("Refresh: 2; URL=../Schema/index.php");
	}
	else{
		if ($_SESSION['type']=="Amministratore")
			$contenuti['menu']=file_get_contents('../Schema/contenuti/navig_a.pag');
		else if ($_SESSION['type']=="Operatore")
				$contenuti['menu']=file_get_contents('../Schema/contenuti/navig_o.pag');
		else if ($_SESSION['type']=="Paziente")
			$contenuti['menu']=file_get_contents('../Schema/contenuti/navig_p.pag');
		$contenuti['login']=file_get_contents('../Schema/contenuti/logged.pag');
		$contenuti['login']=str_replace("[utente]",$_SESSION['user'],$contenuti['login']);
		$contenuti['login']=str_replace("[tipo]",$_SESSION['type'],$contenuti['login']);
		if ($_SESSION['admin'] == true || $_SESSION['operator'] == true)
		{	
			$count_sms_failed = count_sms_failed();
			if ($count_sms_failed > 0)
				$contenuti['login']=str_replace("[warning_sms]",'<br/><table><tr><td style="font:normal bolder 15px Verdana"><font color="red">Attenzione! Sono presenti '.$count_sms_failed.' sms non inviati.</font></td></tr>
				<form name="login" action="../Schema/resend_sms.php" method="post"><td style="font:normal bolder 10px Verdana">
				<input type="submit" value="Reinvia" /></td>
				</form>
			</table>',$contenuti['login']);
			else
				$contenuti['login']=str_replace("[warning_sms]",'',$contenuti['login']);
		}
		else
			$contenuti['login']=str_replace("[warning_sms]",'',$contenuti['login']);
	}
}
//questo specifica la formazione del corpo della pagina
$contenuti['corpo']="<span class=\"corpo_testo\">Ti trovi nella sezione: ".$contenuti['path']."</span><br>".$contenuti['corpo'];

//questi sono invece i contenuti in base alla alitÃ  di accesso che variano
$template['pagina_generale']=str_replace("[login]",$contenuti['login'],$template['pagina_generale']);
$template['pagina_generale']=str_replace("[menu]",$contenuti['menu'],$template['pagina_generale']);
$template['pagina_generale']=str_replace("[corpo]",$contenuti['corpo'],$template['pagina_generale']);
$template['pagina_generale']=str_replace("[info]",$contenuti['info'],$template['pagina_generale']);

echo $template['pagina_generale'];
?>