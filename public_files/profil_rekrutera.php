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
                  $("#zapisz").hide();
                     $("#form_rek").hide();
                    
            $('#form_rek').validate({
    rules: {
   
     imie: {
        required: true,
        minlength: 2
    },
     nazwisko: {
        required: true,
        minlength: 2
    },
     nazwa_firmy: {
        required: true,
        minlength: 2
    },
     miasto: {
        required: true,
        minlength: 2
    },
     ulica: {
        required: true,
        minlength: 2
    },
     numer_domu: {
        required: true,
        minlength: 1
    }
     
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
  jQuery.extend(jQuery.validator.messages, {
    required: 'To pole jest wymagane',
    minlength: $.validator.format("Wprowadź co najmniej {0} znaków.")
  });

            });
            
            function edit(){
                 $("#zapisz").show();
                 $("#edytuj").hide();
                 $("#tabela").hide();
                 $("#form_rek").show();
            }
            function wojewodztwo22() {
            var city = $("#miasto").val();
            requestUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=" + city;
            $.ajax({url: requestUrl, success: function(result){
                result.results[0].address_components.forEach(function(entry) {
                    if (entry.types[0] == 'administrative_area_level_1') {
                        var res = entry.short_name.split(" ");
                        var woj;
                       if (res[0].toLowerCase() == 'województwo')
                                woj = res[1];
                        else
                                woj = res[0];
                        console.log(woj);
                        $("#wojewodztwo").val(woj);
                    }
                });
            }});
        };	
            </script>


<?php
session_start();

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/rec.php';
include '../lib/firma.php';


$rekruter = rekruter::getById($_SESSION['rId']);

$firma2=$rekruter->id_firmy;


                $firma=Firma::getById($firma2);
               
ob_start();
?>
<div class="container"><div class="col-sm-6 col-xs-12">
        <table class="table" id="tabela"><tr class="active"><td><strong>Dane rekrutera</strong></td><td></td><tr><td>
                    Imię</td><td>
   <?php echo $rekruter->imie; ?>
                    </td></tr><tr><td>
                            Nazwisko</td><td>
   <?php echo $rekruter->nazwisko; ?>
                            </td></tr><tr><td><a href="zmiana_hasla.php">Zmień hasło</a></td></tr>
                            <tr class="active"><td><strong> Dane firmy</strong></td><td> </td></tr>
 <tr><td>Nazwa firmy</td><td>
   <?php echo $firma->nazwa; ?></td></tr><tr class="active"><td>

  <?php
    $sql = sprintf("SELECT * from firma where id_firmy= %d", $rekruter->id_firmy ); 
$result = mysql_query($sql);

?>

<?php

?>

           <strong> Adres firmy</strong></td><td></td></tr></tr><tr><td>
  

                   Miasto</td><td>
 
  <?php
  $sql = sprintf('SELECT nazwa_miasta FROM miasto WHERE id_miasta=%d', $firma->idMiasta);
  $query=mysql_query($sql);
  while($r=mysql_fetch_assoc($query)){
      $nazwa_miasta=$r[nazwa_miasta];
  }
  echo $nazwa_miasta;?>
                   </td></tr><tr><td>
                           Ulica</td><td>
  <?php echo $firma->ulica; ?>
                           </td></tr><tr><td>
                                   Numer domu</td><td>
  <?php echo $firma->numer_domu; ?>
                                   </td></tr><tr><td>
                                           Numer lokalu</td><td>
   <?php echo $firma->numer_lokalu; ?>
                                           </td></tr>
           <tr><td><button  class="btn btn-primary" type="button" id="edytuj" value="Edytuj" onclick="edit()">Edytuj</button></td></tr></table>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="post" id="form_rek">
    <h4><strong>Dane rekrutera</strong></h4>
     <div class="form-group">
 <label for="imie">Imię</label>
   <input type="text" name="imie" id="imie" class="form-control"
    value="<?php echo (isset($_POST['imie']))? htmlspecialchars(
$_POST['imie']) : $rekruter->imie; ?>"/>
     </div>
     <div class="form-group">
 <label for="nazwisko">Nazwisko</label>
   <input type="text" name="nazwisko" id="nazwisk" class="form-control"
    value="<?php echo (isset($_POST['nazwisko']))? htmlspecialchars(
$_POST['nazwisko']) : $rekruter->nazwisko; ?>"/>
     </div>
  <h4><strong>Dane firmy </strong></h4>
  <div class="form-group">
   <label for="nazwa_firmy">Nazwa firmy</label>
   <input type="text" name="nazwa_firmy" id="nazwa_firmy" class="form-control"
    value="<?php echo (isset($_POST['nazwa_firmy']))? htmlspecialchars(
            $_POST['nazwa_firmy']) : $firma->nazwa; ?>"/></div>
 
  <?php
    $sql = sprintf("SELECT * from firma where id_firmy= %d", $rekruter->id_firmy ); 
$result = mysql_query($sql);





?>

 <h4><strong>Adres firmy</strong></h4>
   <div class="form-group">

  <label for="miasto">Miasto</label>
 
  <?php
  $sql = sprintf('SELECT nazwa_miasta FROM miasto WHERE id_miasta=%d', $firma->idMiasta);
  $query=mysql_query($sql);
  while($r=mysql_fetch_assoc($query)){
      $nazwa_miasta=$r[nazwa_miasta];
  }
  echo "<input type='text' name='miasto' id='miasto' onchange='wojewodztwo22()' class='form-control' value={$nazwa_miasta}>";?>
   </div>
   <?php
            echo "<input type='hidden' name='wojewodztwo' class='form-control' id='wojewodztwo'/>";
            ?>
    <div class="form-group">
       <label for="ulica">Ulica</label>
  <input type="text" name="ulica" id="ulica" class="form-control"
    value="<?php echo (isset($_POST['ulica']))? htmlspecialchars(
$_POST['ulica']) : $firma->ulica; ?>"/>
    </div>
    <div class="form-group">
 <label for="numer_domu">Numer domu</label>
  <input type="text" name="numer_domu" id="numer_domu" class="form-control"
    value="<?php echo (isset($_POST['numer_domu']))? htmlspecialchars(
$_POST['numer_domu']) : $firma->numer_domu; ?>"/>
    </div>
    <div class="form-group">
   <label for="numer_lokalu">Numer lokalu</label>
   <input type="text" name="numer_lokalu" id="numer_lokalu" class="form-control"
    value="<?php echo (isset($_POST['numer_lokalu']))? htmlspecialchars(
$_POST['numer_lokalu']) : $firma->numer_lokalu; ?>"/>
     <div class="form-group">
  <input type="submit" class="btn btn-default" id="zapisz" value="Zapisz"/>
   <input type="hidden" name="submitted" value="1"/>
     </div>
    </div>

</form></div></div>
<?php
$form = ob_get_clean();

if (!isset($_POST['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}

else
{
  


    if (1)
    {
               
		$rekruter->imie = $_POST['imie'];
		$rekruter->nazwisko = $_POST['nazwisko'];
                $nazwa=$_POST['nazwa_firmy'];
                $firma->nazwa = $nazwa;
                $branza = $_POST['branza'];
                $miasto = $_POST['miasto'];
                $wojewodztwo=$_POST['wojewodztwo'];
                $firma->nazwa = $_POST['nazwa_firmy'];
                $firma->ulica = $_POST['ulica'];
                $firma->numer_domu = $_POST['numer_domu'];
                $firma->numer_lokalu = $_POST['numer_lokalu'];
                $sql = sprintf('SELECT id_miasta from miasto WHERE nazwa_miasta = "%s"', $miasto);
                $result = mysql_query($sql);
                $num=mysql_num_rows($result);
                while($row=mysql_fetch_array($result)){
                    $miasto=$row[id_miasta];
                }
                
                if($num==0){
                    $sql=('SELECT id wojewodztwa from wojewodztwo where nazwa_wojewodztwa="%s",$wojewodztwo');
                    $query=mysql_query($sql);
                    $row=mysql_fetch_assoc($query);
                    $woj=$row[id_wojewodztwa];
                    $sql=sprintf('INSERT INTO miasto (nazwa_miasta, id_wojewodztwa) VALUES ("%s",%d)', $miasto, $woj);
                    $query=mysql_query($sql);
                    $miasto=mysql_insert_id();
                    $firma->idMiasta = $miasto;
                }
                else{
                $firma->idMiasta = $miasto;
                }
                 
                $sql2 = sprintf('SELECT id_branzy from branza WHERE nazwa_branzy = "%s"', $branza);
                $result2 = mysql_query($sql2);
                  
                $firma->idBranzy = $result2;
                $firma->save();
                
                $rr=mysql_insert_id();
            
              
               $s= sprintf('SELECT id_firmy FROM firma WHERE nazwa_firmy = "%s"', $nazwa);
               $ss=mysql_query($s);
               $r=mysql_num_rows($ss);
               
           
               if($r==0){
               
               $rekruter->id_firmy=$rr;
             
                   
               }
            
               
               else{
                while ($row = mysql_fetch_assoc($ss)) { 
               $rekruter->id_firmy=$row[id_firmy];
                }
               }


        $rekruter->save();
        


            mysql_query($query, $GLOBALS['DB']);
		
		
$sql='INSERT INTO branza (nazwa_branzy) VALUES ("Obsługa klienta")';
$query=mysql_query($sql);
$sql='INSERT INTO branza (nazwa_branzy) VALUES ("Nieruchomości")';
$query=mysql_query($sql);
        $GLOBALS['TEMPLATE']['content'] = '<p><strong>Informacje ' .
            'w bazie danych zostały uaktualnione.</strong></p>';
    }

    else
    {
        $GLOBALS['TEMPLATE']['content'] .= '<p><strong>Podano nieprawidłowe ' .
            'dane.</strong></p>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }
}

include '../templates/template-page.php';
?>


