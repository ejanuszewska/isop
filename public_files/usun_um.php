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
echo $idDosw;
$l=0;?><div class="container">
  <div class="col-sm-6 col-xs-12">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="get">
    <div class="form-group"><label>Czy na pewno chcesz usunąć tą umiejętność?</label></div>
        <div class="form-group"><input type="submit" class="btn btn-success btn-sm" value="Tak"/>
   <input type="hidden" name="submitted" value="1"/>
            <input type="hidden" name="id" value="<?php echo $idUm; ?>"/>
             <input type="hidden" name="user" value="<?php echo $user->userId; ?>"/>
        <a href="profil.php" class="btn btn-warning btn-sm">Nie</a>
        </div>
</form></div></div>
<?php
$form = ob_get_clean();
if (!isset($_GET['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}
else
{
    
    $idUm=$_GET[id];
    $idUser=$_GET[user];
    $sql1 = sprintf('DELETE from user_umiejetnosc where id_umiejetnosci=%d AND USER_ID=%d', $idUm, $idUser );
    $query3=mysql_query($sql1);
    header('Location: profil.php');
    
    
}
include '../templates/template-page.php';
?>
