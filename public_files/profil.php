

   

        
        <?php

        include '../lib/common.php';
        include '../lib/db.php';
        include '../lib/functions.php';
        include '../lib/User.php';



        include '401.php';
        $id_w = 1;

        $user = User::getById($_SESSION['userId']);
        ob_start();
        
        ?>
        
            <div class="col-sm-6 col-xs-12 container">
                    <h3>Dane podstawowe</h3>                
                
               <table class="table" id="name"><tr><td>

                    Imię</td><td>
                      <?php echo $user->imie;
                      ?></td></tr><tr><td>Nazwisko</td>
                       <td><?php echo  $user->nazwisko;
                       ?></td></tr>

                    <?php
                    $sql = sprintf("SELECT DATA_UR FROM kandydat WHERE USER_ID= %d", $user->userId);

                    $result = mysql_query($sql);
                    $d = mysql_fetch_array($result);
                  
                    ?>
                    
                    <tr><td>Data urodzenia</td>
                    <td><?php echo date('Y-m-d',strtotime($d[0])) ?></td></tr>
                    <tr><td><button id="sub_name_edit" class="btn btn-primary btn-sm" type="button" value="Edytuj" onclick="editName()">Edytuj</button>  
                        </td><td><a href="zmien_haslo.php"> Zmień hasło </a></td></tr>
               </table>
                           
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post" id="reg">

                    <div class="form-group">
                        <label for="imie">Imię</label>
                        <input type="text" name="imie" id="imie" class="form-control"
                               value="<?php echo (isset($_POST['imie'])) ? htmlspecialchars(
                        $_POST['imie']) : $user->imie;
        ?>"/></div>
                    <?php   $id_w++; ?>
                    <div class="form-group"><label for="nazwisko">Nazwisko</label>
                        <input type="text" name="nazwisko" id="nazwisko" class="form-control"
                               value="<?php echo (isset($_POST['nazwisko'])) ? htmlspecialchars(
                                               $_POST['nazwisko']) : $user->nazwisko;
        ?>"/></div>

                    <?php
                    $sql = sprintf("SELECT DATA_UR FROM kandydat WHERE USER_ID= %d", $user->userId);

                    $result = mysql_query($sql);
                    $d = mysql_fetch_array($result);
                 
                    ?>
                    
                    <div class="form-group"><label for="data_urodzenia">Data urodzenia</label>
<div class="form-inline">
                     <input name="data_ur" id="data_ur" type="date"  value="<?php echo date('Y-m-d',strtotime($d[0])) ?>"/>
                      
                     
                    </div>
                    </div>
                    <div class="form-group" >
                        <input type="submit" class="btn btn-primary btn-sm" value="Zapisz" id="sub_name"/>
                        <input type="hidden" name="submitted_name" value="1"/>
                    </div>
                </form>


                <h3>Wykształcenie</h3>

                <?php
                $sql66 = sprintf("SELECT szkola.nazwa_szkoly, wyksztalcenie.id_wyksztalcenia, kierunek.nazwa_kierunku, wyksztalcenie.data_rozpoczecia, wyksztalcenie.data_zakonczenia FROM wyksztalcenie LEFT JOIN szkola ON wyksztalcenie.id_szkoly = szkola.id_szkoly LEFT JOIN kierunek on wyksztalcenie.id_kierunku = kierunek.id_kierunku LEFT JOIN wyksztalcenie_kandydat on wyksztalcenie.id_wyksztalcenia=wyksztalcenie_kandydat.id_wyksztalcenia WHERE wyksztalcenie_kandydat.USER_ID = %d", $user->userId);
                $result66 = mysql_query($sql66);




                while ($row = mysql_fetch_assoc($result66)) {
                    ?>
                <table class="table" id="<?php echo '1' . $id_w; ?>">
                              <?php
                              $sql77 = sprintf("SELECT data_rozpoczecia, data_zakonczenia FROM wyksztalcenie WHERE id_wyksztalcenia = %d", $row[id_wyksztalcenia]);

                              $result77 = mysql_query($sql77);
                              $dd = mysql_fetch_array($result77);
                              $data_rozp = $dd[0];
                              $data_zak = $dd[1];
                        ?>
                              <tr><td>Placowka</td><td><?php
                              echo $row['nazwa_szkoly'];
                              echo "</td></tr><tr><td>Kierunek</td><td>";

                              echo $row['nazwa_kierunku'];
                              ?>
                        </td></tr><tr><td>Data rozpoczęcia nauki</td>
                    <td><?php echo date('Y-m-d',strtotime($dd[0])) ?>
                           
                   
                    </td></tr><tr>
                        <td>Data zakończenia nauki</td>

                     <td><?php echo date('Y-m-d',strtotime($dd[1])); ?>
                         
                      
                     </td></tr>
                              <tr><td><button type="button" class="btn btn-primary btn-sm" value="Edytuj" id="<?php echo '1' . $id_w; ?>" onclick="editEduc(<?php echo $id_w; ?>); hidewyk(<?php echo '10' . $id_w; ?>);">Edytuj</button><a href="usun_eduk.php?del=<?php echo $row[id_wyksztalcenia]; ?>"> Usuń</a></td></tr></table>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          method="post" id="<?php echo '10' . $id_w; ?>" class="form_wyk"><?php
                        echo'<div id="'.$id_w.'" ';
                              
                              $sql77 = sprintf("SELECT data_rozpoczecia, data_zakonczenia FROM wyksztalcenie WHERE id_wyksztalcenia = %d", $row[id_wyksztalcenia]);

                              $result77 = mysql_query($sql77);
                              $dd = mysql_fetch_array($result77);
                              $data_rozp = $dd[0];
                              $data_zak = $dd[1];
                        
                              echo "<div class='form-group' ><label for='placowka'>Placowka</label>";
                              echo "<input type='text' class='form-control' name='placowka' id='placowka' value='{$row["nazwa_szkoly"]}'/></div>";
                              echo "<div class='form-group' ><label for='kierunek'>Kierunek</label>";

                              echo "<input type='text' class='form-control' name='kierunek' id='kierunek' value='{$row[nazwa_kierunku]}'></div>";
                              ?>
                        <div class='form-group'>
                            <label for="data_rozp_nauki">Data rozpoczęcia nauki</label>
                    <input name="data_rozp_nauki" type="date"  value="<?php echo date('Y-m-d',strtotime($dd[0])) ?>"/>
                           
                   
                        </div>
                        <div class='form-group'><label for="data_zak_nauki">Data zakończenia nauki</label>

                       <input name="data_zak_nauki" type="date"  value="<?php echo date('Y-m-d',strtotime($dd[1])) ?>"/>
                         
                      
                        </div>




                            <div class="form-group">
                            <input type="submit" class="sub_educ btn btn-primary btn-sm" id="<?php echo '2' . $id_w; ?>" value="Zapisz"/>
                            <input type="hidden" name="submitted_educ" value="<?php echo $row[id_wyksztalcenia]; ?>"/>
                            <a href="usun_eduk.php?del=<?php echo $row[id_wyksztalcenia]; ?>" > Usuń</a>
                        </div>
                    </form>
    <?php
    $id_w++;
}
?>




                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post" id="form_wyk_dod">
                    <div class='form-group dodawanieEdukacja'>
                        <label for="placowka">Placowka</label>
                        <input type="text" name="placowka" id="placowka" class="form-control"/></div>
                    <div class='form-group dodawanieEdukacja'>  <label for="data_rozp_nauki">Data rozpoczęcia nauki</label>
                                           <input name="data_rozp_nauki" type="date" value="<?php echo date("2015-01-01"); ?>"/>

                            
                               
                            
                               </div><div class='form-group dodawanieEdukacja'>
                        <label for="data_zak_nauki">Data zakończenia nauki</label>
                                              <input name="data_zak_nauki" type="date" value="<?php echo date("2015-01-01"); ?>"/>
</div><div class='form-group dodawanieEdukacja'>
                        <label for="kierunek">Kierunek</label><input type="text" name="kierunek" id="kiernek" class="form-control"/></div>
                    <div class='form-group'><button class="btn btn-default btn-sm" type="button" onclick="dodajEdukacje()" id="przyciskDodajEdukacje">Dodaj wykształcenie</button></div>
                    <div  class='form-group dodawanieEdukacja'> <input class='btn btn-success btn-sm' type="submit" id="sub_educ_new" value="Zapisz"/><button class='btn btn-warning btn-sm' type="button" onclick="anulujEduc()">Anuluj</button>
                       
                        <input type="hidden" name="submitted_educ_new" value="1"/>
                    </div>
                </form>

                <h3>Doświadczenie</h3>

                <?php
                $sql32 = sprintf("SELECT firma.nazwa_firmy, doswiadczenie.id_doswiadczenia, stanowisko.nazwa_stanowiska, miasto.nazwa_miasta,"
                        . "doswiadczenie.data_rozpoczecia, doswiadczenie.data_zakonczenia FROM doswiadczenie LEFT JOIN stanowisko ON "
                        . "doswiadczenie.id_stanowiska = stanowisko.id_stanowiska LEFT JOIN firma on doswiadczenie.id_firmy = firma.id_firmy"
                        . " LEFT JOIN miasto on doswiadczenie.id_miasta=miasto.id_miasta LEFT JOIN doswiadczenie_kandydat on doswiadczenie.id_doswiadczenia=doswiadczenie_kandydat.id_doswiadczenia"
                        . " WHERE doswiadczenie_kandydat.USER_ID = %d", $user->userId);
                $result32 = mysql_query($sql32);




                while ($row = mysql_fetch_assoc($result32)) {
                    ?>
                <table class="table"  id="<?php echo '3' . $id_w; ?>">
                              <?php
                              $sql999 = sprintf("SELECT data_rozpoczecia, data_zakonczenia FROM doswiadczenie WHERE id_doswiadczenia = %d", $row['id_doswiadczenia']);

                              $result999 = mysql_query($sql999);
                              $dd9 = mysql_fetch_array($result999);
                              $data_rozp2 = $dd9[0];
                            
                              $data_zak2 = $dd9[1];
                             
                              
                              echo "<tr><td>Firma</td><td>";
                              echo $row['nazwa_firmy'];
                              echo "</td></tr><tr><td>Stanowisko</td><td>";
                              echo $row['nazwa_stanowiska'];
                              echo "</td></tr><tr><td>Miasto</td><td>";
                              echo $row['nazwa_miasta'];
                              ?>
                </td></tr><tr><td>Data rozpoczęcia pracy</td><td>
                     <?php echo date('Y-m-d',strtotime($data_rozp2)); ?>

                    </td></tr><tr><td>
                        Data zakończenia pracy</td><td>
                                      <?php echo date('Y-m-d',strtotime($data_zak2)) ?>

                    </td></tr><tr><td><button id="<?php echo '3' . $id_w; ?>" class="btn btn-primary btn-sm" type="button"  value="Edytuj" onclick="editDosw(<?php echo $id_w; ?>); hidewyk(<?php echo '20' . $id_w; ?>);">Edytuj</button>  <a href="deletedosw.php?del=<?php echo $row[id_doswiadczenia]; ?>"> Usuń</a></td></tr></table>
    <?php
    ?>



                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          method="post" id="<?php echo '20' . $id_w; ?>" class="form_dosw">
                        <?php
            echo "<input type='hidden' name='wojewodztwo' class='form-control' id='".'70' . $id_w."'/>";
            ?>
                              <?php
                              $sql99 = sprintf("SELECT data_rozpoczecia, data_zakonczenia FROM doswiadczenie WHERE id_doswiadczenia = %d", $row[id_doswiadczenia]);

                              $result99 = mysql_query($sql99);
                              $dd = mysql_fetch_array($result99);
                              $data_rozp = $dd[0];
                              $data_zak = $dd[1];
                             
                         
                              echo "<div class='form-group'><label for='firma'>Firma</label>";
                              echo "<input type='text' name='firma' class='form-control' id='firma' value='{$row[nazwa_firmy]}'></div>";
                              echo "<div class='form-group'><label for='stanowisko'>Stanowisko</label></td>";
                              echo "<input type='text' class='form-control' name='stanowisko' id='stanowisko' value='{$row[nazwa_stanowiska]}'></div>";
                              echo "<div class='form-group'><label for='miasto'>Miasto</label>";
                              echo "<input type='text' class='form-control' name='miasto' id='".'90' . $id_w."'  onchange='wojewodztwo22(".'90' . $id_w. ',' .$id_w.")' value={$row['nazwa_miasta']}></div>";
                              ?>
                       <div class="form-group"> <label for="data_rozp_pracy">Data rozpoczęcia pracy</label>
                       <input name="data_rozp_dosw" type="date" value="<?php echo date('Y-m-d',strtotime($dd[0])) ?>"/>

                        </div>
                        <div class='form-group'><label for="data_zak_pracy">Data zakończenia pracy</label>
                                        <input name="data_zak_dosw" type="date" value="<?php echo date('Y-m-d',strtotime($dd[1])) ?>"/>

                        </div>
    <?php
    ?>



                        <div class='form-group'  id="<?php echo '4' . $id_w; ?>"><button id="<?php echo '3' . $id_w; ?>"type="button" class="sub_dosw" value="Edytuj" onclick="editDosw(<?php echo $id_w; ?>)">Edytuj</button>
                            <input type="submit" class="btn btn-primary btn-sm" value="Zapisz"/>
                            <input type="hidden" name="submitted_dosw" value="<?php echo $row[id_doswiadczenia]; ?>"/>
                            <a href="deletedosw.php?del=<?php echo $row[id_doswiadczenia]; ?>">Usuń</a></div>
                    </form>
                        <?php
                        $id_w++;
                    }
                    ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post" id="form_dos_dod">
 <?php
            echo "<input type='hidden' name='wojewodztwo' class='form-control' id='wojewodztwo'/>";
            ?>
                            <?php
                            
                            echo "<div class='form-group dodawanieDoswiadczenie'><label for='firma'>Firma</label>";
                            echo "<input type='text' name='firma' id='firma' class='form-control'></div>";
                            echo "<div class='form-group dodawanieDoswiadczenie'><label for='stanowisko'>Stanowisko</label>";
                            echo "<input type='text' name='stanowisko' id='stanowisko' class='form-control'></div>";
                            echo "<div class='form-group dodawanieDoswiadczenie'><label for='miasto'>Miasto</label>";
                            echo "<input type='text' name='miasto' id='miasto_dod'  class='form-control'></div>";
                            ?>

                    <div class='form-group dodawanieDoswiadczenie'><label for="data_rozp_pracy">Data rozpoczęcia pracy</label>

                              <input name="data_rozp_dosw" type="date" value="<?php echo date("2015-01-01"); ?>"/>

                    </div>
                    <div class='form-group dodawanieDoswiadczenie'>
                        <label for="data_zak_pracy">Data zakończenia pracy</label>
                       <input name="data_zak_dosw" type="date" value="<?php echo date("2015-01-01"); ?>"/>



                <?php
                echo "</div>";
                ?>



                        <div class='form-group'> <button class="btn btn-default btn-sm" type="button" onclick="dodajDoswiadczenie()" id="przyciskDodajDoswiadczenie">Dodaj doświadczenie</button></div>
                        <div class='form-group dodawanieDoswiadczenie'>   <input class='btn btn-success btn-sm' id="sub_dosw_new" type="submit" value="Zapisz"/>
                            <button class='btn btn-warning btn-sm' type="button" onclick="anulujDosw()">Anuluj</button>
                            <input type="hidden" name="submitted_dosw_new" value="1"/></div>
                </form>
                          <?php
                          ?>


                <h3>Umiejętności</h3>

                          <?php
                          $sql = sprintf("SELECT umiejetnosc.id_umiejetnosci, poziom.id_poziomu, poziom.nazwa_poziomu, umiejetnosc.typ_umiejetnosci FROM umiejetnosc_kandydat LEFT JOIN poziom ON umiejetnosc_kandydat.id_poziomu = poziom.id_poziomu LEFT JOIN umiejetnosc on umiejetnosc_kandydat.id_umiejetnosci = umiejetnosc.id_umiejetnosci WHERE umiejetnosc_kandydat.USER_ID = %d", $user->userId);
                          $result = mysql_query($sql);
                          while ($row = mysql_fetch_assoc($result)) {
                              ?>
                <table class='table' id="<?php echo '5' . $id_w; ?>"><tr><td>Umiejętność</td><td><?php
                              echo $row['typ_umiejetnosci'];
                              echo "</td></tr><tr><td>Poziom</td><td>";
                              echo $row['nazwa_poziomu'];
                            
                              ?>
                        </td></tr><tr><td><button type="button" class="btn btn-primary btn-sm" value="Edytuj" id="<?php echo '5' . $id_w; ?>" onclick="editUm(<?php echo $id_w; ?>); hidewyk(<?php echo '50' . $id_w; ?>);">Edytuj</button>     <a href="usun_um.php?del=<?php echo $row[id_umiejetnosci]; ?>"> Usuń</a></td></tr></table>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                          method="post" id="<?php echo '50' . $id_w; ?>" class="form_um">
                              <?php
                              echo "  <div class='form-group'><label for='umiejetnosc'>Umiejetnosc</label>";
                              echo "<input type='text' class='form-control' name='umiejetnosc' id='umiejetnosc' value='{$row[typ_umiejetnosci]}'></div>";
                              echo "<div class='form-group'><label for='poziom'>Poziom</label>";
                              echo "<select class='selectpicker form-control' name='poziom_umiejetnosci' > ";

                              //echo "<option value={$row['nazwa_poziomu']}>{$row['nazwa_poziomu']}";
                              $sql2 = "SELECT * FROM poziom";
                              $result2 = mysql_query($sql2);
                              while ($row2 = mysql_fetch_assoc($result2)) {
                                  $poziom = $row2['nazwa_poziomu'];

                                  echo "<option value= $poziom ";
                                  if ($poziom == $row['nazwa_poziomu'])
                                      echo "selected";
                                  echo ">{$row2['nazwa_poziomu']}</option>";
                              }
                              ?>
                    </select></div> <?php ?>

  
                <div class="form-group">  <td><input type="submit" class=" sub_um btn btn-primary btn-sm" id="<?php echo '6' . $id_w; ?>" value="Zapisz"/>
                <input type="hidden" name="submitted_um"  value="<?php echo $row['id_umiejetnosci']; ?>"/>
                    <a href="usun_um.php?del=<?php echo $row[id_umiejetnosci]; ?>">Usuń</a></div>
                </form>
                        <?php
                        $id_w++;
                    }
                    ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                  method="post" id="form_um_dod">
                <div class="form-group dodawanieUmiejetnosc">
                    <label for="umiejetnoscnowy">Umiejętność</label>
                    <input type="text" name="umiejetnosc" id="umiejetnosc" class='form-control'/></div>
                <div class="form-group dodawanieUmiejetnosc"><label for="poziomnowy">Poziom</label>

                    

                    <?php
                    $sql = "SELECT * FROM poziom";

                    $result = mysql_query($sql);
                    echo "<td><select name='poziom_umiejetnosci' class='form-control'>";
                    while ($row = mysql_fetch_assoc($result)) {

                        echo "<option value={$row['nazwa_poziomu']}>{$row['nazwa_poziomu']}</option>";
                        ?>


    <?php
}
echo '</select>';
?>

                </div>
                     <div class="form-group dodawanieUmiejetnosc" id="sub_um_new"><input type="submit" class='btn btn-success btn-sm' value="Zapisz"/>
                  <button type="button" class='btn btn-warning btn-sm' onclick="anulujUm()">Anuluj</button>
                        <input type="hidden" name="submitted_um_new" value="1"/></div>
                 </form>



         <div class="form-group"><button class="btn btn-default btn-sm" type="button" onclick="dodajUmiejetnosc()" id="przyciskDodajUmiejetnosc">Dodaj umiejętność</button>
                </div>
                
                
                   <h3>Wymagane umiejętności</h3>

                          <?php
                          $sql = sprintf("SELECT * from wymagana_umiejetnosc  LEFT JOIN kandydat_wym_umiejetnosc ON kandydat_wym_umiejetnosc.id_umiejetnosci = wymagana_umiejetnosc.id_umiejetnosci WHERE kandydat_wym_umiejetnosc.USER_ID = %d", $user->userId);
                          $result = mysql_query($sql);
                          $numwym=mysql_num_rows($result);
                          if ($numwym>0){
                          while ($row = mysql_fetch_assoc($result)) {
                              ?>
                <table class='table'><tr><td>Umiejętność</td><td><?php
                              echo $row['nazwa_umiejetnosci'];
                              echo "</td>";
                            
                              ?>
                        <td><a href="usun_um_wym.php?del=<?php echo $row[id_umiejetnosci]; ?>"> Usuń</a></td></tr></table>
                          <?php }}
                          else{
                             echo' Brak wymaganych umiejętności. ';
                          }?>
                  <div class="form-group"><button class="btn btn-default btn-sm" type="button" onclick="dodajWymUmiejetnosc()" id="przyciskDodajWymUmiejetnosc">Dodaj wymaganą umiejętność</button>
                </div>
                 <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                      method="post" id="form_um_wym">
                  <div class="form-group">
                <label>Wymagane umiejętności</label>

                <input id="ms" class="form-control" name="umiejetnosci[]" />
            </div>
                

                <div class="form-group dodawanieWym" id="sub_um_wym"><input type="submit" class='btn btn-success btn-sm' value="Zapisz"/>
                  <button type="button" class='btn btn-warning btn-sm' onclick="anulujUmWym()">Anuluj</button>
                        <input type="hidden" name="submitted_um_wym" value="1"/></div>
            </form>
   </div>


            
        <?php
        $form = ob_get_clean();


        if (!isset($_POST['submitted']) && !isset($_POST['submitted_name']) && !isset($_POST['submitted_educ']) && !isset($_POST['submitted_dosw']) && !isset($_POST['submitted_um']) && !isset($_POST['submitted_educ_usun']) && !isset($_POST['submitted_educ_new']) && !isset($_POST['submitted_um_new']) && !isset($_POST['submitted_dosw_new']) && !isset($_POST['submitted_um_wym'])) {
            $GLOBALS['TEMPLATE']['content'] = $form;
        }

        else {
            if (isset($_POST['submitted_educ_usun'])) {
                $id_w = $_POST[$id_wyksztalcenia];
                $sql = sprintf("DELETE FROM wyksztalcenie_kandydat WHERE id_wyksztalcenia=%d AND USER_ID=%d", $id_w, $user->userId);
            }


            if (isset($_POST['submitted_name'])) {

                $user->imie = $_POST['imie'];
                $user->nazwisko = $_POST['nazwisko'];
                $data_ur=$_POST['data_ur'];

                
                $user->data_ur = $data_ur;
                $user->save();




                 header('Location: profil.php');
            }

            if (isset($_POST['submitted_educ'])) {

                $id_wyksz = $_POST['submitted_educ'];
                $placowka = $_POST['placowka'];
                $kierunek = $_POST['kierunek'];
                
                
                $data_rozp = $_POST['data_rozp_nauki'];
                $data_zak = $_POST['data_zak_nauki'];
                $sql_plac = sprintf('SELECT id_szkoly from szkola WHERE nazwa_szkoly="%s"', mysql_real_escape_string($placowka, $GLOBALS['DB']));
                $plac_query = mysql_query($sql_plac);
             
                $numrowsplac = mysql_num_rows($plac_query);
        
                $sql_kier = sprintf('SELECT id_kierunku from kierunek WHERE nazwa_kierunku="%s"', mysql_real_escape_string($kierunek, $GLOBALS['DB']));
                $kier_query = mysql_query($sql_kier);
                $numrowskier = mysql_num_rows($kier_query);
              


                $sql_dr = sprintf('SELECT id_wyksztalcenia from wyksztalcenie WHERE data_rozpoczecia="%s"', $data_rozp);
                $dr = mysql_query($sql_dr);
                $numrowsdr = mysql_num_rows($dr);

                $sql_dz = sprintf('SELECT id_wyksztalcenia from wyksztalcenie WHERE data_zakonczenia="%s"', $data_zak);
                $dz = mysql_query($sql_dz);
                $numrowsdz = mysql_num_rows($dz);

                if ($numrowsplac == 0 || $numrowskier == 0 || $numrowsdr == 0 || $numrowsdz == 0) {
                    if ($numrowsplac == 0) {
                        $sql6 = sprintf('INSERT INTO szkola (nazwa_szkoly) VALUES ("%s")', mysql_real_escape_string($placowka, $GLOBALS['DB']));
                        $ir = mysql_query($sql6);
                       
                    }

                    if ($numrowskier == 0) {
                        $sql67 = sprintf('INSERT INTO kierunek (nazwa_kierunku) VALUES ("%s")', mysql_real_escape_string($kierunek, $GLOBALS['DB']));
                        $ir2 = mysql_query($sql67);
                      
                    }

                    $sql8 = sprintf('INSERT INTO wyksztalcenie (id_szkoly , id_kierunku, data_rozpoczecia, data_zakonczenia) VALUES'
                            . ' ((select id_szkoly from szkola where nazwa_szkoly = "%s"), '
                            . '(select id_kierunku from kierunek where nazwa_kierunku = "%s"),"%s","%s")', $placowka, $kierunek, $data_rozp, $data_zak);
                    $r = mysql_query($sql8);
         
                    
                    $r = mysql_insert_id();
                 

                    $sql = sprintf("UPDATE wyksztalcenie_kandydat SET wyksztalcenie_kandydat.id_wyksztalcenia=%d WHERE id_wyksztalcenia=%d AND USER_ID=%d", $r, $id_wyksz, $user->userId);
                    $sw = mysql_query($sql);
                } else {

                    while ($row = mysql_fetch_assoc($kier_query)) {
                        $id_kierunku = $row['id_kierunku'];
                    }

                    while ($row = mysql_fetch_assoc($plac_query)) {
                        $id_szkoly = $row['id_szkoly'];
                    }
                 
                    $sql01 = sprintf('select id_wyksztalcenia from wyksztalcenie where id_kierunku = %d AND id_szkoly=%d AND '
                            . 'data_rozpoczecia = "%s" AND data_zakonczenia = "%s"', $id_kierunku, $id_szkoly, $data_rozp, $data_zak);
                    $query01 = mysql_query($sql01);


                
                    $numrows01 = mysql_num_rows($query01);
                  

                    if ($numrows01 <= 0) {
                        $sqlww = sprintf('INSERT INTO wyksztalcenie (id_szkoly, id_kierunku, data_rozpoczecia, data_zakonczenia) '
                                . 'VALUES (%d, %d, "%s", "%s")', $id_szkoly, $id_kierunku, $data_rozp, $data_zak);
                        $query = mysql_query($sqlww);
                        $rr = mysql_insert_id();
                        $sqlupw = sprintf("UPDATE wyksztalcenie_kandydat SET wyksztalcenie_kandydat.id_wyksztalcenia=%d WHERE id_wyksztalcenia=%d AND USER_ID=%d", $rr, $id_wyksz, $user->userId);
                        $sw = mysql_query($sqluw);
                        
                    } else {
                        
                        while ($row = mysql_fetch_assoc($query01)) {
                            $id_wyk2 = $row[id_wyksztalcenia];
                        }
                        $sqler = sprintf("UPDATE wyksztalcenie_kandydat SET wyksztalcenie_kandydat.id_wyksztalcenia=%d WHERE id_wyksztalcenia=%d AND USER_ID=%d",$id_wyk2, $id_wyksz,  $user->userId);
                        $sw = mysql_query($sqler);
                   
                       
                    }
                }
 header('Location: profil.php');

                    
            }

            if (isset($_POST['submitted_dosw'])) {

                $id_dosw = $_POST['submitted_dosw'];
                $firma = $_POST['firma'];
                $stanowisko = $_POST['stanowisko'];
                $miasto = $_POST['miasto'];
           $wojewodztwo = $_POST['wojewodztwo'];
          
                $data_rozp = $_POST['data_rozp_dosw'];
                $data_zak = $_POST['data_zak_dosw'];
                $sql_firma = sprintf('SELECT id_firmy from firma WHERE nazwa_firmy="%s"', mysql_real_escape_string($firma, $GLOBALS['DB']));
             
                $firma_query = mysql_query($sql_firma);
                $numrowsfirma = mysql_num_rows($firma_query);
                $sql_stan = sprintf('SELECT id_stanowiska from stanowisko WHERE nazwa_stanowiska="%s"', mysql_real_escape_string($stanowisko, $GLOBALS['DB']));
                $stan_query = mysql_query($sql_stan);
                $numrowsstan = mysql_num_rows($stan_query);
                $sql_miasto = sprintf('SELECT id_miasta from miasto WHERE nazwa_miasta="%s"', mysql_real_escape_string($miasto, $GLOBALS['DB']));
                $miasto_query = mysql_query($sql_miasto);
                $numrowsmiasto = mysql_num_rows($miasto_query);
            



                $sql_dr = sprintf('SELECT id_doswiadczenia from doswiadczenie WHERE data_rozpoczecia="%s"', $data_rozp);
                $dr = mysql_query($sql_dr);
                $numrowsdr = mysql_num_rows($dr);

                $sql_dz = sprintf('SELECT id_doswiadczenia from doswiadczenie WHERE data_zakonczenia="%s"', $data_zak);
                $dz = mysql_query($sql_dz);
                $numrowsdz = mysql_num_rows($dz);

                if ($numrowsfirma == 0 || $numrowsstan == 0 || $numrowsdr == 0 || $numrowsmiasto == 0 || $numrowsdz == 0) {
                    if ($numrowsfirma == 0) {
                        $sql6 = sprintf('INSERT INTO firma (nazwa_firmy) VALUES ("%s")', mysql_real_escape_string($firma, $GLOBALS['DB']));
                        $ir = mysql_query($sql6);
                        
                    }

                    if ($numrowsstan == 0) {
                        $sql67 = sprintf('INSERT INTO stanowisko (nazwa_stanowiska) VALUES ("%s")', mysql_real_escape_string($stanowisko, $GLOBALS['DB']));
                        $ir2 = mysql_query($sql67);
                       
                     
                    }

                    if ($numrowsmiasto == 0) {
                        $sql=sprintf('SELECT id_wojewodztwa from wojewodztwo where nazwa_wojewodztwa="%s"', $wojewodztwo);
                        $quety=mysql_query($sql);
                    
                        $r=mysql_fetch_array($quety);
                        $w=$r['id_wojewodztwa'];
                        $sql67 = sprintf('INSERT INTO miasto (nazwa_miasta, id_wojewodztwa) VALUES ("%s", %d)', mysql_real_escape_string($miasto, $GLOBALS['DB']), $w);
                        $ir2 = mysql_query($sql67);
                       
                       
                    }

                    $sql8 = sprintf('INSERT INTO doswiadczenie (id_firmy , id_stanowiska, id_miasta) '
                            . ' select firma.id_firmy, stanowisko.id_stanowiska, miasto.id_miasta FROM firma, stanowisko, miasto'
                            . ' where firma.nazwa_firmy = "%s" AND stanowisko.nazwa_stanowiska="%s" AND miasto.nazwa_miasta="%s"', mysql_real_escape_string($firma, $GLOBALS['DB']), mysql_real_escape_string($stanowisko, $GLOBALS['DB']), mysql_real_escape_string($miasto, $GLOBALS['DB']));
                    $r = mysql_query($sql8);
                    $rr = mysql_insert_id();
                
                    $dd = mysql_fetch_array($ee);

                    
                
                    $sql = sprintf('UPDATE doswiadczenie SET data_rozpoczecia="%s", data_zakonczenia="%s" WHERE id_doswiadczenia=%d', $data_rozp, $data_zak, $rr);
                    $query = mysql_query($sql);

                    $sql = sprintf("UPDATE doswiadczenie_kandydat SET doswiadczenie_kandydat.id_doswiadczenia=%d WHERE id_doswiadczenia=%d AND USER_ID=%d", $rr, $id_dosw, $user->userId);
                    $sw = mysql_query($sql);
                 echo $sql;
                } else {

                    while ($row = mysql_fetch_assoc($firma_query)) {
                        $id_firmy = $row[id_firmy];
                    }

                    while ($row = mysql_fetch_assoc($stan_query)) {
                        $id_stanowiska = $row[id_stanowiska];
                    }

                    while ($row = mysql_fetch_assoc($miasto_query)) {
                        $id_miasta = $row[id_miasta];
                    }

                    $sqlkw = sprintf('select id_doswiadczenia from doswiadczenie where id_firmy = %d AND id_stanowiska=%d AND id_miasta=%d AND '
                            . ' data_rozpoczecia = "%s" AND data_zakonczenia = "%s"', $id_firmy, $id_stanowiska, $id_miasta, $data_rozp, $data_zak);
                    $querykw = mysql_query($sqlkw);
                  
                    $numrowskw = mysql_num_rows($querykw);
                   
                 
                    if ($numrowskw == 0) {
                        $sqlww = sprintf('INSERT INTO doswiadczenie (id_firmy, id_stanowiska, id_miasta, data_rozpoczecia, data_zakonczenia)'
                                . 'VALUES (%d, %d, %d, "%s", "%s")', $id_firmy, $id_stanowiska, $id_miasta, $data_rozp, $data_zak);
                        $query = mysql_query($sqlww);
                       
                        $rr = mysql_insert_id();
                     
                        $sqlupw = sprintf("UPDATE doswiadczenie_kandydat SET doswiadczenie_kandydat.id_doswiadczenia=%d WHERE id_doswiadczenia=%d AND USER_ID=%d", $rr, $id_dosw, $user->userId);
                        $sw = mysql_query($sqluw);
                     
                        
                    } else {
                       
                        while ($row = mysql_fetch_assoc($querykw)) {
                            $id_d2 = $row[id_doswiadczenia];
                        }
                        
                        $sqler = sprintf("UPDATE doswiadczenie_kandydat SET doswiadczenie_kandydat.id_doswiadczenia=%d WHERE id_doswiadczenia=%d AND USER_ID=%d",  $id_d2,$id_dosw, $user->userId);
                        $sw = mysql_query($sqler);
                       
                
                    }
                }
                 header('Location: profil.php');
            }

            if (isset($_POST['submitted_dosw_new'])) {


                $firma = $_POST['firma'];
                $stanowisko = $_POST['stanowisko'];
                $miasto = $_POST['miasto'];
                $wojewodztwo=$_POST['wojewodztwo'];
                $data_rozp = $_POST['data_rozp_dosw'];
                $data_zak = $_POST['data_zak_dosw'];
                $sql_firma = sprintf('SELECT id_firmy from firma WHERE nazwa_firmy="%s"', mysql_real_escape_string($firma, $GLOBALS['DB']));
                $firma_query = mysql_query($sql_firma);
                $numrowsfirma = mysql_num_rows($firma_query);
                $sql_stan = sprintf('SELECT id_stanowiska from stanowisko WHERE nazwa_stanowiska="%s"', mysql_real_escape_string($stanowisko, $GLOBALS['DB']));
                $stan_query = mysql_query($sql_stan);
                $numrowsstan = mysql_num_rows($stan_query);
                $sql_miasto = sprintf('SELECT id_miasta from miasto WHERE nazwa_miasta="%s"', mysql_real_escape_string($miasto, $GLOBALS['DB']));
                $miasto_query = mysql_query($sql_miasto);
                $numrowsmiasto = mysql_num_rows($miasto_query);
               



                $sql_dr = sprintf('SELECT id_doswiadczenia from doswiadczenie WHERE data_rozpoczecia="%s"', $data_rozp);
                $dr = mysql_query($sql_dr);
                $numrowsdr = mysql_num_rows($dr);

                $sql_dz = sprintf('SELECT id_doswiadczenia from doswiadczenie WHERE data_zakonczenia="%s"', $data_zak);
                $dz = mysql_query($sql_dz);
                $numrowsdz = mysql_num_rows($dz);

                if ($numrowsfirma == 0 || $numrowsstan == 0 || $numrowsdr == 0 || $numrowsmiasto == 0 || $numrowsdz == 0) {
                    if ($numrowsfirma == 0) {
                        $sql6 = sprintf('INSERT INTO firma (nazwa_firmy) VALUES ("%s")', mysql_real_escape_string($firma, $GLOBALS['DB']));
                        $ir = mysql_query($sql6);
                       
                    }

                    if ($numrowsmiasto == 0) {
                        $sql=sprintf('SELECT id_wojewodztwa from wojewodztwo where nazwa_wojewodztwa="%s"', $wojewodztwo);
                        $query=mysql_query($sql);
                        while ($row = mysql_fetch_assoc($query)) {
                        $woj = $row[id_wojewodztwa];
                    }
              
                        $sql6 = sprintf('INSERT INTO miasto (nazwa_miasta, id_wojewodztwa) VALUES ("%s", %d)', mysql_real_escape_string($miasto, $GLOBALS['DB']), $woj);
                        $ir = mysql_query($sql6);
                       
                       
                    }

                    if ($numrowsstan == 0) {
                        $sql67 = sprintf('INSERT INTO stanowisko (nazwa_stanowiska) VALUES ("%s")', mysql_real_escape_string($stanowisko, $GLOBALS['DB']));
                        $ir2 = mysql_query($sql67);
                        
                    }
                 
                    $sql8 = sprintf('INSERT INTO doswiadczenie (id_firmy , id_stanowiska, id_miasta) '
                            . ' select firma.id_firmy, stanowisko.id_stanowiska, miasto.id_miasta FROM firma, stanowisko, miasto'
                            . ' where firma.nazwa_firmy = "%s" AND stanowisko.nazwa_stanowiska="%s" AND miasto.nazwa_miasta="%s"', mysql_real_escape_string($firma, $GLOBALS['DB']), mysql_real_escape_string($stanowisko, $GLOBALS['DB']), mysql_real_escape_string($miasto, $GLOBALS['DB']));
                    $rr = mysql_query($sql8);
                    


                    $r = mysql_insert_id();

                    
                    $sql = sprintf('UPDATE doswiadczenie SET data_rozpoczecia="%s", data_zakonczenia="%s" WHERE id_doswiadczenia=%d', $data_rozp, $data_zak, $r);
                    $query = mysql_query($sql);
                  
                    $sql = sprintf("INSERT INTO doswiadczenie_kandydat (id_doswiadczenia, USER_ID) VALUES (%d, %d)", $r, $user->userId);
                    $sw = mysql_query($sql);
                } else {

                    while ($row = mysql_fetch_assoc($firma_query)) {
                        $id_firmy = $row[id_firmy];
                    }

                    while ($row = mysql_fetch_assoc($stan_query)) {
                        $id_stanowiska = $row[id_stanowiska];
                    }

                    while ($row = mysql_fetch_assoc($miasto_query)) {
                        $id_miasta = $row[id_miasta];
                    }

                    $sqlkw = sprintf('select id_doswiadczenia from doswiadczenie where id_firmy = %d AND id_stanowiska=%d AND id_miasta=%d AND'
                            . ' data_rozpoczecia = "%s" AND data_zakonczenia = "%s"', $id_firmy, $id_stanowiska, $id_miasta, $data_rozp, $data_zak);
                    $querykw = mysql_query($sqlkw);
                    $numrowskw = mysql_num_rows($querykw);
                 

                    if ($numrowskw == 0) {
                        $sqlww = sprintf('INSERT INTO doswiadczenie (id_firmy, id_stanowiska, id_miasta, data_rozpoczecia, data_zakonczenia)'
                                . 'VALUES (%d, %d, %d, "%s", "%s")', $id_firmy, $id_stanowiska, $id_miasta, $data_rozp, $data_zak);
                        $query = mysql_query($sqlww);
                       
                        $rr = mysql_insert_id();
                        $sqlupw = sprintf("INSERT INTO doswiadczenie_kandydat (id_doswiadczenia, USER_ID) VALUES (%d, %d)", $rr, $user->userId);
                        $sw = mysql_query($sqluw);
                    
                    } else {
                    
                        while ($row = mysql_fetch_assoc($querykw)) {
                            $id_d2 = $row[id_doswiadczenia];
                        }
                        $sqler = sprintf("INSERT INTO doswiadczenie_kandydat (id_doswiadczenia, USER_ID) VALUES (%d, %d)", $id_d2, $user->userId);
                        $sw = mysql_query($sqler);
                        
                       
                    }
                }
                 header('Location: profil.php');
            }

            if (isset($_POST['submitted_um'])) {
           
                $id_um = $_POST['submitted_um'];
                $poziom = $_POST['poziom_umiejetnosci'];
                $um = $_POST['umiejetnosc'];
               
                
                $sql_um = sprintf('SELECT id_umiejetnosci from umiejetnosc WHERE typ_umiejetnosci="%s"', mysql_real_escape_string($um, $GLOBALS['DB']));
                $um_query = mysql_query($sql_um);
                $numrowsum = mysql_num_rows($um_query);
                $sql_poz = sprintf('SELECT id_poziomu from poziom WHERE nazwa_poziomu="%s"', $poziom);
                $poz_query = mysql_query($sql_poz);

                while ($row = mysql_fetch_array($poz_query)) {
                   
                    $id_poz = $row[id_poziomu];
                }
             
                $numrowspoz = mysql_num_rows($poz_query);



                if ($numrowsum == 0) {

                    $sql6 = sprintf('INSERT INTO umiejetnosc (typ_umiejetnosci) VALUES ("%s")', mysql_real_escape_string($um, $GLOBALS['DB']));
                    $ir = mysql_query($sql6);




                    $r = mysql_insert_id();


                    $sql = sprintf("UPDATE umiejetnosc_kandydat SET umiejetnosc_kandydat.id_umiejetnosci=%d, umiejetnosc_kandydat.id_poziomu = %d WHERE id_umiejetnosci=%d AND USER_ID=%d", $r, $id_poz, $id_um, $user->userId);
                    $sw = mysql_query($sql);
                   
                } else {


                 
                    while ($row = mysql_fetch_assoc($um_query)) {
                        $id_um2 = $row[id_umiejetnosci];
                    }
                    
                     $sql=sprintf("SELECT * from umiejetnosc_kandydat where id_umiejetnosci=%d AND USER_ID=%d", $id_um2, $user->userId);
                    $query=mysql_query($sql);
                    $num=mysql_num_rows($query);
                    
                    if($num <= 0){

                    $sqler = sprintf("UPDATE umiejetnosc_kandydat SET umiejetnosc_kandydat.id_umiejetnosci=%d, umiejetnosc_kandydat.id_poziomu=%d WHERE id_umiejetnosci=%d AND USER_ID=%d", $id_um2, $id_poz, $id_um, $user->userId);
                    $sw = mysql_query($sqler);
                    
                    }
                    else{
                        
                    }
                }



                  header('Location: profil.php');
            }

            if (isset($_POST['submitted_um_new'])) {

                $poziom = $_POST['poziom_umiejetnosci'];
                $um = $_POST['umiejetnosc'];
              
                $sql_um = sprintf('SELECT id_umiejetnosci from umiejetnosc WHERE typ_umiejetnosci="%s"', mysql_real_escape_string($um, $GLOBALS['DB']));
                $um_query = mysql_query($sql_um);
                $numrowsum = mysql_num_rows($um_query);
                $sql_poz = sprintf('SELECT id_poziomu from poziom WHERE nazwa_poziomu="%s"', $poziom);
                $poz_query = mysql_query($sql_poz);

                while ($row = mysql_fetch_array($poz_query)) {
                    
                    $id_poz = $row[id_poziomu];
                }
         
                $numrowspoz = mysql_num_rows($poz_query);



                if ($numrowsum == 0) {

                    $sql6 = sprintf('INSERT INTO umiejetnosc (typ_umiejetnosci) VALUES ("%s")', mysql_real_escape_string($um, $GLOBALS['DB']));
                    $ir = mysql_query($sql6);




                    $r = mysql_insert_id();
                   

                    $sql = sprintf("INSERT INTO umiejetnosc_kandydat (USER_ID, id_umiejetnosci, id_poziomu) VALUES (%d, %d, %d)", $user->userId, $r, $id_poz);
                    $sw = mysql_query($sql);
                   header('Location: profil.php');
                } else {

                

                    while ($row = mysql_fetch_assoc($um_query)) {
                        $id_um2 = $row[id_umiejetnosci];
                    }
                    $sql=sprintf("SELECT * from umiejetnosc_kandydat where id_umiejetnosci=%d AND USER_ID=%d", $id_um2, $user->userId);
                    $query=mysql_query($sql);
                    $num=mysql_num_rows($query);
                    
                    if($num <= 0){
                    $sqler = sprintf("INSERT INTO umiejetnosc_kandydat (USER_ID, id_umiejetnosci, id_poziomu) VALUES (%d, %d, %d)", $user->userId, $id_um2, $id_poz);
                    $sw = mysql_query($sqler);
                  header('Location: profil.php');
                    }
                    else{
                         $GLOBALS['TEMPLATE']['content'] .='<div class="container col-ms-6 col-xs-12">Dodałeś już taką umiejętność.</br> Powrót do <a href="profil.php">profilu</a>.</div>';
                    }
                 
                 
                }



               
            }
            if (isset($_POST['submitted_educ_new'])) {

                $id_wyksz = $_POST['submitted_educ'];
                $placowka = $_POST['placowka'];
                $kierunek = $_POST['kierunek'];
               
                
             
                $data_rozp = $_POST['data_rozp_nauki'];
                $data_zak = $_POST['data_zak_nauki'];
             
             
                $sql_plac = sprintf('SELECT id_szkoly from szkola WHERE nazwa_szkoly="%s"',  mysql_real_escape_string($placowka, $GLOBALS['DB']));
                $plac_query = mysql_query($sql_plac);
              
                $numrowsplac = mysql_num_rows($plac_query);
                $sql_kier = sprintf('SELECT id_kierunku from kierunek WHERE nazwa_kierunku="%s"', mysql_real_escape_string($kierunek, $GLOBALS['DB']));
                $kier_query = mysql_query($sql_kier);
                
                $numrowskier = mysql_num_rows($kier_query);


                $sql_dr = sprintf('SELECT id_wyksztalcenia from wyksztalcenie WHERE data_rozpoczecia="%s"', $data_rozp);
                $dr = mysql_query($sql_dr);
                $numrowsdr = mysql_num_rows($dr);

                $sql_dz = sprintf('SELECT id_wyksztalcenia from wyksztalcenie WHERE data_zakonczenia="%s"', $data_zak);
                $dz = mysql_query($sql_dz);
                $numrowsdz = mysql_num_rows($dz);

                if ($numrowsplac == 0 || $numrowskier == 0 || $numrowsdr == 0 || $numrowsdz == 0) {
                   
                    if ($numrowsplac == 0) {
                        $sql6 = sprintf('INSERT INTO szkola (nazwa_szkoly) VALUES ("%s")',  mysql_real_escape_string($placowka, $GLOBALS['DB']));
                        $ir = mysql_query($sql6);
                      
                    }

                    if ($numrowskier == 0) {
                        $sql67 = sprintf('INSERT INTO kierunek (nazwa_kierunku) VALUES ("%s")', mysql_real_escape_string($kierunek, $GLOBALS['DB']));
                        $ir2 = mysql_query($sql67);
                        
                        
                    }

                    $sql8 = sprintf('INSERT INTO wyksztalcenie (id_szkoly , id_kierunku, data_rozpoczecia, data_zakonczenia) VALUES'
                            . ' ((select id_szkoly from szkola where nazwa_szkoly = "%s"), '
                            . '(select id_kierunku from kierunek where nazwa_kierunku = "%s"),"%s","%s")', mysql_real_escape_string($placowka, $GLOBALS['DB']), mysql_real_escape_string($kierunek, $GLOBALS['DB']), $data_rozp, $data_zak);
                    $m = mysql_query($sql8);

                    $r = mysql_insert_id();
                   

                    $sql = sprintf("INSERT INTO wyksztalcenie_kandydat (id_wyksztalcenia, USER_ID) VALUES (%d,%d)", $r, $user->userId);
                   
                    $sw = mysql_query($sql);
                } else {

                    while ($row = mysql_fetch_assoc($kier_query)) {
                        $id_kierunku = $row[id_kierunku];
                    }

                    while ($row = mysql_fetch_assoc($plac_query)) {
                        $id_szkoly = $row[id_szkoly];
                    }
                
                    $sql01 = sprintf('select id_wyksztalcenia from wyksztalcenie where id_kierunku = %d AND id_szkoly=%d AND '
                            . 'data_rozpoczecia = "%s" AND data_zakonczenia = "%s"', $id_kierunku, $id_szkoly, $data_rozp, $data_zak);
                    $query01 = mysql_query($sql01);

                  
                    
                    $numrows01 = mysql_num_rows($query01);
                  

                    if ($numrows01 <= 0) {
                        $sqlww = sprintf('INSERT INTO wyksztalcenie (id_szkoly, id_kierunku, data_rozpoczecia, data_zakonczenia)'
                                . 'VALUES (%d, %d, "%s", "%s")', $id_szkoly, $id_kierunku, $data_rozp, $data_zak);
                        $query = mysql_query($sqlww);
                    echo $sqlww;
                        $rr = mysql_insert_id();
                        $sqlupw = sprintf("INSERT INTO wyksztalcenie_kandydat (id_wyksztalcenia, USER_ID) VALUES (%d, %d)", $rr, $user->userId);
                        $sw = mysql_query($sqluw);
                       
                    } else {
                     
                        while ($row = mysql_fetch_assoc($query01)) {
                            $id_wyk2 = $row[id_wyksztalcenia];
                        }
                       
                        
                    
                        $sqler = sprintf("INSERT INTO wyksztalcenie_kandydat (id_wyksztalcenia, USER_ID) VALUES (%d, %d)", $id_wyk2, $user->userId);
                        $sw = mysql_query($sqler);
             
                    }
                }


              header('Location: profil.php');
            }
               if (isset($_POST['submitted_um_wym'])) {
             $countries = isset($_POST['umiejetnosci']) ? $_POST['umiejetnosci'] : array();
     
        
               foreach($countries as $idkd){
         
            $sql=sprintf('SELECT * from wymagana_umiejetnosc where id_umiejetnosci=%d', $idkd);
            $query=mysql_query($sql);
            $num=mysql_num_rows($query);
      
       
      $sql=sprintf('INSERT INTO kandydat_wym_umiejetnosc (id_umiejetnosci, USER_ID) VALUES (%d, %d)', $idkd, $user->userId);
      $query=mysql_query($sql);
  
         }    
               }
        }


        include '../templates/template-page.php';
        ?>
            <script src="../lib/magic/magicsuggest.js"></script>
       <script>
            $(document).ready(function () {

                $(".dodawanieUmiejetnosc").hide();
                $(".dodawanieDoswiadczenie").hide();
                $(".dodawanieEdukacja").hide();
                $("#sub_name").hide();
                $("#dodaj_wym_umiejetnosc").hide();
                  $("#form_um_wym").hide();
                  $("#reg").hide();
                $(".sub_educ").hide();
                $(".form_wyk").hide();
                $(".form_dosw").hide();
                 $(".form_um").hide();
                $(".sub_dosw").hide();
                $(".sub_um").hide();
               
               

            });
            
            function dodajWymUmiejetnosc(){
                $("#form_um_wym").show();
                $("#przyciskDodajWymUmiejetnosc").hide();
            }
 $('#reg').validate({
    rules: {
      imie: {
        required: true,
        minlength: 2
      },
      nazwisko: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
  
              $('#form_wyk').validate({
    rules: {
      placowka: {
        required: true,
        minlength: 2
      },
      kierunek: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
  
  $('#form_wyk_dod').validate({
    rules: {
      placowka: {
        required: true,
        minlength: 2
      },
      kierunek: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
      //label.text('OK!').addClass('valid');
    }
  });
  
  $('#form_dos').validate({
    rules: {
      firma: {
        required: true,
        minlength: 2
      },
      stanowisko: {
        required: true,
        minlength: 2
    },
     miasto: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
      //label.text('OK!').addClass('valid');
    }
  });
  
    $('#form_dos_dod').validate({
    rules: {
      firma: {
        required: true,
        minlength: 2
      },
      stanowisko: {
        required: true,
        minlength: 2
    },
     miasto: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
      //label.text('OK!').addClass('valid');
    }
  });
  
   for (i = 0; i < 20; i++) {
    $('#10'+i).validate({
        rules: {
            placowka: {
        required: true,
        minlength: 2
      },
      kierunek: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
    //  label.text('OK!').addClass('valid');
    }
  });
        }
  for (i = 0; i < 20; i++) {
    $('#20'+i).validate({
        rules: {
      firma: {
        required: true,
        minlength: 2
      },
      stanowisko: {
        required: true,
        minlength: 2
    },
     miasto: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
    }
  for (i = 0; i < 20; i++) {
    $('#50'+i).validate({
    rules: {
    
     umiejetnosc: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
  
    $('#form_um_dod').validate({
    rules: {
   
     umiejetnosc: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
     // label.text('OK!').addClass('valid');
    }
  });
  }
  jQuery.extend(jQuery.validator.messages, {
    required: 'To pole jest wymagane',
    minlength: $.validator.format("Wprowadź co najmniej {0} znaków.")
  });


function anulujUmWym(){
     $("#form_um_wym").hide();
     $("#przyciskDodajWymUmiejetnosc").show();
}
            function anulujUm() {
                $(".dodawanieUmiejetnosc").hide();
                $("#przyciskDodajUmiejetnosc").show();
            }

            function anulujDosw() {
                $(".dodawanieDoswiadczenie").hide();
                $("#przyciskDodajDoswiadczenie").show();
            }

            function anulujEduc() {
                $(".dodawanieEdukacja").hide();
                 $("#przyciskDodajEdukacje").show();
            }


            function dodajEdukacje() {
                $(".dodawanieEdukacja").show();

                $("#sub_educ").show();
                $("#przyciskDodajEdukacje").hide();
            }
            ;

            function dodajDoswiadczenie() {
                $(".dodawanieDoswiadczenie").show();

                $("#sub_dosw").show();

                $("#przyciskDodajDoswiadczenie").hide();
            }
            ;

            function editName() {
                $("#sub_name_edit").hide();
                $("#name").hide();
                $("#reg").show();
                $("#sub_name").show();
                $("#nazwisko").removeClass("inform");
                $("#imie").removeClass("inform");
                $("#data_ur").removeClass("inform");
            }
            ;
            function hidewyk(id_educ) {
            var ed = document.getElementById(id_educ);
                $(ed).show();
            }
            function editEduc(id_educ) {
                var w = 1;
                var c = id_educ.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).hide();
              
                saveEduc(id_educ);

            }
            ;
            
                function saveEduc(id_educ) {
                var w = 2;
                var c = id_educ.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).show();
                $(ed).validate({
    rules: {
      placowka: {
        required: true,
        minlength: 2
      },
      kierunek: {
        required: true,
        minlength: 2
    }
    },
    success: function(label) {
      label.text('OK!').addClass('valid');
    }
  });

            }
            ; 

            

            function editDosw(id_dosw) {
                var w = 3;
                var c = id_dosw.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).hide();
                saveDosw(id_dosw);

            }
            ;

            function saveDosw(id_dosw) {
                var w = 4;
                var c = id_dosw.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).show();

            }
            ;

            function editUm(id_um) {
                var w = 5;
                var c = id_um.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).hide();
                saveUm(id_um);
                

            }
            ;

            function saveUm(id_um) {
                var w = 6;
                var c = id_um.valueOf();
                var e = "" + w + c;
                var ed = document.getElementById(e);
                $(ed).show();

            }
            ;

            function dodaneD() {
                $(".dodawanieDoswiadczenie").hide();
                $("#przyciskDodajDoswiadczenie").show();
            }
            ;
            function dodajUmiejetnosc() {
                $(".dodawanieUmiejetnosc").show();
                $("#przyciskDodajUmiejetnosc").hide();
            }
            ;

            function dodanaUmiejetnosc() {
                $(".dodawanieUmiejetnosc").hide();
                $("#przyciskDodajUmiejetnosc").show();
            }
            ;

        </script>
            <script>
                 $( '#city' ).focusout(function( event ) {
 getVoivodeship();
});
    	getVoivodeship = function() {
					var city = $("#miasto").val();
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
				};
         
			    $(function() {

        var ms=  $('#ms').magicSuggest({
            data: 'um.php',
            valueField: 'id_umiejetnosci',
            displayField: 'nazwa_umiejetnosci',
            allowFreeEntries: 'false',
            placeholder: 'Wprowadź umiejętność lub kliknij tutaj',
            mode: 'remote',
            renderer: function (data) {
                return '<div class="umiejetnosci">' +
                        '<div class="name">' + data.nazwa_umiejetnosci + '</div>' +
                        '<div style="clear:both;"></div>' +
                        '</div>';
            },
            resultAsString: true,
            selectionRenderer: function (data) {
                return '<div class="name">' + data.nazwa_umiejetnosci + '</div>';
            }

        });
    });
	 $('#miasto_dod').on('change',(function() {
            var city = $("#miasto_dod").val();
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
       
    function wojewodztwo22(id_woj, r) {
        var c=document.getElementById(id_woj);
            var city =   $(c).val();
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
                        
                        $('#70'+r).val(woj);
                        console.log($("#70"+r).val());
                        console.log("#70"+r);
                    }
                });
            }});
        };//}
    </script>