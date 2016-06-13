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



$idOgl = $_GET['gid'];
$idEtapu = $_GET['id'];
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
    $GLOBALS['TEMPLATE']['content'] .= '<li class="active"><a href="dodaj_kan.php?id='.$idEtapu.'&gid='.$idOgl.'">Etap - '.$nazwa_etapu.'</a></li>';
$GLOBALS['TEMPLATE']['content'] .= '</ol></div></div>';
$l=0;
$sql=sprintf('select USER_ID from ogloszenie_kandydaci WHERE USER_ID NOT IN (SELECT USER_ID FROM etap_kandydat WHERE etap_kandydat.id_ogloszenia=%d) AND USER_ID NOT IN (SELECT USER_ID FROM rekrutacja_odrzucona WHERE rekrutacja_odrzucona.id_ogloszenia=%d) AND ogloszenie_kandydaci.id_ogloszenia=%d',  $idOgl, $idOgl,$idOgl);
$query=mysql_query($sql);

$num=mysql_num_rows($query);
$GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6 col-xs-12"><h3><span class="label label-primary">Dodaj kandydatów do etapu</span></h3>';
    
if ($num==0){
    $GLOBALS['TEMPLATE']['content'].= "Brak kandydatów do dodania</div></div>";
}
else{
$GLOBALS['TEMPLATE']['content'] .= "<form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get'>";





    $GLOBALS['TEMPLATE']['content'].= "<table class='table'> <thead><tr><th>Dodaj</th><th>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Data urodzenia</th><th>Profil</th></tr></thead>";

while ($row = mysql_fetch_array($query)) {
$l++;
$kandydat = User::wyswietl($row['USER_ID'], $idOgl, $idEtapu);

$GLOBALS['TEMPLATE']['content'].= "<tbody><tr><td><input type='checkbox' name='idk[]' value='".$row['USER_ID']."'/></td><td>$l</td>";
    
    
$GLOBALS['TEMPLATE']['content'] .= $kandydat;

}


}
if($num!=0){
   $GLOBALS['TEMPLATE']['content'].= '<tr><td><input type="submit" class="btn btn-default" value="Dodaj"/></td>';
}
   $GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6 col-xs-12"><td><input type="hidden" name="submitted" value="1"/></td>';
   $GLOBALS['TEMPLATE']['content'].= '<td><input type="hidden" name="gid" value="' . $idOgl . '"/></td>';
   $GLOBALS['TEMPLATE']['content'].= '<td><input type="hidden" name="id" value="' . $idEtapu . '"/></td></tr></tbody></table></form></div></div>';
  
 $sql=sprintf('select USER_ID from etap_kandydat  WHERE etap_kandydat.id_ogloszenia=%d AND id_etapu=%d', $idOgl, $idEtapu);

$query=mysql_query($sql);
$num=mysql_num_rows($query);
$GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6"><h3><span class="label label-primary">Usuń kandydatów z tego etapu</span></h3>';

if ($num==0){
    $GLOBALS['TEMPLATE']['content'].= "Brak kandydatów do usunięcia</div></div>";
}
else{  
$GLOBALS['TEMPLATE']['content'] .= "<form action=" . htmlspecialchars($_SERVER['PHP_SELF']) . " method='get'>";


    $GLOBALS['TEMPLATE']['content'].= "<table class='table'><thead><tr><th>Usuń</th><th>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Data urodzenia</th><th>Profil</th></tr></thead>";

while ($row = mysql_fetch_array($query)) {
$l++;
$kandydat = User::wyswietl($row['USER_ID'], $idOgl, $idEtapu);

$GLOBALS['TEMPLATE']['content'].= "<tr><td><input type='checkbox' name='idk[]' value='". $row['USER_ID']. "'/></td><td>$l</td>";
    
    
$GLOBALS['TEMPLATE']['content'] .= $kandydat;

}


}
if($num!=0){
   $GLOBALS['TEMPLATE']['content'].= '<tr><td><input type="submit"  class="btn btn-default" value="Usuń"/></td>';
}
   $GLOBALS['TEMPLATE']['content'].= '<td><input type="hidden" name="submittedusun" value="1"/></td>';
   $GLOBALS['TEMPLATE']['content'].= '<td><input type="hidden" name="gid" value="' . $idOgl . '"/></td>';
   $GLOBALS['TEMPLATE']['content'].= '<td><input type="hidden" name="id" value="' . $idEtapu . '"/></td></tr></table></form></div></div>';
if (isset($_GET['submitted']))
{
    
    $ogl=$_GET[gid];
    $etap=$_GET[id];
  

  
 $idus = $_GET[idk];
 echo $idus;
 echo $ogl;
        foreach($idus as $idk){
    $sql1 = sprintf('INSERT INTO etap_kandydat (id_etapu, id_ogloszenia, USER_ID) VALUES (%d, %d, %d)',  $etap,$ogl, $idk);
    $query3=mysql_query($sql1);
    echo 'idus';
    echo $idk;
        }
   header('Location: dodaj_kan.php?id='.$etap.'&gid='.$ogl.'');
  }
  
  if (isset($_GET['submittedusun']))
{
    
    $ogl=$_GET[gid];
    $etap=$_GET[id];
 


 $idus = $_GET[idk];
        foreach($idus as $idkd){
    $sql1 = sprintf('DELETE from etap_kandydat WHERE id_etapu=%d AND id_ogloszenia=%d AND USER_ID=%d',  $etap,$ogl, $idkd);
    $query3=mysql_query($sql1);
    echo $sql1;
 
        }
  header('Location: dodaj_kan.php?id='.$etap.'&gid='.$ogl.'');
  }


	
include '../templates/template-page.php';
?>

