<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kontakte</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");

$typ="K";
if (isset($_GET['typ']) && $_GET['typ']!=""){
	$typ=htmlspecialchars($_GET['typ']);
}
?>
<div class="table">
<p class="header">Kontakte
  <a class="btn" href="kunden_bearbeiten.php?action=n&typ=K">Kunde anlegen</a>
  <a class="btn" href="kunden_bearbeiten.php?action=n&typ=L">Lieferant anlegen</a>
</p>
<table>
  <thead>
    <tr>
     <th>Firma</th>
     <th>Person</th>
     <th>Strasse</th>
     <th>PLZ/Ort</th>
     <th>Land</th>
	 <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma, person, strasse, plz, ort, land_code FROM kunden WHERE typ=? ORDER BY kunde_id")) {
  $stmt2 -> bind_param('s',$typ);
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $firma, $name, $strasse, $plz, $ort, $land_code);
  while ($stmt2 -> fetch()){
    ?>
    <tr>
    <td><?=$firma;?></td>
    <td><?=$name;?></td>
    <td><?=$strasse;?></td>
    <td><?=$plz.' '.$ort;?></td>
    <td><?=$land_code;?></td>
	<td><a class="btn" href="kunden_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a></td>
    </tr>
    <?php	
  }
}
$stmt2->close();
$mysqli->close();
?>
</tbody>
</table>
</div>
</body>
</html>
