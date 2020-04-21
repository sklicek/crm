<?php
session_start();
?>
<div class="topnav" id="myTopnav">
<a href="<?=$_SESSION['root_path'];?>/index.php">Hauptseite</a>
<div class="dropdown">
  <button class="dropbtn">Kontakte</button>
  <div class="dropdown-content">
    <a href="<?=$_SESSION['root_path'];?>/kunden/kunden.php?typ=K">Kunden</a>
    <a href="<?=$_SESSION['root_path'];?>/kunden/kunden.php?typ=L">Lieferanten</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Angebote</button>
  <div class="dropdown-content">
    <a href="<?=$_SESSION['root_path'];?>/config/einheiten.php">Einheiten konfig.</a>
	 <a href="<?=$_SESSION['root_path'];?>/angebote/angebote.php">Angebote</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Rechnungen</button>
  <div class="dropdown-content">
	  <a href="<?=$_SESSION['root_path'];?>/rechnungen/rechnungen.php">Ausgangsrechnungen</a>
	  <a href="<?=$_SESSION['root_path'];?>/rechnungen_eingang/rechnungen.php">Eingangsrechnungen</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Auswertungen</button>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/eur.php">EÜR</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/saldenliste.php">Saldenliste</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_view.php">Ausgangsrechnungen</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_eingang_view.php">Eingangsrechnungen</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Konfiguration</button>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/config/unternehmen.php">Unternehmen</a>
	    <a href="<?=$_SESSION['root_path'];?>/kontenrahmen/kontenrahmen.php">Kontenrahmen</a>
  </div>
</div>
<a style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div>