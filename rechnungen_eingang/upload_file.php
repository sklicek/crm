<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Eingangsrechnungen</title>
<!-- jquery mobile -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<?php
include("menu.php");
@require_once("../include/config.inc.php");

$id_rechnung=0;
if (isset($_GET["id_rechnung"])){
	$id_rechnung=$_GET["id_rechnung"];
}

$nr="";
if (isset($_GET["nr"])){
	$nr=$_GET["nr"];
}
?>
<h1>Eingangsrechnungen</h1>
PDF-Datei zur Dokumentennummer: <?=$nr;?>
<form data-ajax="false" name="upload_form" action="save_file.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_rechnung" id="id_rechnung" value="<?=$id_rechnung;?>">
    <input type="file" name="file" id="fileupload" accept="application/pdf">
	<input type="submit" name="submit" id="submit" value="upload">
</form>
</body>
</html>