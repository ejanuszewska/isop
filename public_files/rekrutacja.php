<!DOCTYPE html 
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
    <head>
        <script type='text/javascript' src='jquery-1.3.2.js'></script>
        <script type='text/javascript' src='jquery-1.8.0.min.js'></script>
        <script type='text/javascript' src='jquery-1.11.2.min.js'></script>
        <script src="../lib/jquery.validate.min.js" type="text/javascript" charset="utf-8"></script>

        <script>
            $(document).ready(function () {
                
            $('#nowyetap').validate({
    rules: {
   
     etap: {
        required: true,
        minlength: 2
    
    }
    },
    success: function(label) {
      label.text('OK!').addClass('valid');
    }
  });
  jQuery.extend(jQuery.validator.messages, {
    required: 'To pole jest wymagane',
    minlength: $.validator.format("Wprowadź co najmniej {0} znaków.")
  });

            });
            </script>
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
$idOgl = $_GET[id];
$sql = sprintf("SELECT * from ogloszenie LEFT JOIN stanowisko ON ogloszenie.id_stanowiska=stanowisko.id_stanowiska WHERE ogloszenie.id_ogloszenia=%d", $idOgl);
$query=mysql_query($sql);
$row=mysql_fetch_assoc($query);
$stanowisko=$row[nazwa_stanowiska];

$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-md-6 col-xs-12"><ol class="breadcrumb">';
 $GLOBALS['TEMPLATE']['content'] .= '<li><a href="panel_rek.php">Panel rekrutera</a></li>';
  $GLOBALS['TEMPLATE']['content'] .= '<li class="active"><a href="rekrutacja.php?id='.$idOgl.'">Rekrutacja - '.$stanowisko.'</a></li>';
$GLOBALS['TEMPLATE']['content'] .= '</ol></div></div>';
$l=0;

$GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6 col-xs-12"><h3><span class="label label-primary">Lista kandydatów</span></h3><table class="table" ><thead><tr><th>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Data urodzenia</th><th>Profil</th><th>Odrzuć aplikację</th></tr></thead>';
$sql=sprintf('select USER_ID from ogloszenie_kandydaci   LEFT JOIN ogloszenie ON ogloszenie_kandydaci.id_ogloszenia = ogloszenie.id_ogloszenia WHERE ogloszenie.id_ogloszenia=%d AND USER_ID NOT IN (SELECT USER_ID FROM rekrutacja_odrzucona WHERE rekrutacja_odrzucona.id_ogloszenia=%d)', $idOgl, $idOgl);
$query=mysql_query($sql);

while ($row = mysql_fetch_array($query)) {
$l++;
$kandydat = User::wyswietl($row[USER_ID], $idOgl);
$GLOBALS['TEMPLATE']['content'] .= '<tr><td>'.$l.'</td>';
$GLOBALS['TEMPLATE']['content'] .= $kandydat.'<td><a href="odrzuc.php?idOgl='.$idOgl.'&user='.$row[USER_ID].'">Odrzuć kandydata</a></td></tr>';

}
$GLOBALS['TEMPLATE']['content'] .= '</table></div></div>';
$GLOBALS['TEMPLATE']['content'].= '<div class="container"><div class="col-sm-6 col-xs-12"><h3><span class="label label-primary">Odrzuceni kandydaci</span></h3><table class="table" ><thead><tr><th>Lp.</th><th>Imię</th><th>Nazwisko</th><th>Data urodzenia</th><th>Profil</th><th>Przywróć kandydata</th></tr></thead>';
$sql=sprintf('select USER_ID FROM  rekrutacja_odrzucona WHERE rekrutacja_odrzucona.id_ogloszenia=%d', $idOgl);
$query=mysql_query($sql);

while ($row = mysql_fetch_array($query)) {
$l++;
$kandydat = User::wyswietl($row[USER_ID], $idOgl);
$GLOBALS['TEMPLATE']['content'] .= '<tr><td>'.$l.'</td>';
$GLOBALS['TEMPLATE']['content'] .= $kandydat.'<td><a href="przywroc.php?idOgl='.$idOgl.'&user='.$row[USER_ID].'">Przywróc</a></td>';

}
$GLOBALS['TEMPLATE']['content'] .= '</table></div></div>';
$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-sm-6 col-xs-12"><form action='. htmlspecialchars($_SERVER["PHP_SELF"]).'  method="get" id="nowyetap">';
   $GLOBALS['TEMPLATE']['content'] .=' <h3><span class="label label-primary">Dodaj nowy etap</span></h3><table><tr>';
   $GLOBALS['TEMPLATE']['content'] .= '<td><label for="etap">Nazwa etapu</label></td>';
   $GLOBALS['TEMPLATE']['content'] .= '<td><input type="text" name="etap" id="etap"/></td></tr><tr>';
   $GLOBALS['TEMPLATE']['content'] .= '<td><label for="opis">Opis etapu</label></td>';
  $GLOBALS['TEMPLATE']['content'] .= ' <td><input type="text" name="opis" id="opis"/></td></tr><tr>';
   $GLOBALS['TEMPLATE']['content'] .= '<td><input type="submit" class="btn btn-default btn-sm" value="Dodaj"/></td>';
  $GLOBALS['TEMPLATE']['content'] .= ' <td><input type="hidden" name="submitted" value="1"/></td>';
  $GLOBALS['TEMPLATE']['content'] .= '<td><input type="hidden" name="id" value="'.$idOgl.'"/></td></tr></table>';
$GLOBALS['TEMPLATE']['content'] .= '</form></div></div>';

$e=0;
$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-sm-6 col-xs-12"><h3><span class="label label-primary">Etapy rekrutacji</span></h3>';
$sql2=sprintf('SELECT * from etap_rekrutacji LEFT JOIN etap_opis ON etap_rekrutacji.id_etapu = etap_opis.id_etapu WHERE etap_opis.id_ogloszenia=%d', $idOgl);
$query2=mysql_query($sql2);

while ($row2 = mysql_fetch_assoc($query2)) {
$nazwa_etapu=$row2[nazwa_etapu];
$id_etapu=$row2[id_etapu];

$opis_etapu=$row2[opis];

$e++;
$GLOBALS['TEMPLATE']['content'] .= '<table class="table"><tr class="active"><td class="col-md-1">'.$e.'.</td><td class="col-md-2">'.$nazwa_etapu.'</td><td class="col-md-2"><a href="dodaj_kan.php?id='.$id_etapu.'&gid='.$idOgl.'">Zarządzaj kandydatami</a></td><td class="col-md-1"><a href="usun_etap.php?id='.$id_etapu.'&gid='.$idOgl.'">Usuń etap</a></td>'
        . '</tr><tr><td colspan="3">Opis etapu: '.$opis_etapu.'</td></tr></table><table class="table">';
$sql3=sprintf('SELECT * from etap_kandydat WHERE etap_kandydat.id_ogloszenia=%d AND etap_kandydat.id_etapu=%d ', $idOgl, $id_etapu);
$query3=mysql_query($sql3);

$n=0;
while ($row3 = mysql_fetch_assoc($query3)) {
$n++;

$id_kan = $row3['USER_ID'];

$kandydat = User::wyswietl($id_kan, $idOgl);
$GLOBALS['TEMPLATE']['content'] .= '<tr class="active"></tr><tr><td>'.$n.'.</td>';
$GLOBALS['TEMPLATE']['content'] .= $kandydat;

}
}

$GLOBALS['TEMPLATE']['content'] .= '</table></div></div>';
$GLOBALS['TEMPLATE']['content'] .= '<div class="container"><div class="col-sm-6 col-xs-12"></br><a href="usun_rek.php?del='.$idOgl.'">Usuń</a> ogłoszenie oraz całą rekrutację.</div></div></br>';



if (isset($_GET['submitted']))
{
    
    $nazwa=$_GET[etap];
    $opis=$_GET[opis];
    $idOgl=$_GET[id];
    $sql1 = sprintf('SELECT * from etap_rekrutacji WHERE nazwa_etapu="%s"', $nazwa);
    $query3=mysql_query($sql1);
    $numrows = mysql_num_rows($query3);
   
    if ($numrows > 0) { 
      $row4 = mysql_fetch_assoc($query3);
      $etap=$row4[id_etapu];
    $sql11 = sprintf('SELECT * from etap_opis WHERE id_etapu=%d AND id_ogloszenia=%d', $etap, $idOgl);
    $query33=mysql_query($sql11);
    $numrows2 = mysql_num_rows($query33); 
     if ($numrows2 > 0) { 
         $GLOBALS['TEMPLATE']['content'] .= '<div class="col-sm-6 col-xs-12">Dodałeś już taki etap rekrutacji</div>';
     }
    
     else
     {
      $sql7=sprintf('INSERT INTO etap_opis (id_etapu, id_ogloszenia, opis) VALUES (%d, %d, "%s")', $etap, $idOgl, $opis);
      $t=mysql_query($sql7);
         header('Location: rekrutacja.php?id='.$idOgl.'');
     }
      
  
    }
    else{
    $sql2=sprintf('INSERT INTO etap_rekrutacji (nazwa_etapu) VALUES ("%s")',$nazwa);
    $i=mysql_query($sql2);
  
    $row5 = mysql_fetch_assoc($i);
    
    $id_e=$row5[id_etapu];
   
    $sql6=sprintf('INSERT INTO etap_opis (id_etapu, id_ogloszenia, opis) VALUES ((select id_etapu from etap_rekrutacji where nazwa_etapu = "%s"), %d, "%s")', $nazwa, $idOgl, $opis);
    $ir=mysql_query($sql6);
     header('Location: rekrutacja.php?id='.$idOgl.'');
    
    }
}


            mysql_query($query, $GLOBALS['DB']);
		
		


include '../templates/template-page.php';
?>



