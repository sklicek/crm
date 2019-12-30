<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Mein CRM</title>
<!-- jquery mobile -->
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
session_start();
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_loc=$protocol.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['root_path']=$protocol.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);

include("include/menu.php");
@require_once("include/config.inc.php");

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
if (isset($_POST['jahr']) && $_POST['jahr']!=""){
	$jr=$_POST['jahr'];
}
$_SESSION['kal_jahr']=$jr;

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
	  $stmt2 -> close();
}

//kleinstes Jahr holen
$min_jr=0;
if ($stmt2 = $mysqli -> prepare("SELECT DISTINCT rechnung_datum FROM krechnungen_eingang ORDER BY rechnung_datum ASC")) {
	  $stmt2 -> execute();
	  $stmt2 -> bind_result($min_jr);
	  $stmt2 -> fetch();
	  $stmt2 -> close();
}
if ($min_jr==0){
	$min_jr=date("Y");
}

//Ergebnis
$ergebnis=$gesamt_einnahmen-$gesamt_ausgaben;  

$mysqli -> close();
?>
<div class="table">
<p class="header">Aktuelles Kalenderjahr:&nbsp;<b><?=$jr;?></b></p>
<p>
Anderes Kalenderjahr:
<form id="submit_jr" method="post">
<select name="jahr" onchange="submit_form();">
<option value="">--- Bitte wählen ---</option> 
<?php
for ($i=$min_jr;$i<=$jr;$i++){
	if ((isset($_POST['jahr']) && $i==$_POST['jahr']) || $jr == $i){
		?>
		<option selected value="<?=$i;?>"><?=$i;?></option> 
		<?php
	} else {
		?>
		<option value="<?=$i;?>"><?=$i;?></option> 
		<?php
	}
}
?>
</select>
</form>
</p>
<p class="header">Im System gespeichert</p>
<table>
<tbody>
<tr>
  <td>Kunden</td>
  <td><?=$counter;?></td>
</tr>
<tr>
  <td>Rechnungen<br>Ausgang</td>
  <td><?=$counter_rech;?></td>  
</tr>
<tr>
  <td>Lieferanten</td>
  <td><?=$counter_lief;?></td>
</tr>
<tr>  
  <td>Rechnungen<br>Eingang</td>
  <td><?=$counter_rech_ein;?></td>
</tr>
</tbody>
</table>
</div>
<div class="table">
<p class="header">Ergebnis <?=$jr;?></p>
<table>
<tbody>
<tr>
  <td>Einnahmen</td>
  <td><?=number_format($gesamt_einnahmen,2);?>&nbsp;&euro;</td>
</tr>
<tr>    
  <td>- Ausgaben</td>
  <td><?=number_format($gesamt_ausgaben,2);?>&nbsp;&euro;</td>  
</tr>
<tr>
<td>= Ergebnis</td>
<td><?=number_format($ergebnis,2);?>&nbsp;&euro;</td>
</tr>
</tbody>
</table>
</div>
<script>
function submit_form(){
	document.getElementById('submit_jr').submit();
}
</script>
</body>
</html>
