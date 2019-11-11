<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kunden</title>
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
<h1>Lieferantenstamm</h1>
<div class="ui-block-a">
  <a href="lieferanten_bearbeiten.php?action=n" data-role="button" data-icon="plus">Neuer Lieferant</a><br/>
</div>
<table data-role="table" class="ui-responsive table-stroke">
  <thead>
    <tr>
     <th>ID</th>
     <th>Firma</th>
     <th>Person</th>
     <th>Strasse</th>
     <th>PLZ/Ort</th>
     <th>Land</th>
    </tr>	
  </thead>
  <tbody>
<?php
$counter=0;
if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma, person, strasse, plz, ort, land_code FROM kunden WHERE typ='L' ORDER BY kunde_id")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $firma, $name, $strasse, $plz, $ort, $land_code);
  while ($stmt2 -> fetch()){
    $counter++;
    ?>
    <tr>
    <td><a href="lieferanten_bearbeiten.php?id=<?=$id;?>&action=e"><?=$id;?></a></td>
    <td><?=$firma;?></td>
    <td><?=$name;?></td>
    <td><?=$strasse;?></td>
    <td><?=$plz.' '.$ort;?></td>
    <td><?=$land_code;?></td>
    </tr>
    <?php	
  }
}
$stmt2->close();
$mysqli->close();
?>
<tr>
 <td colspan="6" style="background-color:lightgrey">Anzahl: <?=$counter;?></td>
</tr>
</tbody>
</table>
</body>
</html>
