<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Einheiten zu Artikel</title>
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

$id_einheit=0;
if (isset($_GET['id'])){
    $id_einheit=$_GET['id'];
} elseif (isset($_POST['id'])){
    $id_einheit=$_POST['id'];
}

//daten speichern
$msg="";
if (isset($_POST['submit'])){
    $einheit=htmlspecialchars($_POST['einheit']);
    
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO einheiten (einheit) VALUES (?)")) {
            $stmt2 -> bind_param("s",$einheit);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE einheiten SET einheit = ? WHERE id_einheit = ?")) {
            $stmt2 -> bind_param("si",$einheit,$id_einheit);
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
	alert('<?=$msg;?>');
    window.location.href="einheiten.php";
    </script>
    <?php
    exit;
}

//daten auslesen
$einheit="";
if ($action=="e" && $id_einheit!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT einheit FROM einheiten WHERE id_einheit = ?")) {
        $stmt2 -> bind_param('i',$id_einheit);
        $stmt2 -> execute();
        $stmt2 -> bind_result($einheit);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
}
?>
<div class="table">
<p class="header">Einheit bearbeiten</p>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_einheit;?>">
    <table>
	<tbody>
	<tr>
	<td><b for ="firma">Einheit</b></td>
    <td><input type="text" name="einheit" maxlength="15" value="<?=$einheit;?>"></td>
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