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
    $kt_id=htmlspecialchars($_POST['konto']);
      
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO rechnungen (rechnung_nr, rechnung_datum, kunde_id, leistung_datum, bruttowert,datum_bezahlt, id_konto) VALUES (?,?,?,?,?,?,?)")) {
            $stmt2 -> bind_param("ssisdsi",$nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$kt_id);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE rechnungen SET rechnung_nr = ?, rechnung_datum = ?, kunde_id = ?, leistung_datum = ?, bruttowert = ?, datum_bezahlt = ?, id_konto = ? WHERE id_rechnung = ?")) {
            $stmt2 -> bind_param("ssisdsii",$nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$kt_id,$id_rechnung);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    }
	 ?>
    <script>
	alert(<?=$msg;?>);
	window.location.href="rechnungen.php";
    </script>
    <?php
    exit;
}

//rechnung löschen
if ($action=="d" && $id_rechnung!=0){
    if ($stmt2 = $mysqli -> prepare("DELETE FROM rechnungen WHERE id_rechnung = ?")) {
        $stmt2 -> bind_param("i",$id_rechnung);
        $stmt2 -> execute();
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
    ?>
    <script>
    window.location.href="rechnungen.php";
    </script>
    <?php
    exit;
}

//daten auslesen
$nr=$dat=$kunde_id=$dat_leist=$dat_bez=$konto_id="";
$brutto=0.00;
if ($action=="e" && $id_rechnung!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT rechnung_nr, rechnung_datum, kunde_id, leistung_datum, bruttowert, datum_bezahlt, id_konto FROM rechnungen WHERE id_rechnung = ?")) {
        $stmt2 -> bind_param('i',$id_rechnung);
        $stmt2 -> execute();
        $stmt2 -> bind_result($nr,$dat,$kunde_id,$dat_leist,$brutto,$dat_bez,$konto_id);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
    if ($dat!="") $dat=date("Y-m-d",strtotime($dat));
    if ($dat_leist!="") $dat_leist=date("Y-m-d",strtotime($dat_leist));
    if ($dat_bez!="") $dat_bez=date("Y-m-d",strtotime($dat_bez));
}
?>
<div class="table">
<p class="header">Ausgangsrechnungen bearbeiten</p>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_rechnung;?>">
	<table>
	<tbody>
    <tr>
	<td><b for="nr">Rechnung-Nr</b></td>
    <td><input type="text" name="nr" maxlength="25" value="<?=$nr;?>"></td>
	</tr>
	<tr>
    <td><b for="dat">Rechnung-Datum</b></td>
    <td><input type="date" name="dat" maxlength="15" value="<?=$dat;?>"></td>
	</tr>
	<tr>
    <td><b for="dat_leist">Leistung/Liefer-Datum</b></td>
    <td><input type="date" name="dat_leist" maxlength="15" value="<?=$dat_leist;?>"></td>
	</tr>
	<tr>
    <td><b for="brutto">Brutto-Betrag</b></td>
    <td><input type="number" step="0.01" name="brutto" value="<?=$brutto;?>"></td>
	</tr>
	<tr>
    <td><b for="konto">Konto</b></td>
    <td><select name="konto">
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
    </select></td>
	</tr>
	<tr>
    <td><b for="kunde">Kunde</b></td>
    <td><select name="kunde">
        <option value="">---</option>
        <?php
        if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma FROM kunden WHERE typ='K' ORDER BY firma")) {
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
    </select></td>
	</tr>
	<tr>
    <td><b for="dat_bez">Bezahl-Datum</b></td>
    <td><input type="date" name="dat_bez" maxlength="15" value="<?=$dat_bez;?>"></td>
	</tr>
	</tbody>
	</table>
    <input class="btn" type="submit" name="submit" value="Speichern">
</form>
</div>
<?php
$mysqli -> close();
?>
</body>
</html>