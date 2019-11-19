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
<h1>Kontenrahmen</h1>
<div class="ui-block-a">
  <a href="kontenrahmen_bearbeiten.php?action=n" data-role="button" data-icon="plus">Neues Konto</a><br/>
</div>
<table data-role="table" class="ui-responsive table-stroke">
  <thead>
    <tr>
     <th>ID</th>
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
    <td><a href="kontenrahmen_bearbeiten.php?id=<?=$id;?>&action=e"><?=$id;?></a></td>
    <td><?=$konto_nr;?></td>
    <td><?=$bez;?></td>
    <td><?=$zeile_nr;?></td>
    <td><?=$typ;?></td>
    <td><a onclick="return confirm('Definitif löschen ?');" href="kontenrahmen_bearbeiten.php?id=<?=$id;?>&action=d" data-role="button" data-mini="true">Löschen</a></td>
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
</body>
</html>
