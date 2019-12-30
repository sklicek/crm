<?php
//Datenbankserver
$hostname="localhost";
$database="firma";
$username="root";
$password="<Kennwort>";
$port="3306";

//Datenbank-Verbindung
$mysqli = @(new mysqli($hostname, $username, $password, $database, $port));
if ($mysqli->connect_error) {
	echo "Fehler bei der Verbindung: " .
	mysqli_connect_error() . "<hr />";
	exit();
}
if (!$mysqli->set_charset("utf8")) {
	echo "Fehler beim Laden von UTF8 ". $mysqli->error;
}
?>
