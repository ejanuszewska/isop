<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';

    $_SESSION = array();
    session_unset();
    session_destroy();
   header('Location: index.php');
    

include '../templates/template-page.php';
?>
