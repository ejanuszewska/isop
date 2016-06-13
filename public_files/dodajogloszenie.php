<?php

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/ogloszenie.php';
include '../lib/rec.php';
include '../lib/firma.php';

include '401.php';

$rekruter = rekruter::getById($_SESSION['rId']);

$firma2 = $rekruter->id_firmy;


$firma = Firma::getById($firma2);

ob_start();
?>

    <div class="col-sm-6 col-xs-12">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
              method="post" id="form_ogl">
            <div class="form-group"><label for="stanowisko">Stanowisko</label>
                <input class="form-control" type="text" name="stanowisko" id="stanowisko"/></div>
            <label for="branza">Branza</label><?php
            echo "<select name='branza' class='form-control'>";
            $sql2 = "SELECT * FROM branza";
            $result2 = mysql_query($sql2);
            while ($row = mysql_fetch_assoc($result2)) {

                echo "<option value={$row['nazwa_branzy']}>{$row['nazwa_branzy']}</option>";
            }
            ?>
            </select> 

            <?php
            echo "<input type='hidden' name='wojewodztwo' class='form-control' id='wojewodztwo'/>";
            ?>
        

            <label for="miasto">Miasto</label>

            <input class='form-control' id="city" name="miasto" autocomplete="off" data-country="pl" />
            <div class="form-group"><label for="opis">Opis stanowiska</label>
                <textarea class='form-control' rows="3" name="opis" id="opis"></textarea>
            </div>
            <div class="form-group">
                <label for="wymagania">Wymagania</label>
                <textarea  rows="3" class='form-control' name="wymagania" id="wymagania"></textarea>
            </div>
            <div class="form-group">
                <label>Umiejętności</label>

                <input id="ms" class="form-control" name="umiejetnosci[]" />
            </div>


            <div class="form-group">
                <label for="pensja_min">Pensja minimalna</label>
                <input type="text" class='form-control' name="pensja_min" id="pensja_min"/>
            </div>
            <div class="form-group">
                <label for="pensja_max">Pensja maksymalna</label>
                <input type="text" class='form-control' name="pensja_max" id="pensja_max"/>
            </div>

            <label for="czas_trwania">Czas trwania rekrutacji</label> <?php
            echo "<select name='czas_trwania' class='form-control'>";
            $sql2 = "SELECT * FROM okres_aplikacji";
            $result2 = mysql_query($sql2);
            while ($row = mysql_fetch_assoc($result2)) {

                echo "<option value={$row['okres_aplikacji']}>{$row['okres_aplikacji']}</option>";
            }
            ?>
            </select> 
	    </br>
            <div class="form-group">
                <input class="btn btn-default" type="submit" value="Zapisz"/>
                <input type="hidden" name="submitted" value="1"/>
            </div>

        </form></div>
<?php
$form = ob_get_clean();

// wy¶wietlenie formularza, je¶li strona jest wy¶wietlana po raz pierwszy
if (!isset($_POST['submitted'])) {
    $GLOBALS['TEMPLATE']['content'] = $form;
}
// w przeciwnym razie przetworzenie danych wej¶ciowych
else {


    // uaktualnienie rekordu, jeżeli dane wej¶ciowe s± poprawne
    if (1) {
        $ogl = new Ogloszenie();
         $wojewodztwo = $_POST[wojewodztwo];
   
        $stanowisko = $_POST[stanowisko];
        $s = sprintf("select * from stanowisko where nazwa_stanowiska='%s'", $stanowisko);
        $rekordy = mysql_query($s);
        $num=mysql_num_rows($rekordy);
        if ($num == 0) {
           
            $sql4 = sprintf('INSERT INTO stanowisko (nazwa_stanowiska) VALUES ("%s")', $stanowisko);
            $result4 = mysql_query($sql4);
            $r = mysql_insert_id();
            $ogl->idStanowiska = $r;
        } else {
           while($row=mysql_fetch_assoc($rekordy))
           {
            $ogl->idStanowiska = $row[id_stanowiska];
           }
        }
        $opis = $_POST[opis];
        $opis = nl2br(htmlentities($opis, ENT_QUOTES, 'UTF-8'));
        $ogl->opisPracy = $opis;
        $ogl->id_firmy = $firma2;
        
        $wym = $_POST[wymagania];
        $wymagania = nl2br(htmlentities($wym, ENT_QUOTES, 'UTF-8'));
        $ogl->wymagania = $wymagania;
        $ogl->pensjaMin = $_POST[pensja_min];
        $ogl->pensjaMax = $_POST[pensja_max];
       
        
        $miasto = $_POST[miasto];
        $sql = sprintf('SELECT id_miasta from miasto WHERE nazwa_miasta = "%s"', $miasto);
        $result = mysql_query($sql);
        $num = mysql_num_rows($result);
       
        if ($num > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $ogl->idMiasta = $row[id_miasta];
            }
        } else {
              $sql = sprintf('SELECT id_wojewodztwa from wojewodztwo where nazwa_wojewodztwa="%s"', $wojewodztwo);
            $result = mysql_query($sql);
           
            while ($row=mysql_fetch_assoc($result)){
                $id_woj=$row[id_wojewodztwa];
            }
            $sql = sprintf('INSERT INTO miasto (nazwa_miasta, id_wojewodztwa) VALUES ("%s", %d)', $miasto, $id_woj);
            $result = mysql_query($sql);
            $r = mysql_insert_id();
            $ogl->idMiasta = $r;
         
        }

        $branza = $_POST['branza'];
        $sql2 = sprintf('SELECT id_branzy from branza WHERE nazwa_branzy = "%s"', $branza);
        $result2 = mysql_query($sql2);
   
        while ($row = mysql_fetch_assoc($result2)) {
            $ogl->idBranzy = $row[id_branzy];
        }
        


        $czas = $_POST['czas_trwania'];
        $sql3 = sprintf('SELECT id_okresu_aplikacji from okres_aplikacji WHERE "okres_aplikacji" = %d', $czas);
        $result3 = mysql_query($sql3);
        $ogl->idOkresuAplikacji = $result3;
        $countries = isset($_POST['umiejetnosci']) ? $_POST['umiejetnosci'] : array();
     
        $ogl->save();
        $r=  mysql_insert_id();
                $der=$r;
               foreach($countries as $idkd){
         
            $sql=sprintf('SELECT * from umiejetnosc where id_umiejetnosci=%d', $idkd);
            $query=mysql_query($sql);
            $num=mysql_num_rows($query);
      
            if ($num == 0){
               $sql=sprintf('INSERT INTO umiejetnosc (typ_umiejetnosci) VALUES ("%s")', $idkd);
              
               $query=mysql_query($sql);
               $r=mysql_insert_id();
               $umr=$r;
                 $sql=sprintf('INSERT INTO ogloszenie_umiejetnosc (id_umiejetnosci, id_ogloszenia) VALUES (%d, %d)', $umr, $der);
      $query=mysql_query($sql);
  }
  
  else{
      $sql=sprintf('INSERT INTO ogloszenie_umiejetnosc (id_umiejetnosci, id_ogloszenia) VALUES (%d, %d)', $idkd, $r);
      $query=mysql_query($sql);
  }
         }    
     

        mysql_query($query, $GLOBALS['DB']);




    }

    else {
        $GLOBALS['TEMPLATE']['content'] .= '<p><strong>Podano nieprawidłowe ' .
                'dane.</strong></p>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }
   
}


include '../templates/template-page.php';
?>

<script src="../lib/magic/magicsuggest.js"></script>
<script>
        

</script>
<script>
    
    
    $(document).ready(function () {

        $('#form_ogl').validate({
            rules: {
                stanowisko: {
                    required: true,
                    minlength: 2
                },
                 miasto: {
                    required: true,
                    minlength: 2
                },
                wymagania: {
                    required: true,
                    minlength: 2
                },
                opis: {
                    required: true,
                    minlength: 2
           },
                pensja_min: {
                    required: true,
                    minlength: 1
                },
                pensja_max: {
                    required: true,
                    minlength: 1
                }
            },
            success: function (label) {
                label.text('OK!').addClass('valid');
                //getVoivodeship();
            }
        });
        jQuery.extend(jQuery.validator.messages, {
            required: 'To pole jest wymagane',
            minlength: $.validator.format("Wprowadź co najmniej {0} znaków.")
        });
        
        $('input#city').cityAutocomplete();

    });
        $('#city').on('change',(function() {
            var city = $("#city").val();
            requestUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=" + city;
            $.ajax({url: requestUrl, success: function(result){
                result.results[0].address_components.forEach(function(entry) {
                    if (entry.types[0] == 'administrative_area_level_1') {
                        var res = entry.short_name.split(" ");
                        var woj;
                        if (res[0] == 'Województwo')
                                woj = res[1];
                        else
                                woj = res[0];
                        $("#wojewodztwo").val(woj);
                    }
                });
            }});
        }));
    $(function() {

        var ms=  $('#ms').magicSuggest({
            data: 'um.php',
            valueField: 'id_umiejetnosci',
            displayField: 'typ_umiejetnosci',
            allowFreeEntries: 'false',
              placeholder: 'Wprowadź umiejętność lub kliknij tutaj',
            mode: 'remote',
            renderer: function (data) {
                return '<div class="umiejetnosci">' +
                        '<div class="name">' + data.typ_umiejetnosci + '</div>' +
                        '<div style="clear:both;"></div>' +
                        '</div>';
            },
            resultAsString: true,
            selectionRenderer: function (data) {
                return '<div class="name">' + data.typ_umiejetnosci + '</div>';
            }

        });
    });
</script>
