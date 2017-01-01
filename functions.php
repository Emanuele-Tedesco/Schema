<?php
function check_presenza($campi)
{
	for ($i = 0; $i < count($campi); $i++)
		if ($campi[$i] == '') 
			return $check_ko = true;
}

function check_len($username,$password)
{
	if (strlen($username)<5 || strlen($password)<5)
		return $check_ko = true;
}

function check_len_descr($descr)
{
	if (strlen($descr)<10 || strlen($descr)>199)
		return $check_ko = true;
}

function check_passwrd ($re_password,$password)
{
	if ($re_password != $password)
		return $check_ko = true;
}

function check_date ($cdate1,$cdate0,$cdate2)
{
	$data_inserita = mktime(0,0,0,$cdate1,$cdate0,$cdate2);
	$today = getdate();
	$today_sec = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
	if ($data_inserita == null || $data_inserita > $today_sec || !is_numeric($cdate0) || !is_numeric($cdate1) || !is_numeric($cdate2))
		return true;
	else
	{
		if (!checkdate($cdate1,$cdate0,$cdate2))
			return true;
		else
			return false;
	}
}

function check_cf($cf)
{
	if (strlen($cf)!=16) 
		return $check_ko = true;
}

function check_cap($cap)
{
	if (!is_numeric($cap) || strlen($cap)!=5) 
		return $check_ko = true;
}

function check_tel($tel)
{
	if (!is_numeric($tel))
		return $check_ko = true;
}

function check_email($email)
{
	if ($email)
	{
		$check = strpbrk($email,"@");
			if ($check != null)
			{
				$recheck = strpbrk($check,".");
				if ($recheck == null)
					return $check_ko = true;
			}
			else 
				return $check_ko = true;
	}
	else 
		return $check_ko = false;
}

function send_mail($address,$message,$Subject)
{
	$host = "ssl://smtp.mail.yahoo.it";
	$port = "465";
	$username = "irccsboninopulejo@yahoo.it";
	$password = "centroneurolesi";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: no-reply@irccsboninopulejo' . "\r\n";
	if(!mail($address,$Subject,$message,$headers))
		return true;
	else
		return false;
}

function count_sms_failed()
{
	$conn = connection();
	$query = "select count(id) from Sms WHERE  stato =  '2'";
	$rs = mysql_query($query);
	$riga = mysql_fetch_assoc($rs);
	connection_close($conn);
	return $riga['count(id)'];
}

function connection()
{
	$conn = mysql_connect('Localhost','root','telemedicina');
	if (!$conn)
	{
		echo "Impossibile connettersi al database.";
		die("Errore Sql: ".mysql_error());
		header("Refresh: 3; URL=../Schema/index.php");
	}
	else
	{
		mysql_select_db('Telemedicina');
		return $conn;
	}
}
function connection_close($conn)
{
	mysql_close($conn);
}
?>
