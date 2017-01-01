<?php
	session_start();
	$_SESSION['sel'] = 0;
	for($i = 2; $i < 19; $i ++)
		if (isset($_POST[''.$i.'']))
		{
			$_SESSION['sel'] = $i;
			header("Refresh: 0; URL=../Schema/index.php?case=research_done1");
		}
?>