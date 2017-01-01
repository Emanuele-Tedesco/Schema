<?php
session_start();
include("functions.php");
function genera_mail($id,$email,$telefono)
{
	if ($email != '')
	{
		$request = urlencode(base64_encode('ID-'.$id.'-richiesta_modifica_password'));
		$r = urlencode('recupero_pass');
		$Subject    = "Richiesta recupero password";
		$url = '
		<html>
		<head>
		  <title>Richiesta di modifica password</title>
		</head>
		<body>
		  <p>Ha ricevuto questa email poiché è stata inoltrata una richiesta
			di recupero password da parte del suo account sul portale di TELEMEDICINA.</p>
		  <p>Se dovesse aver ricevuto questa email per errore, la ignori e la cancelli
			immediatamente, altrimenti clicchi sul seguente link per avviare la procedura di modifica:</p>
		<p><a href="https://localhost/Schema/index.php?case='.$r.'&valore='.$request.'">Clicca qui per modificare la password</a></p>
		</body>
		</html>';
		if ($check_ko = send_mail($email,$url,$Subject))
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ko&ko=1");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ok&ok=1");
	}
	else
	{
		srand(time());
		$new_passwd = md5(rand());		
		$new_passwd = substr($new_passwd,0,8);
		$query = "select password from Utente WHERE  id = '$id' ";
		$rs = mysql_query($query);
		$rs = mysql_fetch_assoc($rs);
		$old_passwd = $rs['password'];
		$query = "UPDATE  Utente SET  password = SHA1( '$new_passwd' ) WHERE  id = '$id' ";
		$rs = mysql_query($query);
		if ($rs)
		{
			$da = 'IRCCS';
			$telefono = '39'.$telefono;
			$server = '192.168.0.98';
			$porta = 21;
			$text = 'Oggetto: Recupero Password --- Ha ricevuto questo sms poiché è stata inoltrata una richiesta di recupero password da parte del suo account sul portale di TELEMEDICINA. La sua nuova password è: '.$new_passwd.'.';
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
			if (!ftp_put($connessione,"/var/spool/outgoing/".$filename,$filename,FTP_ASCII))
			{
				$query = "UPDATE  Utente SET  password = '$old_passwd' WHERE  id = '$id' ";
				$rs1 = mysql_query($query);
				if ($rs1)
					$stato = 2;
			}
			else
				$stato = 3;
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
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_rec&ok=1");
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_rec&ok=2");
		}
	}
}
if ($conn = connection())
{
	$username = $_POST['username'];
	if ($username == '')
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=4");
	else
	{
		$query = "SELECT  email, id_operatore, numero_telefono FROM Anagrafica_operatore WHERE username = '$username'";
		$rs = mysql_query($query);
		$riga = mysql_fetch_assoc($rs);
		$id = $riga['id_operatore'];
		$telefono = $riga['numero_telefono'];
		$email = $riga['email'];
		echo mysql_error();
		if (($n_rows=mysql_num_rows($rs)) > 0)
			genera_mail($id,$email,$telefono);
		else
		{
			$query = "SELECT email, id_paziente, numero_telefono FROM Anagrafica_paziente WHERE username = '$username'";
			$rs = mysql_query($query);
			$riga = mysql_fetch_assoc($rs);
			$id = $riga['id_paziente'];
			$telefono = $riga['numero_telefono'];
			$email = $riga['email'];
			echo mysql_error();
			if (($n_rows=mysql_num_rows($rs)) > 0)
				genera_mail($id,$email,$telefono);
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=no_match&utente=9");
		}
	}
}
connection_close($conn);
?>