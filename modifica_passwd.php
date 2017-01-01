<?php
session_start();
include("functions.php");
if ($conn = connection())
{
	$check_ko = false;
	$id = $_SESSION['id_rec_pass'][1];
	$new_passwd = $_POST['password'];
	$re_new_passwd = $_POST['repassword'];
	if ($new_passwd == '')
		header("Refresh: 0; URL=../Schema/index.php?refresh=err_campo&case=5");
	else
	{
		$check_ko = check_passwrd ($re_new_passwd,$new_passwd);
		if ($check_ko)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_pass&case=2");
		elseif(check_len($new_passwd,$new_passwd))
			header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_lung_mod&type=pass");
		else
		{
			$query = "UPDATE  Utente SET  password = SHA1( '$new_passwd' ) WHERE  id = '$id' ";
			$rs = mysql_query($query);
			if ($rs)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=ok_mod_cred");
			else
				header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=ko_mod_cred");
			unset($_SESSION['id_rec_pass']);
		}
	}
}
connection_close($conn);
?>