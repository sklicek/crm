	<?php
	//**********************************
	//angebot nach word exportieren
	//**********************************
	@require_once("../include/config.inc.php");
	include_once('tbs_class.php');
	include_once('tbs_plugin_opentbs.php');

	$id_angebot="";
	if (isset($_GET['id'])){
		$id_angebot=$_GET['id'];
	}

	//Kundenadresse und Angebot-Daten holen
	$nr_angebot="";
	$datum_angebot=date("d.m.Y",strtotime('now'));
	$id_kunde="";
	if ($stmt2 = $mysqli -> prepare("SELECT nr_angebot, datum_angebot, id_kunde FROM angebote WHERE id_angebot = ?")) {
		$stmt2 -> bind_param('i',$id_angebot);
		$stmt2 -> execute();
		$stmt2 -> bind_result($nr_angebot, $datum_angebot, $id_kunde);
		$stmt2 -> fetch();
		$stmt2 -> close();
	}
	if ($datum_angebot!=""){
		$datum_angebot=date("d.m.Y",strtotime($datum_angebot));
	}
	
	$firma="";
	$adresse="";
	$plz="";
	$ort="";
	if ($stmt2 = $mysqli -> prepare("SELECT firma, strasse, plz, ort FROM kunden WHERE kunde_id = ?")) {
		$stmt2 -> bind_param('i',$id_kunde);
		$stmt2 -> execute();
		$stmt2 -> bind_result($firma, $adresse, $plz, $ort);
		$stmt2 -> fetch();
		$stmt2 -> close();
	}
	
	//unterzeichner
	$firma_ort="";
	$firma_kontaktperson="";
	if ($stmt2 = $mysqli -> prepare("SELECT ort, person FROM kunden WHERE typ='F' LIMIT 1")) {
		$stmt2 -> execute();
		$stmt2 -> bind_result($firma_ort, $firma_kontaktperson);
		$stmt2 -> fetch();
		$stmt2 -> close();
	}

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
	
	//Angebot-Daten holen und zusammensetzen zur Tabelle in Word
	$arr_dest_word=array();
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
		
		$arr=array('anz'=>$anz,'bez'=>$bez,'einzelpreis'=>$einzelpreis,'einheit'=>$einheit,'preis'=>$preis);
		$arr_dest_word[]=$arr;
	}
	
	$mysqli -> close();
	
	//TBS-Instanz initialisieren und Word-Dokument erstellen
	$TBS = new clsTinyButStrong;
	$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
	$template = '../word_vorlagen/angebot.odt';
	$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document). 
	
	//Angebot-Daten in Word-Tabelle einlesen
	$TBS->MergeBlock('b', $arr_dest_word);
	
	//Als download anbieten
	$output_file_name="angebot_exported.odt";
	$TBS->Show(OPENTBS_FILE, $output_file_name);
	
	 // Process download
        if(file_exists($output_file_name)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($output_file_name).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($output_file_name));
            flush(); // Flush system output buffer
            readfile($output_file_name);
            die();
        } else {
            http_response_code(404);
	        die();
        }
	?>
