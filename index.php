<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM</title>
<!-- jquery mobile -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<?php
include("include/menu.php");
@require_once("include/config.inc.php");
?>
<h1>Hauptseite</h1>
<h2>Dashboard</h2>
<?php
$counter=0;
if ($stmt2 = $mysqli -> prepare("SELECT count(*) FROM kunden where typ='K'")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($counter);
  $stmt2 -> fetch();
  $stmt2 -> close();
}

$counter_lief=0;
if ($stmt2 = $mysqli -> prepare("SELECT count(*) FROM kunden where typ='L'")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($counter_lief);
  $stmt2 -> fetch();
  $stmt2 -> close();
}

$counter_rech=0;
if ($stmt2 = $mysqli -> prepare("SELECT count(*) FROM rechnungen")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($counter_rech);
  $stmt2 -> fetch();
  $stmt2 -> close();
}

$counter_rech_ein=0;
if ($stmt2 = $mysqli -> prepare("SELECT count(*) FROM rechnungen_eingang")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($counter_rech_ein);
  $stmt2 -> fetch();
  $stmt2 -> close();
}

//Einnahmen
$gesamt_einnahmen=0;
$jr=date("Y");
if ($stmt2 = $mysqli -> prepare("SELECT firma, rechnung_datum, bruttowert FROM krechnungen WHERE rechnung_datum = ? ORDER BY rechnung_datum DESC, firma ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($kunde, $jahr, $brutto);
	  while ($stmt2 -> fetch()){
		$gesamt_einnahmen+=$brutto;
	}
}  
		
//Ausgaben
$gesamt_ausgaben=0;
if ($stmt2 = $mysqli -> prepare("SELECT firma, rechnung_datum, bruttowert FROM krechnungen_eingang WHERE rechnung_datum = ? ORDER BY rechnung_datum DESC, firma ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($kunde, $jahr, $brutto);
	  while ($stmt2 -> fetch()){
		$gesamt_ausgaben+=$brutto;
     }
}

//Ergebnis
$ergebnis=$gesamt_einnahmen-$gesamt_ausgaben;  

$mysqli -> close();
?>
<table data-role="table" class="ui-responsive table-stroke">
<thead>
<tr>
  <th>Kunden</th>
  <th style="background-color:yellow">Rechnungen<br>Ausgang</th>
  <th>Lieferanten</th>
  <th style="background-color:lightgrey">Rechnungen<br>Eingang</th>
</tr>
</thead>
<tbody>
<tr>
<td><a href="kunden/kunden.php"><?=$counter;?></a></td>
<td style="background-color:yellow"><a href="rechnungen/rechnungen.php"><?=$counter_rech;?></a></td>
<td><a href="lieferanten/lieferanten.php"><?=$counter_lief;?></a></td>
<td style="background-color:lightgrey"><a href="rechnungen_eingang/rechnungen.php"><?=$counter_rech_ein;?></a></td>
</tr>
</tbody>
</table>
<h2>Ergebnis <?=$jr;?></h2>
<table data-role="table" class="ui-responsive table-stroke">
<thead>
<th>&nbsp;</th>
<th>Euro</th>
</thead>
<tbody>
<tr>
  <td style="background-color:yellow">Einnahmen</td>
  <td style="background-color:yellow"><?=number_format($gesamt_einnahmen,2);?></td>
</tr>
<tr>    
  <td style="background-color:lightgrey">- Ausgaben</td>
  <td style="background-color:lightgrey"><?=number_format($gesamt_ausgaben,2);?></td>  
</tr>
<tr>
<td><b>= Ergebnis</b></td>
<td><b><?=number_format($ergebnis,2);?></b></td>
</tr>
</tbody>
</table>
</body>
</html>
