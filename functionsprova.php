<?
include '../faticaSM/var.php';

function info_log($utente){

$query = "SELECT * " .
	 "FROM utenti " .
	 "WHERE id_utenti=".$utente["id_utenti"];

$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) == 1){
	$info= mysql_fetch_assoc($result);
	extract($info);
}

return $info;
}

function permessi($perm){

$query = "SELECT * " .
	 "FROM permessi " .
	 "WHERE id_attore=".$perm["id_attore"];

$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) == 1){
	$p= mysql_fetch_assoc($result);
	extract($p);
}

return $p;
}



function alert_oper_rec(){

$ALERT=array();

$query = "SELECT * " .
	 "FROM temp";

$result = mysql_query($query) or die(mysql_error());

$ALERT['num']= mysql_num_rows($result);
$i=0;
$ALERT['numok']=0;

while ($row = mysql_fetch_array($result)) {
	extract($row);

	$quer = "SELECT id_test " .
	 	"FROM valutazione ".
         	"WHERE id_paziente = ".$row['id_temp']." ";

	$resu = mysql_query($quer) or die(mysql_error());
	$num=mysql_num_rows($resu);
		if($num==5){
			$ALERT['numok']++;
			$ALERT[$i]=$row;
		}
}
$i++;

return $ALERT;
}



function alert_oper_sms(){
$ALERT=array();

$query = "SELECT id_paziente " .
	 "FROM invii ".
         "WHERE 'invio_gior_ok?'=0";

$result = mysql_query($query) or die(mysql_error());

$ALERT['num_gio']= mysql_num_rows($result);
$i=0;

while ($row = mysql_fetch_array($result)) {
	extract($row);
        $ALERT['gior'][$i]=$row['id_paziente'];
	$i++;
}

$query = "SELECT id_paziente " .
	 "FROM invii ".
         "WHERE 'invio_corr_ok?'=0";

$result = mysql_query($query) or die(mysql_error());

$ALERT['num_cor']= mysql_num_rows($result);
$i=0;

while ($row = mysql_fetch_array($result)) {
	extract($row);
        $ALERT['corr'][$i]=$row['id_paziente'];
	$i++;
}

return $ALERT;
}


function alert_oper_compl(){

$ALERT=array();

$query = "SELECT id_paziente " .
	 "FROM paziente ".
         "WHERE compliance=0";

$result = mysql_query($query) or die(mysql_error());

$ALERT['num_no_compl']= mysql_num_rows($result);
$i=0;

while ($row = mysql_fetch_array($result)) {
	extract($row);
        $ALERT[$i]=$row['id_paziente'];
	$i++;
}

return $ALERT;
}




function alert_oper_vis(){

$ALERT=array();

$ANNO=intval(substr(date('Y-m-d'),0,4)); 
$MESE=intval(substr(date('Y-m-d'),5,2));
$DAY=intval(substr(date('Y-m-d'),8,2));

$query = "SELECT id_paziente,visite, data_arr " .
	 "FROM paziente ";

$result = mysql_query($query) or die(mysql_error());
$i=0;

while ($row = mysql_fetch_array($result)) {
	extract($row);

	$ANNO_AR=intval(substr($row['data_arr'],0,4)); 
	$MESE_AR=intval(substr($row['data_arr'],5,2));
	$DAY_AR=intval(substr($row['data_arr'],8,2));

	$data = mktime(0, 0, 0, $MESE_AR,$DAY_AR,$ANNO_AR, 0);
	$plus=86400*(60*$row['visite']);
	$data=$data+$plus;
	$date_arr=date('Y-m-d',$data);

	$ANNO_AR=intval(substr($date_arr,0,4)); 
	$MESE_AR=intval(substr($date_arr,5,2));
	$DAY_AR=intval(substr($date_arr,8,2));

	$data1 = mktime(0, 0, 0, $MESE,$DAY,$ANNO, 0);
	$data2 = mktime(0, 0, 0, $MESE_AR,$DAY_AR,$ANNO_AR, 0);
	$dif=($data2 - $data1)/(60*60*24);

	if(($dif<10)&&($dif>0)){
		$ALERT[$i]=$row['id_paziente'];
		$ALERT[$i]['data']=$date_arr;
		$i++;
	}
}

$ALERT["numvis"]=$i;
return $ALERT;
}




function mostra_in_table($matrix, $nome){
//$matrix deve essere una matrice oppure un array
//$nome un array contenente i titoli delle colonne 

echo "<table border=5>";
echo "<tr>";
foreach($nome as $col){
echo "<td align=center>".$col."</td>";
}
echo "</tr>\n";

echo "<tr>";

foreach($matrix as $row){
if(count($row)==1){
	echo "<td align=center>".$row."</td>";

}else{

echo "<tr>";
        foreach($row as $cell) {
        	echo "<td align=center>".$cell."</td>";
    }

   echo "</tr>\n";    
}

}
echo "</tr>\n";
echo "</table>";
}

