<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Angebote">
<meta name="robots" content="index,follow">
<title>Angebote</title>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
include("../include/menu.php");
@require_once("../include/config.inc.php");

//Angebote zu Ausgangsrechnung umwandeln
$id_angebot=0;
if (isset($_GET['id']) && $_GET['id']!=""){
	$id_angebot=$_GET['id'];
}

//Daten aus Angebot holen
$nr=$dat=$kunde_id="";
if ($stmt2 = $mysqli -> prepare("SELECT nr_angebot, datum_angebot, id_kunde FROM angebote WHERE id_angebot = ?")) {
    $stmt2 -> bind_param('i',$id_angebot);
    $stmt2 -> execute();
    $stmt2 -> bind_result($nr,$dat,$kunde_id);
    $stmt2 -> fetch();
    $stmt2 -> close();
}

//artikel holen um brutto zu bestimmen
$summe_ang=0;
$brutto=0;
$arr_artikel=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT a.id_art, a.bezeichnung, a.vkpreis_einheit, b.einheit, a.anzahl FROM angebote_artikel as a left join einheiten as b on a.id_einheit=b.id_einheit WHERE id_angebot = ? ORDER BY a.id_art")) {
    $stmt2 -> bind_param("i",$id_angebot);    
    $stmt2 -> execute();
    $stmt2 -> bind_result($id_art,$bez,$vkpreis,$einheit,$anzahl);
    while ($stmt2 -> fetch()){
		$arr_artikel[$a][0]=$id_art;
		$arr_artikel[$a][1]=$bez;
		$arr_artikel[$a][2]=$vkpreis;
		$arr_artikel[$a][3]=$einheit;
		$arr_artikel[$a][4]=$anzahl;
		$a++;
	}
    $stmt2 -> close();
}

for ($x=0;$x<count($arr_artikel);$x++){
	$einzelpreis=$arr_artikel[$x][2];
	$anz=$arr_artikel[$x][4];
	$preis=$anz*$einzelpreis;
	$summe_ang+=$preis;
}
$dat_heute=date("Y-m-d",strtotime('now'));
$dat_leist=$dat;
$brutto=$summe_ang;
$dat_bez=null;
$kt_id=52;	//500 Allgemeines Einnahmenkonto
	
//Rechnung eintragen und anzeigen zum bearbeiten	
$msg="";
if ($stmt2 = $mysqli -> prepare("INSERT INTO rechnungen (rechnung_nr, rechnung_datum, kunde_id, leistung_datum, bruttowert,datum_bezahlt, id_konto) VALUES (?,?,?,?,?,?,?)")) {
    $stmt2 -> bind_param("ssisdsi",$nr,$dat_heute,$kunde_id,$dat_leist,$brutto,$dat_bez,$kt_id);
    if ($stmt2 -> execute()){
        $msg="OK";
    } else {
        $msg="Fehler beim Eintragen der Ausgangsrechnung. Bitte Eingaben verbessern und neu erstellen!";
    }
    $stmt2 -> close();
}

if ($msg=="OK"){
	//aktuelle ID von eingetragener rechnung holen
	$id_neue_rechnung=$mysqli->insert_id;	
	$mysqli -> close();
	header("Location: ../rechnungen/rechnungen_bearbeiten.php?id=".$id_neue_rechnung."&action=e");
} else {
	//zurueck zum angebot gehen
	$mysqli -> close();
	 ?>
    <script>
	alert('<?=$msg;?>');
	window.location.href="angebote_bearbeiten.php?id=<?=$id_angebot;?>&action=e";
    </script>
    <?php
    exit;
}
?>
</body>
</html>	