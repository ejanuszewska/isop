<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
include '../lib/firma.php';
include '../lib/ogloszenie.php';



$user = User::getById($_SESSION['userId']);



ob_start();


$sql=sprintf('select ogloszenie.id_ogloszenia from ogloszenie LEFT JOIN ogloszenie_kandydaci ON ogloszenie.id_ogloszenia = ogloszenie_kandydaci.id_ogloszenia WHERE ogloszenie_kandydaci.USER_ID=%d', $user->userId);
$query=mysql_query($sql);

$num=mysql_num_rows($query);
    $GLOBALS['TEMPLATE']['content'].= '<div class="container">';
if ($num==0){
    $GLOBALS['TEMPLATE']['content'].= '<div class="col-sm-6 col-xs-12">Nie złożyłeś żadnej aplikacji.</div>';
}
else{
while ($row = mysql_fetch_array($query)) {
$ogl = Ogloszenie::getById($row[id_ogloszenia]);
$idOgl=$row[id_ogloszenia];
$r = $ogl->wyswietl(true, 1);

$GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6 col-xs-12"><table class="table">';
$GLOBALS['TEMPLATE']['content'].=$r[1];
$GLOBALS['TEMPLATE']['content'].='</table></div></div>';

$sql2=sprintf('SELECT * from etap_kandydat LEFT JOIN etap_rekrutacji ON etap_kandydat.id_etapu=etap_rekrutacji.id_etapu LEFT JOIN etap_opis ON etap_opis.id_etapu=etap_kandydat.id_etapu WHERE etap_kandydat.id_ogloszenia=%d AND etap_kandydat.USER_ID=%d AND etap_opis.id_ogloszenia=%d', $idOgl, $user->userId, $idOgl );
$query2=mysql_query($sql2);
$num2=mysql_num_rows($query2);
if ($num2>0){
while ($row2= mysql_fetch_array($query2)) {
$etap=$row2[nazwa_etapu];
$opis=$row2[opis];

$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-sm-6 col-xs-12">Etap na którym jestes: ';
$GLOBALS['TEMPLATE']['content'] .= $etap;
$GLOBALS['TEMPLATE']['content'] .= '</br>Opis:';
$GLOBALS['TEMPLATE']['content'] .= $opis;
$GLOBALS['TEMPLATE']['content'] .= '</div></div><div class="col-xs-12" style="height:50px;"></div>';

}

}
else{
    $GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-sm-6 col-xs-12">Nie dodano Cię jeszcze do żadnego etapu rekrutacji.</div></div><div class="col-xs-12" style="height:50px;"></div>';
}
}

}

    $GLOBALS['TEMPLATE']['content'] .= '</div>';
        


            mysql_query($query, $GLOBALS['DB']);
		
		


include '../templates/template-page.php';
?>



