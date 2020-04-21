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

//daten auslesen
$msg="";
$nr=$dat=$kunde_id="";
if ($id_angebot!=0){
    if ($stmt2 = $mysqli -> prepare("SELECT a.nr_angebot, a.datum_angebot, b.firma FROM angebote as a left join kunden as b on a.id_kunde=b.kunde_id WHERE a.id_angebot = ?")) {
        $stmt2 -> bind_param('i',$id_angebot);
        $stmt2 -> execute();
        $stmt2 -> bind_result($nr,$dat,$kunde_id);
        $stmt2 -> fetch();
        $stmt2 -> close();
    }
    if ($dat!="") $dat=date("d.m.Y",strtotime($dat));
}

//artikel entfernen vom angebot
if ($action=="d" && $id_angebot!=0 && isset($_GET['id_rel'])){
	$id_rel=$_GET['id_rel'];
	if ($stmt2 = $mysqli -> prepare("DELETE FROM angebote_artikel WHERE id_art = ? and id_angebot = ?")) {
        $stmt2 -> bind_param("ii",$id_rel,$id_angebot);
        if ($stmt2 -> execute()){
            $msg="Daten erfolgreich entfernt.";
        } else {
            $msg="Fehler bei der Entfernung der Daten.";
        }
        $stmt2 -> close();
    }
	?>
	<script>
	alert('<?=$msg;?>');
	</script>
	<?php
}

//daten speichern
if (isset($_POST['submit'])){
   $anzahl=$_POST['anzahl'];
   $bezeichnung=htmlspecialchars($_POST['bezeichnung']);
   $id_einheit=$_POST['id_einheit'];
   $vkpreis_einheit=$_POST['vkpreis_einheit'];
   $msg="TEST";
	//if ($action==""){
		if ($stmt2 = $mysqli -> prepare("INSERT INTO angebote_artikel (anzahl,bezeichnung,id_einheit, vkpreis_einheit,id_angebot) VALUES (?,?,?,?,?)")) {
            $stmt2 -> bind_param("dsidi",$anzahl,$bezeichnung,$id_einheit,$vkpreis_einheit,$id_angebot);
            if ($stmt2 -> execute()){
                $msg="Daten erfolgreich gespeichert.";
            } else {
                $msg="Fehler bei der Speicherung der Daten.";
            }
            $stmt2 -> close();
        }
	//}
	?>
	<script>
	alert('<?=$msg;?>');
	</script>
	<?php
}

//einheiten holen
$arr_einheiten=array();
$a=0;
if ($stmt2 = $mysqli -> prepare("SELECT id_einheit, einheit FROM einheiten ORDER BY einheit")) {
    $stmt2 -> execute();
    $stmt2 -> bind_result($id_einheit,$einheit);
    while ($stmt2 -> fetch()){
    	$arr_einheiten[$a][0]=$id_einheit;
    	$arr_einheiten[$a][1]=$einheit;
    	$a++;
    }
    $stmt2->close();
}

//artikel holen
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
?>
<div class="table">
<p class="header">Angebot bearbeiten
	<a href="angebote_bearbeiten.php?id=<?=$id_angebot;?>&action=e" class="btn">zum Angebot</a>
</p>
<table>
<tbody>
    <tr>
	<td><b for="nr">Angebot-Nr</b></td>
    <td><?=$nr;?></td>
	</tr>
	<tr>
    <td><b for="dat">Angebot-Datum</b></td>
    <td><?=$dat;?></td>
	</tr>
	<tr>
    <td><b for="kunde">Kunde</b></td>
    <td><?=$kunde_id;?></td>
	</tr>
</tbody>
</table>

<table>
<thead>
	<tr>
	<th colspan="5" style="background-color:lightgrey">Artikel im Angebot</th>
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
	<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="action" value="<?=$action;?>">
		<input type="hidden" name="id" value="<?=$id_angebot;?>">
		<tr>
		<td><input name="anzahl" type="number" step="0.01" required></td>
		<td><input name="bezeichnung" type="text" maxlength="250"></td>
		<td><input name="vkpreis_einheit" type="number" step="0.01" required></td>
		<td>
			<select name="id_einheit" required>
				<option value="">--- Bitte wählen ---</option>
				<?php
				for ($a=0;$a<count($arr_einheiten);$a++){
					?>
					<option value="<?=$arr_einheiten[$a][0];?>"><?=$arr_einheiten[$a][1];?></option>
					<?php
				}
				?>
			</select>
		</td>
		<td><input class="btn" type="submit" name="submit" value="Hinzufügen"></td>
		<td></td>
		<td></td>
		</tr>
	</form>
	<?php
	$summe_ang=0;
	for ($x=0;$x<count($arr_artikel);$x++){
		$id_rel=$arr_artikel[$x][0];
		$bez=$arr_artikel[$x][1];
		$einzelpreis=$arr_artikel[$x][2];
		$einheit=$arr_artikel[$x][3];
		$anz=$arr_artikel[$x][4];
		$preis=$anz*$einzelpreis;
		$summe_ang+=$preis;
		?>
		<tr>
			<td><?=$anz;?></td>
			<td><?=$bez;?></td>
			<td><?=$einzelpreis;?>&euro;</td>
			<td><?=$einheit;?></td>
			<td>
				<?=number_format($preis,2);?>&euro;
				<a href="angebote_artikel_bearbeiten.php?id=<?=$id_angebot;?>&id_rel=<?=$id_rel;?>&action=d" onclick="return confirm('Diesen Artikel entfernen?');" class="btn">Löschen</a>
			</td>
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
</tbody>
</table>
</div>
<?php
$mysqli -> close();
?>
</body>
</html>