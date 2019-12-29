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

$arr_jahre=array();
if ($stmt2 = $mysqli -> prepare("SELECT DISTINCT rechnung_datum FROM krechnungen ORDER BY rechnung_datum DESC")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($jahr);
  while ($stmt2 -> fetch()){
	  $arr_jahre[]=$jahr;
  }
}
$stmt2 -> close();
?>
<h2>Jahresübersicht (Ausgangsrechnungen)</h2>

<div class="table">
<center><h3>Bezahlt</h3></center>
<table>
  <thead>
    <tr>
     <th>Kunde</th>
     <th>Jahr</th>
     <th>Bruttobetrag (Gesamt)</th>
    </tr>	
  </thead>
  <tbody>
<?php
$jahresgesamt=0;
for ($i=0;$i<count($arr_jahre);$i++){
	$jr=$arr_jahre[$i];
	$brutto_jahr=0;
	if ($stmt2 = $mysqli -> prepare("SELECT firma, rechnung_datum, bruttowert FROM krechnungen WHERE rechnung_datum = ? ORDER BY rechnung_datum DESC, firma ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($kunde, $jahr, $brutto);
	  while ($stmt2 -> fetch()){
		$brutto_jahr+=$brutto;
		$jahresgesamt+=$brutto;  
		?>
		<tr>
		<td><?=$kunde;?></td>
		<td><?=$jahr;?></td>
		<td><?=number_format($brutto,2);?></td>
		</tr>
		<?php	
	  }
	  ?>
	  <tr style="background-color:lightgrey">
		<td><strong>Bruttobetrag (Gesamt)</strong></td>
		<td><strong><?=$jr;?></strong></td>
		<td><strong><?=number_format($brutto_jahr,2);?></strong></td>
	  </tr>
	  <?php
	}
	$stmt2->close();
}
?>
<tr style="background-color:yellow">
	<td><strong>Bruttobetrag (alle Jahre)</strong></td>
	<td><strong>&nbsp;</strong></td>
	<td><strong><?=number_format($jahresgesamt,2);?></strong></td>
</tr>
</tbody>
</table>
</div>

<div class="table">
<center><h3>Offen (nicht bezahlt)</h3></center>
<table>
  <thead>
    <tr>
     <th>Kunde</th>
     <th>Jahr</th>
     <th>Bruttobetrag (Gesamt)</th>
    </tr>	
  </thead>
  <tbody>
<?php
$jahresgesamt=0;
for ($i=0;$i<count($arr_jahre);$i++){
	$jr=$arr_jahre[$i];
	$brutto_jahr=0;
	if ($stmt2 = $mysqli -> prepare("SELECT firma, rechnung_datum, bruttowert FROM krechnungen_offen WHERE rechnung_datum = ? ORDER BY rechnung_datum DESC, firma ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($kunde, $jahr, $brutto);
	  while ($stmt2 -> fetch()){
		$brutto_jahr+=$brutto;
		$jahresgesamt+=$brutto;  
		?>
		<tr>
		<td><?=$kunde;?></td>
		<td><?=$jahr;?></td>
		<td><?=number_format($brutto,2);?></td>
		</tr>
		<?php	
	  }
	  ?>
	  <tr style="background-color:lightgrey">
		<td><strong>Bruttobetrag (Gesamt)</strong></td>
		<td><strong><?=$jr;?></strong></td>
		<td><strong><?=number_format($brutto_jahr,2);?></strong></td>
	  </tr>
	  <?php
	}
	$stmt2->close();
}
?>
<tr style="background-color:yellow">
	<td><strong>Bruttobetrag (alle Jahre)</strong></td>
	<td><strong>&nbsp;</strong></td>
	<td><strong><?=number_format($jahresgesamt,2);?></strong></td>
</tr>
</tbody>
</table>
</div>
<?php
$mysqli->close();
?>
</body>
</html>