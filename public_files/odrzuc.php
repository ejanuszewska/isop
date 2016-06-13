
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
$idOgl = $_GET[idOgl];
$user = $_GET[user];
$sql=sprintf("INSERT INTO rekrutacja_odrzucona (USER_ID, id_ogloszenia) VALUES (%d, %d)", $user, $idOgl);
$query=mysql_query($sql);
  header('Location: rekrutacja.php?id='.$idOgl.'');

include '../templates/template-page.php';
?>



