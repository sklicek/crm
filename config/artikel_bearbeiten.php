<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Mein CRM">
<meta name="robots" content="index,follow">
<title>Firma: Mein CRM - Artikel</title>
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

$id_art=0;
if (isset($_GET['id'])){
    $id_art=$_GET['id'];
} elseif (isset($_POST['id'])){
    $id_art=$_POST['id'];
}

//daten speichern
$msg="";
if (isset($_POST['submit'])){
	$bez=htmlspecialchars($_POST['bez']);
    $id_einheit=htmlspecialchars($_POST['id_einheit']);
    $vkpreis=htmlspecialchars($_POST['vkpreis']);
	
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO artikel (bezeichnung,id_einheit,vkpreis_einheit) VALUES (?,?,?)")) {
            $stmt2 -> bind_param("sid",$bez,$id_einheit,$vkpreis);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE artikel SET bezeichnung = ?, id_einheit = ?, vkpreis_einheit = ? WHERE id_art = ?")) {
            $stmt2 -> bind_param("sidi",$bez,$id_einheit,$vkpreis,$id_art);
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
    window.location.href="artikel.php";
    </script>
    <?php
    exit;
}

//daten auslesen
$bez="";
$id_einheit=$vkpreis=0;
if ($action=="e" && $id_art!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT bezeichnung, id_einheit, vkpreis_einheit FROM artikel WHERE id_art = ?")) {
        $stmt2 -> bind_param('i',$id_art);
        $stmt2 -> execute();
        $stmt2 -> bind_result($bez,$id_einheit,$vkpreis);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
}
?>
<div class="table">
<p class="header">Artikel bearbeiten</p>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_art;?>">
    <table>
	<tbody>
	<tr>
	<td><b for ="bez">Artikel-Bezeichnung</b></td>
    <td><input type="text" name="bez" maxlength="250" value="<?=$bez;?>"></td>
	</tr>
	<tr>
	<td><b for ="einheit">Einheit</b></td>
    <td>
	<select name="id_einheit" required>
		<option value="">---</option>
		<?php
		if ($stmt2 = $mysqli -> prepare("SELECT id_einheit, einheit FROM einheiten ORDER BY id_einheit")) {
			$stmt2 -> execute();
			$stmt2 -> bind_result($id, $einheit);
			while ($stmt2 -> fetch()){
				if ($id==$id_einheit){
					?>
					<option selected value="<?=$id;?>"><?=$einheit;?></option>
					<?php
				} else {
					?>
					<option value="<?=$id;?>"><?=$einheit;?></option>
					<?php
				}
			}
		}
		?>
	</select>
	</td>
	</tr>
	<tr>
	<td><b for ="bez">VK-Preis/Einheit</b></td>
    <td><input type="number" name="vkpreis" step="0.01" value="<?=$vkpreis;?>"></td>
	</tr>
	<tr>
	<td><input class="btn" type="submit" name="submit" value="Speichern"></td>
	<td></td>
	</tr>
	</tbody>
	</table>
</form>
</div>
<?php
$mysqli -> close();
?>
</body>
</html>