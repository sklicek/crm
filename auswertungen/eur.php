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
include("menu.php");
@require_once("../include/config.inc.php");
?>
<h1>Auswertungen</h1>
<?php
//Ausgaben nach Konten gruppiert
$jr=date("Y");
$gesamt_ausgaben_konten=0;
$arr_konten=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT konto_nr, bezeichnung, gesamt_bruttowert FROM krechnungen_eingang_kontonr WHERE rechnung_jahr = ? ORDER BY konto_nr ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($konto_nr, $bez, $brutto);
	  while ($stmt2 -> fetch()){
		  $gesamt_ausgaben_konten+=$brutto;
		  $arr_konten[$a][0]=$konto_nr;
		  $arr_konten[$a][1]=$bez;
		  $arr_konten[$a][2]=$brutto;
		  $a++;
     }
}

$gesamt_einnahmen_konten=0;
$arr_konten_ein=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT konto_nr, bezeichnung, gesamt_bruttowert FROM krechnungen_kontonr WHERE rechnung_jahr = ? ORDER BY konto_nr ASC")) {
	  $stmt2 -> bind_param('i',$jr);
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($konto_nr, $bez, $brutto);
	  while ($stmt2 -> fetch()){
		  $gesamt_einnahmen_konten+=$brutto;
		  $arr_konten_ein[$a][0]=$konto_nr;
		  $arr_konten_ein[$a][1]=$bez;
		  $arr_konten_ein[$a][2]=$brutto;
		  $a++;
     }
}

$ergebnis=$gesamt_einnahmen_konten-$gesamt_ausgaben_konten;

$mysqli -> close();
?>
<h3>EÜR für einfachen Kontenrahmen - Kleinunternehmer (<?=$jr;?>)</h3>

<table data-role="table" class="ui-responsive table-stroke">
<thead>
<tr><th colspan="3" style="background-color: yellow;">Betriebseinnahmen</th></tr>
<tr>
<th>Konto-Nr</th>
<th>Bezeichnung</th>
<th>Brutto (Euro)</th>
</tr>
</thead>
<tbody>
<?php
for($i=0;$i<count($arr_konten_ein);$i++) {
	?>
	<tr>
	<td><?=$arr_konten_ein[$i][0];?></td>
	<td><?=$arr_konten_ein[$i][1];?></td>
	<td><?=$arr_konten_ein[$i][2];?></td>
	</tr>
	<?php
}
?>
<tr>
<td><b>Summe Betriebseinnahmen</b></td>
<td></td>
<td><b><?=number_format($gesamt_einnahmen_konten,2);?></b></td>
</tr>

<tr><th colspan="3" style="background-color: lightgrey;">Betriebsausgaben</th></tr>
<?php
for($i=0;$i<count($arr_konten);$i++) {
	?>
	<tr>
	<td><?=$arr_konten[$i][0];?></td>
	<td><?=$arr_konten[$i][1];?></td>
	<td><?=$arr_konten[$i][2];?></td>
	</tr>
	<?php
}
?>
<tr>
<td><b>Summe Betriebsausgaben</b></td>
<td></td>
<td><b><?=number_format($gesamt_ausgaben_konten,2);?></b></td>
</tr>
<tr><th colspan="3" style="background-color: lightblue;">Gewinn/Verlust</th></tr>
<tr>
<td></td>
<td><b>Summe der Betriebseinnahmen:</b></td>
<td><b><?=number_format($gesamt_einnahmen_konten,2);?></b></td>
</tr>
<tr>
<td></td>
<td><b>Summe der Betriebsausgaben:</b></td>
<td><b><?=number_format($gesamt_ausgaben_konten,2);?></b></td>
</tr>
<tr>
<td></td>
<td><b>Steuerpflichtiger Gewinn/Verlust:</b></td>
<td><b><?=number_format($ergebnis,2);?></b></td>
</tr>
</tbody>
</table>
</body>
</html>
