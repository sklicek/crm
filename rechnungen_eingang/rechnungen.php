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
?>
<div class="table">
<p class="header">Eingangsrechnungen
  <a class="btn" href="rechnungen_bearbeiten.php?action=n">Neue Rechnung</a>
  <a class="btn" onclick="fnExcelReport('tabelle');">Export (XLS)</a>
</p>
<form id="search_form" method="post">
<b>Konto-Nr</b>
<select name="id_konto" onchange="submit_form();">
  <option value="">---</option>
  <?php
  if ($stmt2 = $mysqli -> prepare("SELECT DISTINCT a.id_konto, b.konto_nr, b.bezeichnung FROM rechnungen_eingang as a left join kontenrahmen as b on a.id_konto=b.id_konto")) {
    $stmt2 -> execute();
    $stmt2 -> bind_result($id_konto,$konto_nr,$bez);
    while ($stmt2 -> fetch()){
      ?>
      <option value="<?=$id_konto;?>"><?=$konto_nr;?>&nbsp;<?=$bez;?></option>
      <?php
    }
    $stmt2 -> close();
  }
  ?>
</select>
</form>
</div>

<div class="table">
<table id="tabelle">
  <thead>
    <tr>
     <th>Nr</th>
     <th>Datum Rechn.</th>
     <th>Datum Leistung</th>
     <th>Bruttobetrag</th>
     <th>Lieferant</th>
     <th>Beschreibung</th>
     <th>Datum Bezahlt</th>
     <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
$yr=date("Y");
if (isset($_SESSION['kal_jahr'])){
	$yr=$_SESSION['kal_jahr'];
}

$counter=0;
$total=0;
$sql="SELECT a.id_rechnung, a.rechnung_nr, a.rechnung_datum, a.leistung_datum, a.bruttowert, b.firma, a.datum_bezahlt, a.beschreibung, a.pdffile FROM rechnungen_eingang a LEFT JOIN kunden b on a.kunde_id=b.kunde_id";
if ($_POST['id_konto']!="" && $_POST['id_konto']!=""){
  $sql.=" WHERE a.id_konto = ".$_POST['id_konto']." AND year(a.datum_bezahlt) = ?";
} else {
  $sql.=" WHERE year(a.datum_bezahlt) = ?";
}
$sql.=" ORDER BY a.rechnung_datum DESC, a.rechnung_nr DESC";
if ($stmt2 = $mysqli -> prepare($sql)) {
  $stmt2 -> bind_param("i",$yr); 	
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $nr, $dat, $dat_leist, $brutto, $kunde,$dat_bez,$besch,$pdffile);
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
    <td><?=$besch;?></td>
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
 <td colspan="6" style="background-color:lightgrey"><b><?=number_format($total,2);?></b></td>
</tr>
</tbody>
</table>
</div>
<script src="../js/funktionen.js"></script>
<script>
function submit_form(){
	document.getElementById('search_form').submit();
}
</script>
</body>
</html>
