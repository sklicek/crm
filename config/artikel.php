<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Artikel</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
?>
<div class="table">
<p class="header">Artikel
	<a class="btn" href="artikel_bearbeiten.php?action=n">Artikel anlegen</a>
</p>
<table>
  <thead>
    <tr>
	 <th>ID</th>
     <th>Artikel</th>
	 <th>Einheit</th>
	 <th>VK-Preis/Einheit</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
if ($stmt2 = $mysqli -> prepare("SELECT a.id_art, a.bezeichnung, b.einheit, a.vkpreis_einheit FROM artikel as a left join einheiten as b on a.id_einheit=b.id_einheit ORDER BY a.id_art")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $bez, $einheit, $vkpreis);
  while ($stmt2 -> fetch()){
    ?>
    <tr>
    <td><?=$id;?></td>
	<td><?=$bez;?></td>
    <td><?=$einheit;?></td>
	<td><?=$vkpreis;?></td>
    <td><a class="btn" href="artikel_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a></td>
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
