<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
session_start();
include("../include/menu.php");
@require_once("../include/config.inc.php");

$jr=date("Y");
if (isset($_SESSION['kal_jahr'])){
	$jr=$_SESSION['kal_jahr'];
}
?>
<div class="table">
<p class="header">Einnahmenüberschussrechnung (EÜR) für einfachen Kontenrahmen - Kleinunternehmer (<?=$jr;?>)
	<a class="btn" onclick="fnExcelReport('tabelle');">Export (XLS)</a>
</p>
<?php
//Kontenrahmen holen
$arr_kontenrahmen=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT konto_nr, bezeichnung, zeile_nr, typ FROM kontenrahmen ORDER BY konto_nr ASC")) {
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($konto_nr, $bez, $zeile_nr, $typ);
	  while ($stmt2 -> fetch()){
	  	  $arr_kontenrahmen[$a][0]=$konto_nr;
	  	  $arr_kontenrahmen[$a][1]=$bez;
	  	  $arr_kontenrahmen[$a][2]=$zeile_nr;
	  	  $arr_kontenrahmen[$a][3]=$typ;
	  	  $a++;
	  }
	  $stmt2 -> close();
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
     $stmt2 -> close();
}	

//Ausgaben nach Konten gruppiert
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
     $stmt2 -> close();
}
$ergebnis=$gesamt_einnahmen_konten-$gesamt_ausgaben_konten;

$firma_name=$firma_adr=$firma_plz=$firma_ort=$firma_steuernr="";
if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma, strasse, plz, ort, steuer_nr FROM kunden WHERE typ='F'")) {
  $stmt2 -> execute();
  $stmt2 -> bind_result($id, $firma_name, $firma_adr, $firma_plz, $firma_ort, $firma_steuernr);
  $stmt2 -> fetch();
  $stmt2 -> close();
}
$mysqli -> close();
?>
<table>
<tbody>
<tr>
<td><b>Firma:</b></td><td><?=$firma_name;?></td>
</tr>
<tr>
<td><b>Adresse:</b></td><td><?=$firma_adr;?><br><?=$firma_plz;?> <?=$firma_ort;?></td>
</tr>
<tr>
<td><b>Steuernummer:</b></td><td><?=$firma_steuernr;?></td>
</tr>
</tbody>
</table>
</div>
<div class="table">
<table id="tabelle">
<thead>
<tr><th colspan="4" style="background-color: yellow;">Betriebseinnahmen</th></tr>
<tr>
<th>Zeile</th>
<th>Konto-Nr</th>
<th>Bezeichnung</th>
<th>Brutto (Euro)</th>
</tr>
</thead>
<tbody>
<?php
for ($i=0;$i<count($arr_kontenrahmen);$i++){
	//Betriebseinnahmen
	if ($arr_kontenrahmen[$i][3]=="E"){
		$ktnr=$arr_kontenrahmen[$i][0];
		$bez=$arr_kontenrahmen[$i][1];
		$zeilenr=$arr_kontenrahmen[$i][2];
		$brutto=0;
		for($e=0;$e<count($arr_konten_ein);$e++) {
			if ($arr_konten_ein[$e][0]==$ktnr){
				$brutto=$arr_konten_ein[$e][2];
				break;
			}
		}
		?>
		<tr>
			<td><?=$zeilenr;?></td>
			<td><?=$ktnr;?></td>
			<td><?=$bez;?></td>
			<td><?=number_format($brutto,2);?></td>
		</tr>
		<?php
	}		
}
?>
<tr>
<td>22</td>
<td></td>
<td><b>Summe Betriebseinnahmen</b></td>
<td><b><?=number_format($gesamt_einnahmen_konten,2);?></b></td>
</tr>
<tr><th colspan="4" style="background-color: lightgrey;">Betriebsausgaben</th></tr>
<?php
for ($i=0;$i<count($arr_kontenrahmen);$i++){
	//Betriebsausgaben
	if ($arr_kontenrahmen[$i][3]=="A"){
		$ktnr=$arr_kontenrahmen[$i][0];
		$bez=$arr_kontenrahmen[$i][1];
		$zeilenr=$arr_kontenrahmen[$i][2];
		$brutto=0;
		for($e=0;$e<count($arr_konten);$e++) {
			if ($arr_konten[$e][0]==$ktnr){
				$brutto=$arr_konten[$e][2];
				break;
			}
		}
		?>
		<tr>
			<td><?=$zeilenr;?></td>
			<td><?=$ktnr;?></td>
			<td><?=$bez;?></td>
			<td><?=number_format($brutto,2);?></td>
		</tr>
		<?php
	}		
}
?>
<tr>
<td>65</td>
<td></td>
<td><b>Summe Betriebsausgaben</b></td>
<td><b><?=number_format($gesamt_ausgaben_konten,2);?></b></td>
</tr>
<tr><th colspan="4" style="background-color: lightblue;">Gewinn/Verlust</th></tr>
<tr>
<td>89</td>
<td></td>
<td><b>Summe der Betriebseinnahmen (Übertrag aus Zeile 22)</b></td>
<td><b><?=number_format($gesamt_einnahmen_konten,2);?></b></td>
</tr>
<tr>
<td>90</td>
<td></td>
<td><b>abzüglich Summe der Betriebsausgaben (Übertrag aus Zeile 65)</b></td>
<td><b><?=number_format($gesamt_ausgaben_konten,2);?></b></td>
</tr>
<tr>
<td>109</td>
<td></td>
<td><b>Steuerpflichtiger Gewinn/Verlust</b></td>
<td><b><?=number_format($ergebnis,2);?></b></td>
</tr>
</tbody>
</table>
</div>
<script src="../js/funktionen.js"></script>
</body>
</html>
