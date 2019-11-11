<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Kunden</title>
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

$id_kunde=0;
if (isset($_GET['id'])){
    $id_kunde=$_GET['id'];
} elseif (isset($_POST['id'])){
    $id_kunde=$_POST['id'];
}

//daten speichern
$msg="";
if (isset($_POST['submit'])){
    $firma=htmlspecialchars($_POST['firma']);
    $person=htmlspecialchars($_POST['person']);
    $strasse=htmlspecialchars($_POST['strasse']);
    $plz=htmlspecialchars($_POST['plz']);
    $ort=htmlspecialchars($_POST['ort']);
    $email=htmlspecialchars($_POST['email']);
    $ust_idnr=htmlspecialchars($_POST['ust_idnr']);
    $land_code=htmlspecialchars($_POST['land_code']);
   
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO kunden (firma, person, strasse, plz, ort, email, ust_idnr, land_code) VALUES (?,?,?,?,?,?,?,?)")) {
            $stmt2 -> bind_param("ssssssss",$firma,$person,$strasse,$plz,$ort,$email,$ust_idnr,$land_code);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE kunden SET firma = ?, person = ?, strasse = ?, plz = ?, ort = ?, email = ?, ust_idnr = ?, land_code = ? WHERE kunde_id = ?")) {
            $stmt2 -> bind_param("ssssssssi",$firma,$person,$strasse,$plz,$ort,$email,$ust_idnr,$land_code,$id_kunde);
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
$firma=$person=$strasse=$plz=$ort=$email=$ust_idnr=$land_code="";
if ($action=="e" && $id_kunde!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT firma, person, strasse, plz, ort, email, ust_idnr, land_code FROM kunden WHERE kunde_id = ?")) {
        $stmt2 -> bind_param('i',$id_kunde);
        $stmt2 -> execute();
        $stmt2 -> bind_result($firma,$person,$strasse,$plz,$ort,$email,$ust_idnr,$land_code);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
}
?>
<h1>Kundenstamm</h1>
<h2>Kunde bearbeiten</h2>
<?=$msg;?>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_kunde;?>">
    <label for="firma">Firma</label>
    <input type="text" name="firma" data-clear-btn="true" maxlength="150" value="<?=$firma;?>">
    <label for="person">Person</label>
    <input type="text" name="person" data-clear-btn="true" maxlength="250" value="<?=$person;?>">
    <label for="strasse">Strasse, Hausnr</label>
    <input type="text" name="strasse" data-clear-btn="true" maxlength="250" value="<?=$strasse;?>">
    <label for="plz">PLZ</label>
    <input type="text" name="plz" data-clear-btn="true" maxlength="10" value="<?=$plz;?>">
    <label for="ort">Ort</label>
    <input type="text" name="ort" data-clear-btn="true" maxlength="150" value="<?=$ort;?>">
    <label for="email">E-Mail</label>
    <input type="text" name="email" data-clear-btn="true" maxlength="250" value="<?=$email;?>">
    <label for="ust_idnr">UST-IDNr</label>
    <input type="text" name="ust_idnr" data-clear-btn="true" maxlength="50" value="<?=$ust_idnr;?>">
    <label for="land_code">Land</label>
    <select name="land_code">
        <option value="">---</option>
        <?php
        if ($stmt2 = $mysqli -> prepare("SELECT Code, `Name` FROM country ORDER BY `Name`")) {
            $stmt2 -> execute();
            $stmt2 -> bind_result($code, $land);
            while ($stmt2 -> fetch()){
                if ($code==$land_code){
                    ?>
                    <option value="<?=$code;?>" selected><?=$land;?></option>
                    <?php
                } else {
                    ?>
                    <option value="<?=$code;?>"><?=$land;?></option>
                    <?php
                }
            }
            $stmt2 -> close();
        }
        ?>
    </select>
    <input type="submit" name="submit" value="Speichern">
</form>
<?php
$mysqli -> close();
?>
</body>
</html>