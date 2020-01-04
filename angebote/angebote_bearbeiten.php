<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Steve Klicek" >
<meta name="description" content="Rechnungen">
<meta name="robots" content="index,follow">
<title>Angebote</title>
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

$id_angebot=0;
if (isset($_GET['id'])){
    $id_angebot=$_GET['id'];
} elseif (isset($_POST['id'])){
    $id_angebot=$_POST['id'];
}

//daten speichern
$msg="";
if (isset($_POST['submit'])){
    $nr=htmlspecialchars($_POST['nr']);
    $dat=htmlspecialchars($_POST['dat']);
    $kunde_id=htmlspecialchars($_POST['kunde']);
    $new_id_angebot=0; 
    if ($action=="n"){
        if ($stmt2 = $mysqli -> prepare("INSERT INTO angebote (nr_angebot, datum_angebot, id_kunde) VALUES (?,?,?)")) {
            $stmt2 -> bind_param("ssi",$nr,$dat,$kunde_id);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
		
		if ($stmt2 = $mysqli -> prepare("SELECT id_angebot FROM angebote ORDER BY id_angebot DESC LIMIT 1")) {
			$stmt2 -> execute();
			$stmt2 -> bind_result($new_id_angebot);
			$stmt2 -> fetch();
			$stmt2 -> close();
        }
    } elseif ($action=="e"){
        if ($stmt2 = $mysqli -> prepare("UPDATE angebote SET nr_angebot = ?, datum_angebot = ?, id_kunde = ? WHERE id_angebot = ?")) {
            $stmt2 -> bind_param("ssii",$nr,$dat,$kunde_id,$id_angebot);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
			$new_id_angebot=$id_angebot;
        }
    }
	?>
    <script>
	alert('<?=$msg;?>');
	window.location.href="angebote_artikel_bearbeiten.php?id=<?=$new_id_angebot;?>";
    </script>
    <?php
    exit;
}

//daten auslesen
$nr=$dat=$kunde_id="";
if ($action=="e" && $id_angebot!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT nr_angebot, datum_angebot, id_kunde FROM angebote WHERE id_angebot = ?")) {
        $stmt2 -> bind_param('i',$id_angebot);
        $stmt2 -> execute();
        $stmt2 -> bind_result($nr,$dat,$kunde_id);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
    if ($dat!="") $dat=date("Y-m-d",strtotime($dat));
}
if ($dat=="") $dat=date("Y-m-d",strtotime('now'));

//artikel holen
$arr_artikel=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT a.id_art, a.bezeichnung, a.vkpreis_einheit, b.einheit FROM artikel as a left join einheiten as b on a.id_einheit=b.id_einheit ORDER BY a.id_art")) {
    $stmt2 -> execute();
    $stmt2 -> bind_result($id_art,$bez,$vkpreis,$einheit);
    while ($stmt2 -> fetch()){
		$arr_artikel[$a][0]=$id_art;
		$arr_artikel[$a][1]=$bez;
		$arr_artikel[$a][2]=$vkpreis;
		$arr_artikel[$a][3]=$einheit;
		$a++;
	}
    $stmt2 -> close();
}

//gespeicherte artikel in angebot holen
$arr_artikel_angebot=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT id_artikel, anzahl, id_rel FROM rel_artikel_angebot WHERE id_angebot = ?")) {
	$stmt2 -> bind_param("i",$id_angebot);
    $stmt2 -> execute();
    $stmt2 -> bind_result($id_art,$anz,$id_rel);
    while ($stmt2 -> fetch()){
		$arr_artikel_angebot[$a][0]=$id_art;
		$arr_artikel_angebot[$a][1]=$anz;
		$arr_artikel_angebot[$a][2]=$id_rel;
		$a++;
	}
    $stmt2 -> close();
}

?>
<div class="table">
<p class="header">Angebot bearbeiten</p>
<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
    <input type="hidden" name="action" value="<?=$action;?>">
    <input type="hidden" name="id" value="<?=$id_angebot;?>">
	<table>
	<tbody>
    <tr>
	<td><b for="nr">Angebot-Nr</b></td>
    <td><input type="text" name="nr" maxlength="25" value="<?=$nr;?>"></td>
	</tr>
	<tr>
    <td><b for="dat">Angebot-Datum</b></td>
    <td><input type="date" name="dat" maxlength="15" value="<?=$dat;?>"></td>
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
		<td><input class="btn" type="submit" name="submit" value="Speichern"></td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
	</table>
</form>
</div>

<div class="table">
<table>
<thead>
	<tr>
	<th colspan="4" style="background-color:lightgrey">Artikel im Angebot</th>
	<th>
	<?php
if ($id_angebot!=0){
	?>
	<a class="btn" href="angebote_artikel_bearbeiten.php?id=<?=$id_angebot;?>">Artikel bearbeiten</a>
	<?php
}
?>
	</th>
	</tr>
	<tr>
	<th>Anzahl</th>
	<th>Artikel-Bezeichnung</th>
	<th>Preis/Einheit</th>
	<th>Einheit</th>
	<th>VK-Preis</th>
	</tr>
</thead>
<tbody>
	<?php
	$summe_ang=0;
	for ($a=0;$a<count($arr_artikel_angebot);$a++){
		$id_art=$arr_artikel_angebot[$a][0];
		$anz=$arr_artikel_angebot[$a][1];
		$id_rel=$arr_artikel_angebot[$a][2];
		$bez="";
		$einheit="";
		$einzelpreis=0;
		for ($x=0;$x<count($arr_artikel);$x++){
			if ($arr_artikel[$x][0]==$id_art){
				$bez=$arr_artikel[$x][1];
				$einzelpreis=$arr_artikel[$x][2];
				$einheit=$arr_artikel[$x][3];
				break;
			}
		}
		$preis=$anz*$einzelpreis;
		$summe_ang+=$preis;
		?>
		<tr>
			<td><?=$anz;?></td>
			<td><?=$bez;?></td>
			<td><?=$einzelpreis;?>&euro;</td>
			<td><?=$einheit;?></td>
			<td><?=number_format($preis,2);?>&euro;</td>
		</tr>
		<?php
	}
	?>
	<tr style="background-color:lightgrey">
		<td><b>Summe Angebot</b></td>
		<td></td>
		<td></td>
		<td></td>
		<td><b><u><?=number_format($summe_ang,2);?>&euro;</u></b></td>
	</tr>
	<?php
	if ($id_angebot!=0){
	?>
	<tr>
		<td><a class="btn" href="../word_export/angebot.php?id=<?=$id_angebot;?>" target="_blank">Exportieren (Word)</a></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php
	}
	?>
</tbody>
</table>
</div>
<?php
$mysqli -> close();
?>
</body>
</html>