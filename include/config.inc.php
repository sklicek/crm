<?php
//Datenbankserver
$hostname="localhost";
$database="firma";
$username="root";
$password="<Kennwort>";
$port="3306";

//Eigene Firma
const FIRMA_NAME="<Firmenbezeichnung>";
const FIRMA_ADRESSE="<Adresse>";
const FIRMA_PLZ="<Postleitzahl>";
const FIRMA_ORT="<Ortschaft>";
const FIRMA_LAND="Deutschland";
const FIRMA_EMAIL="<Email-Adresse>";
const FIRMA_TEL="<Festnetz-Rufnummer>";

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
