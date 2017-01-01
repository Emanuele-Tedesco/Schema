<?php
session_start();
unset ($_SESSION['user']);
unset ($_SESSION['type']);
if ($_SESSION['operator'])
	unset($_SESSION['operator']);
if ($_SESSION['admin'])
	unset($_SESSION['admin']);
if ($_SESSION['paziente'])
	unset($_SESSION['pazien']);
unset($_SESSION['nome_paziente']);
unset($_SESSION['cognome_paziente']);
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['surname']);
header("Refresh: 0; URL=../Schema/index.php");
?>