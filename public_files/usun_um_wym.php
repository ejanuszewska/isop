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

$user = User::getById($_SESSION['userId']);




$idUm = $_GET[del];



    
  
    $sql1 = sprintf('DELETE from kandydat_wym_umiejetnosc where id_umiejetnosci=%d AND USER_ID=%d', $idUm, $user->userId );
    $query3=mysql_query($sql1);
    header('Location: profil.php');
    
    

include '../templates/template-page.php';
?>
