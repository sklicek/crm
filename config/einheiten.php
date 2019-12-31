<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Einheiten zu Artikel</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
?>
<div class="table">
<p class="header">Einheiten zu Artikel
	<a class="btn" href="einheiten_bearbeiten.php?action=n">Einheit anlegen</a>
</p>
<table>
  <thead>
    <tr>
	 <th>ID</th>
     <th>Einheit</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
if ($stmt2 = $mysqli -> prepare("SELECT id_einheit, einheit FROM einheiten ORDER BY id_einheit")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $einheit);
  while ($stmt2 -> fetch()){
    ?>
    <tr>
    <td><?=$id;?></td>
    <td><?=$einheit;?></td>
    <td><a class="btn" href="einheiten_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a></td>
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
