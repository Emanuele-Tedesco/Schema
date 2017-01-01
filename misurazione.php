<?php
session_start();
include ("functions.php");
if ($conn = connection())
{
	mysql_select_db('Telemedicina');
	$id_p = $_GET['id_p'];
	$id_m = $_GET['id_m'];
	$query = "SELECT `file`, `path`,`data` FROM `Misurazioni` WHERE `id_paziente` = '$id_p' and `id` = '$id_m'";
	$result = mysql_query($query);
	if(($n_rows=mysql_num_rows($result)) > 0)
	{
		switch ($_REQUEST['case'])
		{
			case 'read':
				$name = $_GET['name'];
				$surname = $_GET['surname'];
				$_SESSION['visualizza_mis']='';
				header ("Content-type: text/plain");
				$count = 0;
				$rs = mysql_fetch_array($result);
				$file = $rs['file'];
				$rows = split("[\n]", $file);
				while($count < count($rows))
				{
					$cells[$count] = split("[,]",$rows[$count]);
					$count++;
				}
				$colon = count($cells[0]);
				for ($i = 0; $i < count($rows); $i++)
				{
					$_SESSION['visualizza_mis']=$_SESSION["visualizza_mis"]."<tr>";
					for ($j = 0; $j < $colon; $j++)
					{
						$_SESSION['cella'.$i][$j] = $cells[$i][$j];
						$_SESSION['visualizza_mis']=$_SESSION["visualizza_mis"]."<td style="."font:normal 15px Verdana align="."center".">".$cells[$i][$j]."</td>";
					}
					$_SESSION['visualizza_mis']=$_SESSION["visualizza_mis"]."</tr>";
				}
				header("Refresh: 0; URL=../Schema/index.php?case=stampa_m&name=".$name."&surname=".$surname."&data=".$rs['data']."");
				break;
				
			case 'download':
				$rs = mysql_fetch_array($result);
				header ("Content-Disposition: attachment; filename=".$rs['path']."");
				echo $rs['file'];
				break;
		}
	}
}
connection_close($conn);
?>