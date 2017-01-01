<?php
session_start();
include("functions.php");
if ($conn = connection())
{
	$query = "SELECT messaggio, file_name FROM Sms WHERE stato = 2";
	$rs = mysql_query($query);
	echo mysql_error();
	if (($n_rows=mysql_num_rows($rs)) > 0)
	{
		$count = 0;
		$server = '192.168.1.128';
		$porta = 21;
		$connessione = ftp_connect($server,$porta) 
			or die ('impossibile connettersi al sever');
		ftp_login($connessione,'root','pass')
			or die ('autenticazione non riuscita');
		while ($count < $n_rows)
		{
			$riga = mysql_fetch_assoc($rs);
			$messaggio = $riga['messaggio'];
			$file_name = $riga['file_name'];
			$file_name1 = 'sms_failed/'.$file_name;
			$f = fopen($file_name1,'wb');
			if ($f)
				fwrite ($f, $messaggio);
			fclose($f);
			chmod($file_name1,0777);
			if(ftp_put($connessione,"/var/spool/outgoing/".$file_name,$file_name1,FTP_ASCII))
			{
				$query = "UPDATE  Sms SET  stato =  '3' WHERE  file_name = '$file_name'";
				$rs1 = mysql_query($query);
				if ($rs1)
					if (ftp_delete($connessione,"/var/spool/failed/".$file_name))
						header("Refresh:0; URL=../Schema/index.php?refresh=err&case=resend_sms&status=ok");
			}
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=resend_sms&status=ko");
			unlink($file_name1);
			$count++;
		}
		ftp_close($connessione);
	}
}
connection_close($conn);
?>
