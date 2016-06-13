<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
include '../lib/firma.php';
include '../lib/ogloszenie.php';


ob_start();

$rekruter = rekruter::getById($_SESSION['rId']);

$firma=$rekruter->id_firmy;



$idKan = $_GET['id'];
$l=0;
$idOgl = $_GET['idOgl'];
$idEtapu = $_GET['idEtapu'];
$sql = sprintf("SELECT * from ogloszenie LEFT JOIN stanowisko ON ogloszenie.id_stanowiska=stanowisko.id_stanowiska WHERE ogloszenie.id_ogloszenia=%d", $idOgl);
$query=mysql_query($sql);
$row=mysql_fetch_assoc($query);
$stanowisko=$row[nazwa_stanowiska];
$sql = sprintf("SELECT * from etap_opis LEFT JOIN etap_rekrutacji ON etap_opis.id_etapu=etap_rekrutacji.id_etapu WHERE etap_opis.id_etapu=%d AND etap_opis.id_ogloszenia=%d", $idEtapu, $idOgl);
$query=mysql_query($sql);
$row=mysql_fetch_assoc($query);
$nazwa_etapu=$row[nazwa_etapu];
$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-md-6 col-xs-12"><ol class="breadcrumb">';
 $GLOBALS['TEMPLATE']['content'] .= '<li><a href="panel_rek.php">Panel rekrutera</a></li>';
  $GLOBALS['TEMPLATE']['content'] .= '<li><a href="rekrutacja.php?id='.$idOgl.'">Rekrutacja - '.$stanowisko.'</a></li>';
  if (!empty($idEtapu)){
    $GLOBALS['TEMPLATE']['content'] .= '<li class="active"><a href="dodaj_kan.php?id='.$idEtapu.'&gid='.$idOgl.'">Etap - '.$nazwa_etapu.'</a></li>';
  }
$GLOBALS['TEMPLATE']['content'] .= '</ol></div></div>';

$profil= User::WyswietlPelny($idKan);
$GLOBALS['TEMPLATE']['content'] .= $profil;




   

include '../templates/template-page.php';
?>



