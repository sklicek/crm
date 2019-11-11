<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Ausgangsrechnungen</title>
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
<h1>Ausgangsrechnungen</h1>
<p>
<div class="ui-block-a">
  <a href="rechnungen_bearbeiten.php?action=n" data-role="button" data-icon="plus">Neue Rechnung</a><br/>
</div>
</p>

<table data-role="table" class="ui-responsive table-stroke">
  <thead>
    <tr>
     <th>ID</th>
     <th>Nr</th>
     <th>Datum Rechn.</th>
     <th>Datum Leistung</th>
     <th>Bruttobetrag</th>
     <th>Kunde</th>
     <th>Datum Bezahlt</th>
    </tr>	
  </thead>
  <tbody>
<?php
$counter=0;
$total=0;
if ($stmt2 = $mysqli -> prepare("SELECT a.id_rechnung, a.rechnung_nr, a.rechnung_datum, a.leistung_datum, a.bruttowert, b.firma, a.datum_bezahlt FROM rechnungen a LEFT JOIN kunden b on a.kunde_id=b.kunde_id ORDER BY a.rechnung_datum DESC, a.rechnung_nr DESC")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $nr, $dat, $dat_leist, $brutto, $kunde,$dat_bez);
  while ($stmt2 -> fetch()){
    $counter++;
    if ($dat!="") $dat=date("d.m.Y",strtotime($dat));
    if ($dat_leist!="") $dat_leist=date("d.m.Y",strtotime($dat_leist));
    if ($dat_bez!="") $dat_bez=date("d.m.Y",strtotime($dat_bez));
    $total+=$brutto;
    ?>
    <tr>
    <td><a href="rechnungen_bearbeiten.php?id=<?=$id;?>&action=e"><?=$id;?></a></td>
    <td><?=$nr;?></td>
    <td><?=$dat;?></td>
    <td><?=$dat_leist;?></td>
    <td><?=$brutto;?></td>
    <td><?=$kunde;?></td>
    <td><?=$dat_bez;?></td>
    </tr>
    <?php	
  }
}
$stmt2->close();
$mysqli->close();
?>
<tr>
 <td colspan="1" style="background-color:lightgrey">Anzahl: <?=$counter;?></td>
 <td colspan="3" style="background-color:lightgrey">&nbsp;</td>
 <td colspan="3" style="background-color:lightgrey"><b><?=number_format($total,2);?></b></td>
</tr>
</tbody>
</table>
</body>
</html>
