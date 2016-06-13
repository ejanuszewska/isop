	
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script>
			$(document).ready(function(){
				showPage = function(page) {
					pageSize = 5;
				    $(".content").hide();
				    $(".content").each(function(n) {
				        if (n >= pageSize * (page - 1) && n < pageSize * page)
				            $(this).show();
				    });       
				}
				showPage(1);
			}

			);
		</script>
<?php
session_start();
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/ogloszenie.php';

$user = User::getById($_SESSION['userId']);


?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="post">
    <table>
        <tr><td>
                <input type="text" name="slowo_kluczowe" placeholder="Słowo kluczowe" /></td></tr>
       <tr>
   <td><label for="branza">Branza</label></td> <?php
  echo "<td><select name='branza'>";
 $sql2 = "SELECT * FROM branza"; 
$result2 = mysql_query($sql2);
 while ($row = mysql_fetch_assoc($result2)) { 
  
  echo "<option value={$row['nazwa_branzy']}>{$row['nazwa_branzy']}</option>";
} 
   echo "</td>"; ?>
</select> 
  </tr>
     <tr>
   <td><label for="wojewodztwo">Województwo</label></td> <?php
  echo "<td><select name='wojewodztwo'>";
 $sql2 = "SELECT * FROM wojewodztwo"; 
$result2 = mysql_query($sql2);
 while ($row = mysql_fetch_assoc($result2)) { 
  
  echo "<option value={$row['nazwa_wojewodztwa']}>{$row['nazwa_wojewodztwa']}</option>";
} 
   echo "</td>"; ?>
</select> 
  </tr>
    <tr>
   <td><label for="branza">Miasto</label></td> <?php
  echo "<td><select name='miasto'>";
 $sql2 = "SELECT * FROM miasto"; 
$result2 = mysql_query($sql2);
 while ($row = mysql_fetch_assoc($result2)) { 
  
  echo "<option value={$row['nazwa_miasta']}>{$row['nazwa_miasta']}</option>";
} 
   echo "</td>"; ?>
</select> 
  </tr>
        <tr>    <td><input type="submit" value="Szukaj"/></td>
   <td><input type="hidden" name="submitted" value="1"/></td></tr>
    </table>
</form>
<?php


if (isset($_POST['submitted'])){
$form = ob_get_clean();
$GLOBALS['TEMPLATE']['content'] .= $form;
$wojewodztwo = $_POST['wojewodztwo'];
$miasto = $_POST['miasto'];
$stanowisko = $_POST['slowo_kluczowe'];
$branza = $_POST['branza'];
$e = Ogloszenie::filtruj($miasto, $branza);

}
else{
    $form = ob_get_clean();
$GLOBALS['TEMPLATE']['content'] .= $form;
    $sql = 'SELECT * FROM ogloszenie';
$q=mysql_query($sql);

while ($row = mysql_fetch_array($q)) {
$ogl = Ogloszenie::getById($row[id_ogloszenia]);
$r = $ogl->wyswietl();

}
}
include '../templates/template-page.php';

?>
