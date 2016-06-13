<?php
session_start();
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/ogloszenie.php';



$idOgl = $_GET['id'];
$user = $_SESSION['userId'];
ob_start();



$r = Ogloszenie::wyswietlSzczegoly($idOgl);
$GLOBALS['TEMPLATE']['content'] .= $r;
$GLOBALS['TEMPLATE']['content'] .= '<form action="szczegoly.php?id='.$idOgl.'"';
$GLOBALS['TEMPLATE']['content'] .= ' method="post"><div class="form-group">';
  
$GLOBALS['TEMPLATE']['content'] .=    '<input type="hidden" name="us" value='.$idOgl.'/>';
$GLOBALS['TEMPLATE']['content'] .=  '<input type="submit" class="btn btn-default" value="Aplikuj"/><input type="hidden" name="submitted" value="1"/></div>';
$GLOBALS['TEMPLATE']['content'] .= '</form></div></div>';
    
if (isset($_POST['submitted'])){
    $uss=$_POST['us'];
    $sql=sprintf('SELECT * FROM kandydat_wym_umiejetnosc WHERE EXISTS (SELECT * FROM ogloszenie_wym_umiejetnosc where id_ogloszenia=%d) AND USER_ID=%d', $uss, $user);
    $query=mysql_query($sql);

    $num2=mysql_num_rows($query);

     
    $sql2=sprintf('SELECT * from ogloszenie_wym_umiejetnosc where id_ogloszenia=%d', $uss);
    $query2=mysql_query($sql2);

 
 
    $num=mysql_num_rows($query2);

      if (!isset($_SESSION['userId'])){
         $GLOBALS['TEMPLATE']['content'] = '<p>Aby aplikować na ogłoszenie musisz być zalogowany jako poszukujący pracy.<a href="login.php">  Zaloguj się.</a></p>';  

         }
      
    elseif (isset($_SESSION['userId']) AND ($num > $num2)){
  
       $GLOBALS['TEMPLATE']['content'] = '<div class="container"><div class="col-md-6 col-xs-12"><p>Nie posiadasz wymaganych umiejetnosci.</p><a href="szczegoly.php?id='.$uss.'"> Powrót do ogłoszenia.</a></div></div>';  
    }
    else{
           $sql=sprintf('INSERT INTO ogloszenie_kandydaci (id_ogloszenia, USER_ID) VALUES (%d, %d)', $uss, $user);
    $query=mysql_query($sql);
    $GLOBALS['TEMPLATE']['content'] = '<div class="container"><div class="col-md-6 col-xs-12"><p>Twoja aplikacja została zgłoszona.</p><a href="szczegoly.php?id='.$uss.'"> Powrót do ogłoszenia.</a></div></div>';
    }
    }

   


include '../templates/template-page.php';

?>
