<?php
function getKunden($typ,$mysqli) {
    $arr=array();
	$a=0;
	if ($stmt2 = $mysqli -> prepare("SELECT kunde_id, firma, person, strasse, plz, ort, land_code, typ FROM kunden WHERE typ=? ORDER BY kunde_id")) {
		$stmt2 -> bind_param('s',$typ);
		$stmt2 -> execute();
		$stmt2 -> bind_result($id, $firma, $name, $strasse, $plz, $ort, $land_code,$typ);
		while ($stmt2 -> fetch()){
			$arr[$a][0]=$id;
			$arr[$a][1]=$firma;
			$arr[$a][2]=$name;
			$arr[$a][3]=$strasse;
			$arr[$a][4]=$plz;
			$arr[$a][5]=$ort;
			$arr[$a][6]=$land_code;
			$arr[$a][7]=$typ;
			$a++;
		}
		$stmt2 -> close();
	}
    return $arr;
}

function getOffeneRechnungen($firma,$jahr,$mysqli) {
	$brutto_gesamt_offen=0;
    if ($stmt = $mysqli -> prepare("SELECT sum(bruttowert) FROM krechnungen_offen WHERE rechnung_datum = ? and firma = ? group by rechnung_datum, firma")) {
        $stmt -> bind_param('is',$jahr,$firma);
        $stmt -> execute();
        $stmt -> bind_result($brutto);
        if ($stmt -> fetch()){
			$brutto_gesamt_offen+=$brutto;
        }
        $stmt -> close();
    }
    return $brutto_gesamt_offen;
}

function getBezahlteRechnungen($firma,$jahr,$mysqli) {
    $brutto_gesamt_bezahlt=0;
    if ($stmt = $mysqli -> prepare("SELECT sum(bruttowert) FROM krechnungen WHERE rechnung_datum = ? and firma = ? group by rechnung_datum, firma")) {
		$stmt -> bind_param('is',$jahr,$firma);
        $stmt -> execute();
        $stmt -> bind_result($brutto);
        if ($stmt -> fetch()){
            $brutto_gesamt_bezahlt+=$brutto;
        }
        $stmt -> close();
    }
    return $brutto_gesamt_bezahlt;
}
?>
