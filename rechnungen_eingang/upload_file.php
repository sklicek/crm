<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Eingangsrechnungen</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
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
<div class="table">
<p class="header">Eingangsrechnungen</p>
<p><b>PDF-Datei zur Dokumentennummer: </b><?=$nr;?></p>
<form data-ajax="false" name="upload_form" action="save_file.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_rechnung" id="id_rechnung" value="<?=$id_rechnung;?>">
    <input type="file" name="file" id="fileupload" accept="application/pdf">
	<input class="btn" type="submit" name="submit" id="submit" value="upload">
</form>
</div>
</body>
</html>