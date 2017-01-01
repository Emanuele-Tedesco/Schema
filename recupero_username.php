<?php
session_start();
include("functions.php");
function genera_mail($id,$email,$user,$telefono)
{
	if ($email != '')
	{
		$request = urlencode(base64_encode('ID-'.$id.'-richiesta_modifica_username'));
		$r = urlencode('recupero_pass');
		$Subject    = "Richiesta recupero nome utente";
		$url = '
		<html>
		<head>
		  <title>Richiesta di modifica nome utente</title>
		</head>
		<body>
		  <p>Ha ricevuto questa email poiché è stata inoltrata una richiesta
			di recupero del nome utente da parte del suo account sul portale di TELEMEDICINA. Il tuo nome utente è '.$user.'.</p>
		  <p>Se dovesse aver ricevuto questa email per errore, la ignori e la cancelli
			immediatamente.</p>
			Nel caso in cui avesse smarrito anche la propria password, clicchi sul seguente link per modificarla:</p>
		<p><a href="http://localhost/Schema/index.php?case='.$r.'&valore='.$request.'">Clicca qui per modificare la password:</a></p>
		</body>
		</html>';
		if ($check_ko = send_mail($email,$url,$Subject))
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ko&ko=2");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ok&ok=1");
	}
	else
	{
		srand(time());
		$new_passwd = md5(rand());		
		$new_passwd = substr($new_passwd,0,8);
		$query = "UPDATE  Utente SET  password = SHA1( '$new_passwd' ) WHERE  id = '$id' ";
		$res_set = mysql_query($query);
		$da = 'IRCCS';
		$telefono = '39'.$telefono;
		$server = '192.168.0.98';
		$porta = 21;
		$text = 'Oggetto: Recupero Nome Utente e Password--- Ha ricevuto questo sms poiché è stata inoltrata una richiesta di recupero del nome utente e della password da parte del suo account di TELEMEDICINA. Il suo nome utente e la sua nuova password sono: '.$user.', '.$new_passwd.'.';
		$connessione = ftp_connect($server,$porta) 
			or die ('impossibile connettersi al sever');
		ftp_login($connessione,'root','pass')
			or die ('impossibile loggarsi al sever');
		$file_serv = "/var/spool/outgoing/";
		$filename = "TELMED-".$id."-".mktime().rand(1, 1000);
		$somecontent = "From: $da\n";
		$somecontent .= "To: $telefono\n";
		$somecontent .= "\n";
		$somecontent .= $text;
		$f=fopen($filename, "wb");
		fputs($f, $somecontent);
		fclose($f);
		if (ftp_put($connessione,"/var/spool/outgoing/".$filename,$filename,FTP_ASCII))
			$stato = 3;
		else
			$stato = 2;
		ftp_close($connessione);
		$data = addslashes(fread(fopen($filename, "rb"),filesize($filename)));
		fclose($data);
		$conn = connection() 
				or die ('errore durante la connessione al db');
		$query = "INSERT INTO `Telemedicina`.`Sms` (`id`, `id_utente`, `messaggio`, `file_name`, `stato`) VALUES (NULL, '$id', '$data', '$filename', '$stato')";
		$result = mysql_query($query);
		echo mysql_error();
		connection_close($conn);
		chmod($filename,0777);
		unlink($filename);
		if ($stato == 3)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_rec&ok=3");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_rec&ok=4");
	}
}
if ($conn = connection())
{
	$codice_fiscale = $_POST['codice_fiscale'];
	if ($codice_fiscale == '')
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=6");
	$check_ko = check_cf($codice_fiscale);
	if ($check_ko)
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_cf&case=2");
	else	
	{
		$query = "SELECT  email, id_operatore, username, numero_telefono FROM Anagrafica_operatore WHERE codice_fiscale = '$codice_fiscale'";
		$rs = mysql_query($query);
		$riga = mysql_fetch_assoc($rs);
		$id = $riga['id_operatore'];
		$user = $riga['username'];
		$email = $riga['email'];
		$telefono = $riga['numero_telefono'];
		echo mysql_error();
		if (($n_rows=mysql_num_rows($rs)) > 0)
			genera_mail($id,$email,$user,$telefono);
		else
		{
			$_SESSION['flag_paz'] = true;
			$query = "SELECT email, id_paziente, username, numero_telefono FROM Anagrafica_paziente WHERE codice_fiscale = '$codice_fiscale'";
			$rs = mysql_query($query);
			$riga = mysql_fetch_assoc($rs);
			$id = $riga['id_paziente'];
			$user = $riga['username'];
			$email = $riga['email'];
			$telefono = $riga['numero_telefono'];
			echo mysql_error();
			if (($n_rows=mysql_num_rows($rs)) > 0)
				genera_mail($id,$email,$user,$telefono);
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=10");
		}
	}
}
connection_close($conn);
?>