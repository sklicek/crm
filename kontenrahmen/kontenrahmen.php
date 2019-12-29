<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kontenrahmen</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
?>
<div class="table">
<p class="header">Kontenrahmen
  <a class="btn" href="kontenrahmen_bearbeiten.php?action=n">Neues Konto</a>
</p>
<table>
  <thead>
    <tr>
     <th>Konto-Nr</th>
     <th>Bezeichnung</th>
     <th>Zeile-Nr (EÜR)</th>
     <th>Ein-/Ausgabe</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
$counter=0;
if ($stmt2 = $mysqli -> prepare("SELECT id_konto, konto_nr, bezeichnung, typ, zeile_nr FROM kontenrahmen ORDER BY konto_nr ASC")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $konto_nr, $bez, $typ, $zeile_nr);
  while ($stmt2 -> fetch()){
    $counter++;
    ?>
    <tr>
    <td><?=$konto_nr;?></td>
    <td><?=$bez;?></td>
    <td><?=$zeile_nr;?></td>
    <td><?=$typ;?></td>
    <td>
		<a class="btn" href="kontenrahmen_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a>
		<a class="btn" onclick="return confirm('Definitif löschen ?');" href="kontenrahmen_bearbeiten.php?id=<?=$id;?>&action=d">Löschen</a>
	</td>
    </tr>
    <?php	
  }
}
$stmt2->close();
$mysqli->close();
?>
<tr>
 <td colspan="5" style="background-color:lightgrey">Anzahl: <?=$counter;?></td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
