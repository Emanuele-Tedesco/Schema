<?php
session_start();
include ("functions.php");
function send_sms($numero,$testo,$id)
{
	$da = 'IRCCS';
	if ($numero != '' && $da != '' && $testo != '')
	{
		$server = '192.168.1.128';
		$porta = 21;
		$connessione = ftp_connect($server,$porta) 
			or die ('impossibile connettersi al sever');
		ftp_login($connessione,'root','pass')
			or die ('impossibile loggarsi al sever');
		$file_serv = "/var/spool/outgoing/";
		$filename = "TELMED-".$id."-".mktime().rand(1, 1000);
		$somecontent = "From: $da\n";
		$somecontent .= "To: $numero\n";
		$somecontent .= "\n";
		$somecontent .= $testo;
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
		$query = "INSERT INTO `Telemedicina`.`Sms` (`id`, `id_utente`, `messaggio`, `file_name`, `stato`) VALUES (NULL, '".$_SESSION['id']."', '$data', '$filename', '$stato')";
		$result = mysql_query($query);
		echo mysql_error();
		connection_close($conn);
		chmod($filename,0777);
		unlink($filename);
		if ($stato == 3)
		{
			unset($_SESSION['id_paz_sms']);
			return true;
		}
		else
			return false;
	}
	else 
	{
		echo 'inserire tutti i parametri';
		unset($_SESSION['id_paz_sms']);
		return false;
	}
}
$id = $_SESSION['id_paz_sms'];
$object = $_POST['object'];
$type = $_POST['type'];
$text = $_POST['text'];
if ($object == '' || $text == '')
	header("Refresh: 0; URL=../Schema/index.php?refresh=err_campi&tipo=send_mess");
else
{
	if (is_numeric($type))
	{
		$number = '39'.$type;
		$text = 'Oggetto: '.$object.' --- '.$text;
		if ( strlen($text) <= 1823 )
		{
			if (send_sms($number,$text,$id))
			{
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_ok");
			}
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=sms_ko");
		}
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=lung_text");
	}
	else
	{
		if ($check_ko = send_mail($type,$text,$object))
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ko=&ko=3");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=mail_ok2");
	}
}
?>
