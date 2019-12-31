<?php
session_start();
?>
<ul class="topnav">
<li><a href="<?=$_SESSION['root_path'];?>/index.php">Hauptseite</a></li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Kontakte</a>
  <div class="dropdown-content">
    <a href="<?=$_SESSION['root_path'];?>/kunden/kunden.php?typ=K">Kunden</a>
    <a href="<?=$_SESSION['root_path'];?>/kunden/kunden.php?typ=L">Lieferanten</a>
  </div>
</li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Angebote</a>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/config/einheiten.php">Einheiten konfig.</a>
	  <a href="<?=$_SESSION['root_path'];?>/config/artikel.php">Artikel konfig.</a>
	  <a href="<?=$_SESSION['root_path'];?>/angebote/angebote.php">Angebote</a>
  </div>
</li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Rechnungen</a>
  <div class="dropdown-content">
	<a href="<?=$_SESSION['root_path'];?>/rechnungen/rechnungen.php">Ausgangsrechnungen</a>
	<a href="<?=$_SESSION['root_path'];?>/rechnungen_eingang/rechnungen.php">Eingangsrechnungen</a>
  </div>
</li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Auswertungen</a>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/eur.php">EÜR</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_view.php">Ausgangsrechnungen</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_eingang_view.php">Eingangsrechnungen</a>
  </div>
</li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Konfiguration</a>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/config/unternehmen.php">Unternehmen</a>
	  <a href="<?=$_SESSION['root_path'];?>/kontenrahmen/kontenrahmen.php">Kontenrahmen</a>
  </div>
</li>
</ul>