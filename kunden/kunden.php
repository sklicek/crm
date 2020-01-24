<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kontakte</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");
@require_once("../include/rechnungen_funktionen.php");

$typ="K";
if (isset($_GET['typ']) && $_GET['typ']!=""){
	$typ=htmlspecialchars($_GET['typ']);
}

$jr=date("Y");
if (isset($_SESSION['kal_jahr'])){
	$jr=$_SESSION['kal_jahr'];
}
?>
<div class="table">
<p class="header">Kontakte
  <?php
  if ($typ=="K"){
    ?>
    <a class="btn" href="kunden_bearbeiten.php?action=n&typ=K">Kunde anlegen</a>
    <?php
  }
  if ($typ=="L"){
    ?>
    <a class="btn" href="kunden_bearbeiten.php?action=n&typ=L">Lieferant anlegen</a>
    <?php
  }
  ?>
</p>
<table>
  <thead>
    <tr>
     <th>Firma</th>
     <th>Person</th>
     <th>Strasse</th>
     <th>PLZ/Ort</th>
     <th>Land</th>
     <th>Offen</th>
     <th>Bezahlt</th>
	   <th>Aktion</th>
    </tr>	
  </thead>
  <tbody>
<?php
$arr_firmen=getKunden($typ,$mysqli);
for ($i=0;$i<count($arr_firmen);$i++){
	$id=$arr_firmen[$i][0];
	$firma=$arr_firmen[$i][1];
	$name=$arr_firmen[$i][2];
	$strasse=$arr_firmen[$i][3];
	$plz=$arr_firmen[$i][4];
	$ort=$arr_firmen[$i][5];
	$land_code=$arr_firmen[$i][6];
	$typ=$arr_firmen[$i][7];
	
	$offen=getOffeneRechnungen($firma,$jr,$typ,$mysqli);
	$bezahlt=getBezahlteRechnungen($firma,$jr,$typ,$mysqli)
	?>
    <tr>
    <td><?=$firma;?></td>
    <td><?=$name;?></td>
    <td><?=$strasse;?></td>
    <td><?=$plz.' '.$ort;?></td>
    <td><?=$land_code;?></td>
    <td><?=number_format($offen,2);?>&euro;</td>
    <td><?=number_format($bezahlt,2);?>&euro;</td>
	<td><a class="btn" href="kunden_bearbeiten.php?id=<?=$id;?>&typ=<?=$typ;?>&action=e">Bearbeiten</a></td>
    </tr>
    <?php	
}
$mysqli->close();
?>
</tbody>
</table>
</div>
</body>
</html>
