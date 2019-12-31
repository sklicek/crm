<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Angebote</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
?>
<div class="table">
<p class="header">Angebote
  <a class="btn" href="angebote_bearbeiten.php?action=n">Neues Angebot</a>
</p>
<table id="tabelle">
  <thead>
    <tr>
     <th>Nr</th>
     <th>Datum</th>
     <th>Kunde</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
if ($stmt2 = $mysqli -> prepare("SELECT a.id_angebot, a.nr_angebot, a.datum_angebot, b.firma FROM angebote a LEFT JOIN kunden b on a.id_kunde=b.kunde_id ORDER BY a.datum_angebot DESC, a.nr_angebot DESC")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $nr, $dat, $kunde);
  while ($stmt2 -> fetch()){
    if ($dat!="") $dat=date("d.m.Y",strtotime($dat));
    ?>
    <tr>
    <td><?=$nr;?></td>
    <td><?=$dat;?></td>
    <td><?=$kunde;?></td>
    <td>
		<a class="btn" href="angebote_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a>
	</td>
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
