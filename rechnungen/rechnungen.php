<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Ausgangsrechnungen</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
?>
<div class="table">
<p class="header">Ausgangsrechnungen
  <a class="btn" href="rechnungen_bearbeiten.php?action=n">Neue Rechnung</a>
  <a class="btn" onclick="fnExcelReport('tabelle');">Export (XLS)</a>
</p>
<table id="tabelle">
  <thead>
    <tr>
     <th>Nr</th>
     <th>Datum Rechn.</th>
     <th>Datum Leistung</th>
     <th>Bruttobetrag</th>
     <th>Kunde</th>
     <th>Datum Bezahlt</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
$counter=0;
$total=0;
if ($stmt2 = $mysqli -> prepare("SELECT a.id_rechnung, a.rechnung_nr, a.rechnung_datum, a.leistung_datum, a.bruttowert, b.firma, a.datum_bezahlt, a.pdffile FROM rechnungen a LEFT JOIN kunden b on a.kunde_id=b.kunde_id ORDER BY a.rechnung_datum DESC, a.rechnung_nr DESC")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $nr, $dat, $dat_leist, $brutto, $kunde,$dat_bez,$pdffile);
  while ($stmt2 -> fetch()){
    $counter++;
    if ($dat!="") $dat=date("d.m.Y",strtotime($dat));
    if ($dat_leist!="") $dat_leist=date("d.m.Y",strtotime($dat_leist));
    if ($dat_bez!="") $dat_bez=date("d.m.Y",strtotime($dat_bez));
    $total+=$brutto;
    ?>
    <tr>
    <td>
    <?php
		if ($pdffile!=""){
			?>
			<a href="<?=$pdffile;?>" target="_blank"><?=$nr;?></a>
			<?php
		} else {
            echo $nr;
		}
		?>
    </td>
    <td><?=$dat;?></td>
    <td><?=$dat_leist;?></td>
    <td><?=$brutto;?></td>
    <td><?=$kunde;?></td>
    <td><?=$dat_bez;?></td>
    <td>
		<a class="btn" href="rechnungen_bearbeiten.php?id=<?=$id;?>&action=e">Bearbeiten</a>
		<a class="btn" href="upload_file.php?id_rechnung=<?=$id;?>&nr=<?=$nr;?>">Dateianhang</a>
		<a class="btn" onclick="return confirm('Definitif löschen ?');" href="rechnungen_bearbeiten.php?id=<?=$id;?>&action=d">Löschen</a>
	</td>
    </tr>
    <?php	
  }
}
$stmt2->close();
$mysqli->close();
?>
<tr>
 <td colspan="1" style="background-color:lightgrey">Anzahl: <?=$counter;?></td>
 <td colspan="2" style="background-color:lightgrey">&nbsp;</td>
 <td colspan="5" style="background-color:lightgrey"><b><?=number_format($total,2);?></b></td>
</tr>
</tbody>
</table>
</div>
<script src="../js/funktionen.js"></script>
</body>
</html>
