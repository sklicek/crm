<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Lieferanten</title>
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
    $typ='L';
    
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO kunden (firma, person, strasse, plz, ort, email, ust_idnr, land_code, typ) VALUES (?,?,?,?,?,?,?,?,?)")) {
            $stmt2 -> bind_param("sssssssss",$firma,$person,$strasse,$plz,$ort,$email,$ust_idnr,$land_code,$typ);
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
<div class="table">
<p class="header">Lieferanten bearbeiten</p>
<?=$msg;?>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_kunde;?>">
    <table>
	<tbody>
	<tr>
	<td><b for ="firma">Firma</b></td>
    <td><input type="text" name="firma" maxlength="150" value="<?=$firma;?>"></td>
	</tr>
	<tr>
    <td><b for="person">Person</b></td>
    <td><input type="text" name="person" maxlength="250" value="<?=$person;?>"></td>
	</tr>
	<tr>
	<td><b for="strasse">Strasse, Hausnr</b></td>
    <td><input type="text" name="strasse" maxlength="250" value="<?=$strasse;?>"></td>
	</tr>
    <tr>
	<td><b for="plz">PLZ</b></td>
    <td><input type="text" name="plz" maxlength="10" value="<?=$plz;?>"></td>
	</tr>
	<tr>
    <td><b for="ort">Ort</b></td>
    <td><input type="text" name="ort" maxlength="150" value="<?=$ort;?>"></td>
	</tr>
	<tr>
    <td><b for="email">E-Mail</b></td>
    <td><input type="text" name="email" maxlength="250" value="<?=$email;?>"></td>
	</tr>
	<tr>
    <td><b for="ust_idnr">UST-IDNr</b></td>
    <td><input type="text" name="ust_idnr" maxlength="50" value="<?=$ust_idnr;?>"></td>
	</tr>
	<tr>
    <td><b for="land_code">Land</b></td>
    <td><select name="land_code">
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
    </select></td>
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