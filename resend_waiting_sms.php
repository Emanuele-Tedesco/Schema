<?php
include ("functions.php");
if ($conn = connection())
{
	$server = '192.168.1.128';
	$porta = 21;
	$connessione = ftp_connect($server,$porta) 
		or die ('impossibile connettersi al sever');
	ftp_login($connessione,'root','pass')
		or die ('impossibile loggarsi al sever');
	$query = "SELECT * FROM Sms WHERE stato = '3'";
	$rs = mysql_query($query);
	$n_rows=mysql_num_rows($rs);
	$count = 0;
	while ($count < $n_rows)
	{
		$riga = mysql_fetch_assoc($rs);
		mysql_error();
		$messaggio = $riga['messaggio'];
		$file_name = $riga['file_name'];
		$file_name1 = '/var/www/Schema/sms_failed/'.$file_name;
		$f = fopen($file_name1,'w+');
		if ($f)
			fwrite ($f, $messaggio);
		fclose($f);
		chmod($file_name1,0777);
		if(ftp_put($connessione,"/var/spool/outgoing/".$file_name,$file_name1,FTP_ASCII))
		{
			$query = "UPDATE  Sms SET  stato =  '3' WHERE  file_name = '$file_name'";
			$rs1 = mysql_query($query);
		}
		unlink($file_name1);
		$count++;
	}
}
connection_close($conn);
?>
