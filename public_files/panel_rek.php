<?php
session_start();
// doł±czenie kodu współużytkowanego
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
include '../lib/firma.php';
include '../lib/ogloszenie.php';

// doł±czenie pliku 401.php - użytkownik może ogl±dać stronę tylko po zalogowaniu się
//include '401.php';

// wygenerowanie formularza informacji o użytkowniku
$rekruter = Rekruter::getById($_SESSION['rId']);

$firma=$rekruter->id_firmy;



ob_start();

$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-md-6 col-xs-12"><ol class="breadcrumb">';
 $GLOBALS['TEMPLATE']['content'] .= '<li><a href="panel_rek.php">Panel rekrutera</a></li>';
$GLOBALS['TEMPLATE']['content'] .= '</ol></div></div>';


$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-md-6 col-xs-12">';
$GLOBALS['TEMPLATE']['content'] .= '<table class="table"><thead><tr><th>Rekrutacja</th><th>Nazwa ogłoszenia</th><th>Miasto</th><th>Województwo</th><th>Branża</th></tr></thead>';
$sql=sprintf('select id_ogloszenia from ogloszenie LEFT JOIN rekruter ON ogloszenie.id_firmy = rekruter.id_firmy WHERE rekruter.id_firmy=%d', $firma);
$query=mysql_query($sql);
while ($row = mysql_fetch_array($query)) {
$ogl = Ogloszenie::getById($row[id_ogloszenia]);
$r = $ogl->wyswietl();


$GLOBALS['TEMPLATE']['content'] .= $r[1];

}
$GLOBALS['TEMPLATE']['content'] .= '</table></div></div>';
   
        


            mysql_query($query, $GLOBALS['DB']);
		
		

     /*  $GLOBALS['TEMPLATE']['content'] = '<p><strong>Informacje ' .
            'w bazie danych zostały uaktualnione.</strong></p>';
    }
    // dane nieprawidłowe
    else
    {
        $GLOBALS['TEMPLATE']['content'] .= '<p><strong>Podano nieprawidłowe ' .
            'dane.</strong></p>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }*/
//}
// wy¶wietlenie strony
include '../templates/template-page.php';
?>



