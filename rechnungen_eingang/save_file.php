<?php
$id_rechnung=0;
if(isset($_POST["id_rechnung"])){
	$id_rechnung=$_POST["id_rechnung"];
}

//file upload
$target_dir = "../uploads/rechnungen_eingang/";
if (!file_exists($target_dir)){
    mkdir('../uploads');
	mkdir($target_dir);
}

if(isset($_POST['submit']) && isset($_FILES['file'])){
	$target_file = $target_dir.$_FILES['file']['name'];
	//var_dump($target_file);
	
	$msg = "";
	$file_ok = 1;
	// Check if file already exists
	//if (file_exists($target_file)) {
	//	$msg = "Sorry, file already exists.";
	//	$file_ok = 0;
	//}
	
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	//var_dump($fileType);
		
	if ($fileType=="pdf"){
	} else {
		$msg = "Sorry, your file is not PDF.";
		$file_ok = 0;
	}
		
	//all checks are OK
	if ($file_ok == 1){
		if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
			$msg = "The file ". $_FILES['file']['name']. " has been uploaded.";
			
			//Datenbank updaten
			@require_once("../include/config.inc.php");
			if ($stmt2 = $mysqli -> prepare("UPDATE rechnungen_eingang SET pdffile = ? WHERE id_rechnung = ?")) {
				$stmt2 -> bind_param("si",$target_file,$id_rechnung);
				if ($stmt2 -> execute()){
					$msg="Daten erfolgreich gespeichert.";
				} else {
					$msg="Fehler bei der Speicherung der Daten.";
				}
				$stmt2 -> close();
			}
			$mysqli -> close();
		} else {
			$msg = "Sorry, there was an error uploading your file.";
		}
	}
	?>
	<script>
		alert('<?=$msg;?>');
		window.location.href="rechnungen.php";
	</script>	
	<?php
	exit;
}
?>