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



$idOgl = $_GET[del];
$l=0;?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="get">
    <div class="form-group"><label>Czy na pewno chcesz usunąć rekrutację?</label></div>
        <div class="form-group"><input type="submit" value="Tak" class="btn btn-success btn-sm"/>
   <input type="hidden" name="submitted" value="1"/><?php
          echo  '<input type="hidden" name="id" value="'.$idOgl.'" />';
        echo'<a href="rekrutacja.php?id='.$idOgl.'" class="btn btn-warning btn-sm">Nie</a></div>';
       ?></table>
</form>
<?php
$form = ob_get_clean();
if (!isset($_GET['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}
else
{
    echo "wysalne";
    $ogl=$_GET[id];
    $sql1 = sprintf('DELETE from ogloszenie where ogloszenie.id_ogloszenia=%d', $ogl);
    $query3=mysql_query($sql1);
     header('Location: panel_rek.php');
    
    
}
include '../templates/template-page.php';
?>