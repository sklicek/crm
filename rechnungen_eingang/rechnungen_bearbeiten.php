<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Eingangsrechnungen</title>
<!-- jquery mobile -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<?php
include("menu.php");
require_once("../include/config.inc.php");

$action="";
if (isset($_GET['action'])){
    $action=$_GET['action'];
} elseif (isset($_POST['action'])){
    $action=$_POST['action'];
}

$id_rechnung=0;
if (isset($_GET['id'])){
    $id_rechnung=$_GET['id'];
} elseif (isset($_POST['id'])){
    $id_rechnung=$_POST['id'];
}

//daten speichern
$msg="";
if (isset($_POST['submit'])){
    $nr=htmlspecialchars($_POST['nr']);
    $dat=htmlspecialchars($_POST['dat']);
    $dat_leist=htmlspecialchars($_POST['dat_leist']);
    $kunde_id=htmlspecialchars($_POST['kunde']);
    $brutto=htmlspecialchars($_POST['brutto']);
    $dat_bez=htmlspecialchars($_POST['dat_bez']);
    $besch=htmlspecialchars($_POST['besch']);
    $kt_id=htmlspecialchars($_POST['konto']);
    
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO rechnungen_eingang (rechnung_nr, rechnung_datum, kunde_id, leistung_datum, bruttowert, datum_bezahlt, beschreibung, id_konto) VALUES (?,?,?,?,?,?,?,?)")) {
            $stmt2 -> bind_param("ssisdssi",$nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$besch,$kt_id);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE rechnungen_eingang SET rechnung_nr = ?, rechnung_datum = ?, kunde_id = ?, leistung_datum = ?, bruttowert = ?, datum_bezahlt = ?, beschreibung = ?, id_konto = ? WHERE id_rechnung = ?")) {
            $stmt2 -> bind_param("ssisdssii",$nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$besch,$kt_id,$id_rechnung);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    }
}

//daten auslesen
$nr=$dat=$kunde_id=$dat_leist=$dat_bez=$besch=$konto_id="";
$brutto=0.00;
if ($action=="e" && $id_rechnung!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT rechnung_nr, rechnung_datum, kunde_id, leistung_datum, bruttowert, datum_bezahlt, beschreibung, id_konto FROM rechnungen_eingang WHERE id_rechnung = ?")) {
        $stmt2 -> bind_param('i',$id_rechnung);
        $stmt2 -> execute();
        $stmt2 -> bind_result($nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$besch,$konto_id);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
    if ($dat!="") $dat=date("Y-m-d",strtotime($dat));
    if ($dat_leist!="") $dat_leist=date("Y-m-d",strtotime($dat_leist));
    if ($dat_bez!="") $dat_bez=date("Y-m-d",strtotime($dat_bez));
}
?>
<h1>Eingangsrechnungen bearbeiten</h1>
<?=$msg;?>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_rechnung;?>">
    <label for="nr">Rechnung-Nr</label>
    <input type="text" name="nr" data-clear-btn="true" maxlength="25" value="<?=$nr;?>">
    <label for="dat">Rechnung-Datum</label>
    <input type="date" name="dat" data-clear-btn="true" maxlength="15" value="<?=$dat;?>">
    <label for="besch">Beschreibung</label>
    <input type="text" name="besch" data-clear-btn="true" maxlength="250" value="<?=$besch;?>">
    <label for="dat_leist">Leistung/Liefer-Datum</label>
    <input type="date" name="dat_leist" data-clear-btn="true" maxlength="15" value="<?=$dat_leist;?>">
    <label for="brutto">Brutto-Betrag</label>
    <input type="number" step="0.01" name="brutto" data-clear-btn="true" value="<?=$brutto;?>">
    <label for="konto">Konto</label>
    <select name="konto">
        <option value="">---</option>
        <?php
        if ($stmt2 = $mysqli -> prepare("SELECT id_konto, konto_nr, bezeichnung FROM kontenrahmen ORDER BY konto_nr ASC")) {
            $stmt2 -> execute();
            $stmt2 -> bind_result($kt_id, $kt, $bez_konto);
            while ($stmt2 -> fetch()){
                if ($kt_id==$konto_id){
                    ?>
                    <option value="<?=$kt_id;?>" selected><?=$kt;?>&nbsp;<?=$bez_konto;?></option>
                    <?php
                } else {
                    ?>
                    <option value="<?=$kt_id;?>"><?=$kt;?>&nbsp;<?=$bez_konto;?></option>
                    <?php
                }
            }
            $stmt2 -> close();
        }
        ?>
    </select>
    <label for="kunde">Lieferant</label>
    <select name="kunde">
        <option value="">---</option>
        <?php
        if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma FROM kunden WHERE typ='L' ORDER BY firma")) {
            $stmt2 -> execute();
            $stmt2 -> bind_result($kd_id, $firma);
            while ($stmt2 -> fetch()){
                if ($kd_id==$kunde_id){
                    ?>
                    <option value="<?=$kd_id;?>" selected><?=$firma;?></option>
                    <?php
                } else {
                    ?>
                    <option value="<?=$kd_id;?>"><?=$firma;?></option>
                    <?php
                }
            }
            $stmt2 -> close();
        }
        ?>
    </select>
    <label for="dat_bez">Bezahl-Datum</label>
    <input type="date" name="dat_bez" data-clear-btn="true" maxlength="15" value="<?=$dat_bez;?>">
    <input type="submit" name="submit" value="Speichern">
</form>
<?php
$mysqli -> close();
?>
</body>
</html>