<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kontenrahmen</title>
<!-- jquery mobile -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<?php
include("menu.php");
@require_once("../include/config.inc.php");
?>
<h1>Auswertungen</h1>

<ul>
	<li><a href="eur.php">EÜR</a></li>
	<li><a href="rechnungen_view.php">Ausgangsrechnungen-Jahresübersicht</a></li>
	<li><a href="rechnungen_eingang_view.php">Eingangsrechnungen-Jahresübersicht</a></li>
</ul>

</body>
</html>
