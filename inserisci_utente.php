<?php
session_start();
include("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$campi = array();
	$campi[0] = $_POST['username'];
	$campi[1] = $_POST['password'];
	$campi[2] = $_POST['re_password'];
	$campi[3] = $_POST['name'];
	$campi[4] = $_POST['surname'];
	$campi[5] = $_POST['date'];
	$cdate = explode('-', $campi[5]);//scompongo la data
	$sdate = mktime(0,0,0,$cdate[1],$cdate[0],$cdate[2]);
	$campi[6] = $_POST['c_nascita'];
	$campi[7] = $_POST['sex'];
	$campi[8] = $_POST['cf'];
	$campi[9] = $_POST['indirizzo'];
	$campi[10] = $_POST['cap'];
	$campi[11] = $_POST['city'];
	$campi[12] = $_POST['tel'];
	$email = $_POST['email'];
	$file = $_FILES['file'];
	$path = "immagini/";
	$t_utente = $_SESSION['t_utente'];
	if ($t_utente == 2)
	{
		$id_type = 'id_operatore';
		$table = 'Anagrafica_operatore';
	}
	elseif ($t_utente == 3)
	{
		$id_type = 'id_paziente';
		$table = 'Anagrafica_paziente';
		$q_neurologico = $_POST['q_neurologico'];
		$q_psicologico= $_POST['q_psicologico'];
		$terapia = $_POST['terapia'];
		$campi[13] = $_POST['date_ris'];
		$cdate1 = explode('-', $campi[13]);//scompongo la data
		$sdate1 = mktime(0,0,0,$cdate1[1],$cdate1[0],$cdate1[2],0);
		$cdate1 = $cdate1[2].'-'.$cdate1[1].'-'.$cdate1[0];
	}
	$check_ko = false;//valore di controllo sulla coerenza dei campi in seriti
	$check_ko = check_presenza($campi);
	if ($check_ko)
	{
		if ($t_utente == 2)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_campi&tipo=ins_o");
		else
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_campi");
	}
	else
	{
		$check_ko = check_len($campi[0],$campi[1]);
		if ($check_ko)
			header("Refresh: 0; URL=../Schema/index.php?refresh=err_lung");
		else
		{
			$check_ko = check_passwrd($campi[2],$campi[1]);
			if ($check_ko)
				header("Refresh: 0; URL=../Schema/index.php?refresh=err_pass");
			else
			{
				$check_ko = check_date($cdate[1],$cdate[0],$cdate[2]);
				if ($check_ko )
					header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=1&tipo=".$t_utente."");
				else
				{
					$check_ko = check_cf($campi[8]);
						if ($check_ko)
							header("Refresh: 0; URL=../Schema/index.php?refresh=err_cf");
					else
					{
						$check_ko = check_cap($campi[10]);
						if ($check_ko)
							header("Refresh: 0; URL=../Schema/index.php?refresh=err_cap");
						else
						{
							$check_ko = check_tel($campi[12]);
							if ($check_ko)
								header("Refresh: 0; URL=../Schema/index.php?refresh=err_tel");
							else
							{
								$check_ko = check_email($email);
								if ($check_ko)
									header("Refresh: 0; URL=../Schema/index.php?refresh=err_mail");
								else
								{
									if ($t_utente == 3)
									{
										$check_ko = check_date($cdate1[1],$cdate1[0],$cdate1[2]);
										if ($check_ko)
											echo '2';//header("Refresh: 0; URL=../Schema/index.php?refresh=err&case=err_data&caso=1&tipo=".$t_utente."");
									}
									$query1 = "INSERT INTO `Utente` (`id`, `username`, `password`, `tipo`) VALUES (NULL, '$campi[0]', SHA1('$campi[1]'), $t_utente)";
									$result = mysql_query($query1);
									if (!$result)
										header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
									else
									{
										$query_id = mysql_query("SELECT id FROM `Utente` WHERE `username` = '$campi[0]'");
										$result_id = mysql_fetch_assoc($query_id);
										$id = $result_id['id'];
										$cdate = $cdate[2].'-'.$cdate[1].'-'.$cdate[0];//ricompongo la data
										$path = $path . basename( $_FILES['file']['name']);//creo il path per l'immagine
										if ($path == 'immagini/')
											$path = 'immagini/default.jpg';
										$query2 = mysql_query("INSERT INTO `$table` ( `$id_type`, `username`, `nome`, `cognome`, `data_nascita`, `comune_nascita`, `sesso`, `codice_fiscale`, `indirizzo`, `cap`, `città`, `numero_telefono`, `email`, `immagine`, `data_registrazione`) values ( '$id', '$campi[0]', '$campi[3]', '$campi[4]', '$cdate', '$campi[6]', '$campi[7]', '$campi[8]', '$campi[9]', '$campi[10]', '$campi[11]', '$campi[12]', '$email', '$path', CURRENT_TIMESTAMP)");
										if (!$query2)
										{
											$query3 = mysql_query("DELETE FROM `Utente` WHERE `username` = '$campi[0]'");
											if (!$query3)
												header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
											header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
										}
										else
										{
											if ($path == 'immagini/default.jpg')
												if ($t_utente == 3)
												{
													$query4 = "INSERT INTO `Cartella_clinica` (`id`, `id_paziente`, `Q_neurologico`, `Q_psicologico`, `terapia`, `data_riscontro`) VALUES (NULL, '$id', '$q_neurologico', '$q_psicologico', '$terapia', '$cdate1')";
													$result = mysql_query($query4);
													if(!$result)
														header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
													else
														header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=$t_utente");
												}
												else
													header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=$t_utente");
											else
											{
												if (move_uploaded_file($_FILES["file"]["tmp_name"], $path))
													if ($t_utente == 3)
													{
														$query4 = "INSERT INTO `Cartella_clinica` (`id`, `id_paziente`, `Q_neurologico`, `Q_psicologico`, `terapia`, `data_riscontro`) VALUES (NULL, '$id', '$q_neurologico', '$q_psicologico', '$terapia', '$cdate1')";
														$result = mysql_query($query4);
														if(!$result)
															header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
														else
															header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=$t_utente");
													}
													else
														header("Refresh: 0; URL=../Schema/index.php?refresh=eseguito&tipo=$t_utente");
												else
												{
													$query4 = mysql_query("DELETE FROM `$table` WHERE `username` = '$campi[0]'");
													$query3 = mysql_query("DELETE FROM `Utente` WHERE `username` = '$campi[0]'");
													if (!$query4 || !$query3)
														header("Refresh: 0; URL=../Schema/index.php?refresh=err_query");
													header("Refresh: 0; URL=../Schema/index.php?refresh=err_img");
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}				
	}
}
connection_close($conn);
?>