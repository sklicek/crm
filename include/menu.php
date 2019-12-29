<?php
session_start();
?>
<ul class="topnav">
<li><a href="<?=$_SESSION['root_path'];?>/index.php">Hauptseite</a></li>
<li><a href="<?=$_SESSION['root_path'];?>/kunden/kunden.php">Kunden</a></li>
<li><a href="<?=$_SESSION['root_path'];?>/lieferanten/lieferanten.php">Lieferanten</a></li>
<li><a href="<?=$_SESSION['root_path'];?>/rechnungen/rechnungen.php">Ausgangsrechnungen</a></li>
<li><a href="<?=$_SESSION['root_path'];?>/rechnungen_eingang/rechnungen.php">Eingangsrechnungen</a></li>
<li><a href="<?=$_SESSION['root_path'];?>/kontenrahmen/kontenrahmen.php">Kontenrahmen</a></li>
<li class="dropdown">
  <a href="javascript:void(0)" class="dropbtn">Auswertungen</a>
  <div class="dropdown-content">
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/eur.php">EÜR</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_view.php">Ausgangsrechnungen</a>
      <a href="<?=$_SESSION['root_path'];?>/auswertungen/rechnungen_eingang_view.php">Eingangsrechnungen</a>
  </div>
</li>
</ul>