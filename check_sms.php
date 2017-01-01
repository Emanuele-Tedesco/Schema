<?php
include ("functions.php");
$server = '192.168.1.128';
$porta = 21;
$connessione = ftp_connect($server,$porta) 
	or die ('impossibile connettersi al sever');
ftp_login($connessione,'root','pass')
	or die ('impossibile loggarsi al sever');
$fileArr = ftp_nlist($connessione,'/var/spool/failed/');
$count_failed = count($fileArr)-2;
$fileArr1 = ftp_nlist($connessione,'/var/spool/sent/');
$count_sent = count($fileArr1)-2;
$count = 0;
if ($conn = connection())
{
	while ($count < $count_failed)
	{
		$check = explode('-',$fileArr[$count+2]);
		if ($check[0] == 'TELMED')
		{
			$query = "SELECT * FROM Sms WHERE file_name = '".$fileArr[$count+2]."'";
			$rs = mysql_query($query);
			echo mysql_error();
			if ($rs)
			{
				$query = "UPDATE  Sms SET  stato =  '2' WHERE  file_name = '".$fileArr[$count+2]."'";
				$rs = mysql_query($query);
				echo mysql_error();
			}
		}
		$count++;
	}
	$count = 0;
	while ($count < $count_sent)
	{
		$check = explode('-',$fileArr1[$count+2]);
		if ($check[0] == 'TELMED')
		{
			$query = "SELECT * FROM Sms WHERE file_name = '".$fileArr1[$count+2]."'";
			$rs = mysql_query($query);
			if ($rs)
			{
				$query = "UPDATE  Sms SET  stato =  '1' WHERE  file_name = '".$fileArr1[$count+2]."'";
				$rs = mysql_query($query);
				echo mysql_error();
			}
		}
		$count++;
	}
}
connection_close($conn);
ftp_close($connessione);
?>
