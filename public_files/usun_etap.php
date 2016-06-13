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

$l=0;?>
<div class="container">
  <div class="col-sm-6">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="get">
     <div class="form-group">
         <label for="email">Czy na pewno chcesz usunąć ten etap rekrutacji?</label></div>
     <div class="form-group"> <input class="btn btn-success btn-sm"  type="submit" value="Tak"/>
   <input type="hidden" name="submitted" value="1"/>
            <input type="hidden" name="id" value="<?php echo $idOgl; ?>"/>
            <input type="hidden" name="idetap" value="<?php echo $idEtapu; ?>"/> <?php
       echo '<a href="rekrutacja.php?id='.$idOgl.'" class="btn btn-warning btn-sm">Nie</a>';?>
     </div>
 
</form>
       </div>
     </div>
<?php
$form = ob_get_clean();
if (!isset($_GET['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}
else 
{
    
    $ogl=$_GET[id];
    $etap=$_GET[idetap];
    echo $ogl;
    echo $etap;
    $sql1 = sprintf('DELETE FROM etap_kandydat where etap_kandydat.id_ogloszenia=%d and etap_kandydat.id_etapu=%d', $ogl, $etap);
    $query3=mysql_query($sql1);
    $sql1 = sprintf('DELETE FROM etap_opis where etap_opis.id_ogloszenia=%d and etap_opis.id_etapu=%d', $ogl, $etap);
    $query3=mysql_query($sql1);
    echo $sql1;
     header('Location: rekrutacja.php?id='.$ogl.'');
    
    
}
include '../templates/template-page.php';
?>