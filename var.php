<?
// var.php
#########################
# CONFIGURAZIONI
#######################
empty($var);
// disabilito visualizzazione errori visualizzero'
// solo mesg debug
error_reporting(0);

$var['db_host']='localhost';
$var['db_name']='Telemedicina';

$var['db_user_admin']='root';
$var['db_password_admin']='admin';

$var['db_user_view']='';
$var['db_password_view']='';

$var['general_path']="localhost/faticaSM";
//$var['general_path']="ide.unime.it/";
//$var['absolute_path']="/home/job/web/idecat/";
$var['absolute_path']="/var/www/faticaSM/";
$var['upload_syzebytes_limit']=100000000;
$var['limit']['rows_one_page']=20;
//$var['mappa']['img_on']="img_on.gif";
//$var['mappa']['img_off']="img_off.gif";


$link=mysql_connect($var['db_host'],$var['db_user_admin'],$var['db_password_admin'])
           or die('Problemi di accesso al database'.mysql_error());

mysql_select_db ($var['db_name'])
        or die("Non posso selezionare il database".mysql_error());

?>
