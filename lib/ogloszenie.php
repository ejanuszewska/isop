<?php

class Ogloszenie {

    private $oglid;     
    private $fields;  



    public function __construct() {
        $this->oglid = null;
        $this->fields = array('idStanowiska' => '',
            'idBranzy' => '',
            'idMiasta' => '',
            'opisPracy' => '',
            'wymagania' => '',
            'pensjaMin' => '',
            'pensjaMax' => '',
            'idOkresuAplikacji' => '',
            'id_firmy' => '');
    }

   
    public function __get($field) {
        if ($field == 'oglId') {
            return $this->oglid;
        } else {
            return $this->fields[$field];
        }
    }

 
    public function __set($field, $value) {
        if (array_key_exists($field, $this->fields)) {
            $this->fields[$field] = $value;
        }
    }

    public static function getById($oglid) {
        $o = new Ogloszenie();

        $query = sprintf('SELECT id_ogloszenia, id_stanowiska, id_branzy, id_miasta, opis_pracy, wymagania, pensja_minimalna, pensja_maksymalna, id_okresu_aplikacji, id_firmy FROM ogloszenie WHERE id_ogloszenia = %d', $oglid);
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result)) {
            $row = mysql_fetch_assoc($result);
            $o->idStanowiska = $row['id_stanowiska'];
            $o->idBranzy = $row['id_branzy'];
            $o->idMiasta = $row['id_miasta'];
            $o->opisPracy = $row['opis_pracy'];
            $o->wymagania = $row['wymagania'];
            $o->pensjaMin = $row['pensja_minimalna'];
            $o->pensjaMax = $row['pensja_maksymalna'];
            $o->idOkresuAplikacji = $row['id_okresu_aplikacji'];
            $o->id_firmy = $row['id_firmy'];
            $o->oglid = $oglid;
        }
        mysql_free_result($result);

        return $o;
    }


    public function save() {
        if ($this->oglid) {
            $query = sprintf('UPDATE ogloszenie SET id_stanowiska= %d, id_branzy = %d ' .
                    'id_miasta= %d, opis_pracy = "%s", wymagania = "%s", pensja_minimalna = %d, pensja_maksymalna = %d, id_okresu_aplikacji = %d, id_firmy=%d ' .
                    'WHERE id_ogloszenia = %d', $this->idStanowiska, $this->idBranzy, $this->idMiasta, mysql_real_escape_string($this->opisPracy, $GLOBALS['DB']), mysql_real_escape_string($this->wymagania, $GLOBALS['DB']), $this->pensjaMin, $this->pensjaMax, $this->idOkresuAplikacji, $this->id_firmy, $this->oglId);
            mysql_query($query, $GLOBALS['DB']);
        } else {
            $query = sprintf('INSERT INTO ogloszenie (id_stanowiska, id_branzy, id_miasta, opis_pracy, wymagania, pensja_minimalna, pensja_maksymalna, id_okresu_aplikacji, id_firmy) VALUES (%d, %d, %d, "%s", "%s", %d, %d, %d, %d)', $this->idStanowiska, $this->idBranzy, $this->idMiasta, mysql_real_escape_string($this->opisPracy, $GLOBALS['DB']), mysql_real_escape_string($this->wymagania, $GLOBALS['DB']), $this->pensjaMin, $this->pensjaMax, $this->idOkresuAplikacji, $this->id_firmy);
            mysql_query($query, $GLOBALS['DB']);

            $this->oglid = mysql_insert_id($GLOBALS['DB']);
        }
    }

    public function wyswietl($rek, $panel) {

        $er = 'select * from ogloszenie';
        $err = mysql_query($er, $GLOBALS['DB']);
        $tab[0] = mysql_num_rows($err);
        $idOgloszenia = $this->oglid;
        $query2 = sprintf('SELECT nazwa_miasta from miasto WHERE id_miasta = %d', $this->idMiasta);
        $m = mysql_query($query2, $GLOBALS['DB']);
        $mm = mysql_fetch_assoc($m);
        $miasto = $mm['nazwa_miasta'];
        $query7 = sprintf('SELECT nazwa_wojewodztwa from wojewodztwo LEFT JOIN miasto ON miasto.id_wojewodztwa=wojewodztwo.id_wojewodztwa WHERE miasto.id_miasta = %d', $this->idMiasta);

        $w = mysql_query($query7, $GLOBALS['DB']);
        $ww = mysql_fetch_assoc($w);

        $wojewodztwo = $ww['nazwa_wojewodztwa'];

        $query3 = sprintf('SELECT nazwa_branzy from branza WHERE id_branzy = %d', $this->idBranzy);
        $b = mysql_query($query3, $GLOBALS['DB']);
        $bb = mysql_fetch_assoc($b);
        $branza = $bb['nazwa_branzy'];

        $query4 = sprintf('SELECT nazwa_stanowiska from stanowisko WHERE id_stanowiska = %d', $this->idStanowiska);
        mysql_query($query4, $GLOBALS['DB']);
        $s = mysql_query($query4, $GLOBALS['DB']);
        $ss = mysql_fetch_assoc($s);
        $stanowisko = $ss['nazwa_stanowiska'];


        $query5 = sprintf('SELECT okres_aplikacji from okres_aplikacji WHERE id_okresu_aplikacji = %d', $this->idOkresuAplikacji);
        mysql_query($query5, $GLOBALS['DB']);
        $o = mysql_query($query5, $GLOBALS['DB']);
        $oo = mysql_fetch_assoc($o);
        $okres = $oo['okres_aplikacji'];

        $HTML = '';
        if ($rek == true AND $panel==0) {
            $HTML.='<tr class="wpis"><td><a href="szczegoly.php?id=' . $idOgloszenia . '">' . $stanowisko . '</a></td><td>' . $miasto . '</td><td>' . $wojewodztwo . '</td><td>' . $branza . '</td></tr>';
        } elseif ($rek == true AND $panel=1) {
            $HTML.='<tr class="wpis active"><td><a href="szczegoly.php?id=' . $idOgloszenia . '">' . $stanowisko . '</a></td><td>' . $miasto . '</td><td>' . $wojewodztwo . '</td><td>' . $branza . '</td></tr>';
        } else {
            $HTML.='<tr class="wpis" onClick="location.href=\'rekrutacja.php?id=\'' . $idOgloszenia . '"><td><a href="rekrutacja.php?id=' . $idOgloszenia . '">Pokaż rekrutację</a></td><td><a href="szczegoly.php?id=' . $idOgloszenia . '">' . $stanowisko . '</a></td><td>' . $miasto . '</td><td>' . $wojewodztwo . '</td><td>' . $branza . '</td></tr>';
        }
        $tab[1] = $HTML;

        return $tab;
    }

    public function wyswietlSzczegoly($n) {
        $e = Ogloszenie::getById($n);
        $id = $e->oglid;
        $query2 = sprintf('SELECT nazwa_miasta from miasto WHERE id_miasta = %d', $e->idMiasta);
        $m = mysql_query($query2, $GLOBALS['DB']);
        $mm = mysql_fetch_assoc($m);
        $miasto = $mm['nazwa_miasta'];
        $query7 = sprintf('SELECT nazwa_wojewodztwa from wojewodztwo WHERE id_wojewodztwa = %d', $e->idMiasta);

        $w = mysql_query($query7, $GLOBALS['DB']);
        $ww = mysql_fetch_assoc($w);

        $wojewodztwo = $ww['nazwa_wojewodztwa'];



        $query3 = sprintf('SELECT nazwa_branzy from branza WHERE id_branzy = %d', $e->idBranzy);
        $b = mysql_query($query3, $GLOBALS['DB']);
        $bb = mysql_fetch_assoc($b);
        $branza = $bb['nazwa_branzy'];

        $query4 = sprintf('SELECT nazwa_stanowiska from stanowisko WHERE id_stanowiska = %d', $e->idStanowiska);
        mysql_query($query4, $GLOBALS['DB']);
        $s = mysql_query($query4, $GLOBALS['DB']);
        $ss = mysql_fetch_assoc($s);
        $stanowisko = $ss['nazwa_stanowiska'];
        $sql10 = sprintf('SELECT * from ogloszenie_umiejetnosc LEFT JOIN umiejetnosc ON umiejetnosc.id_umiejetnosci=ogloszenie_umiejetnosc.id_umiejetnosci where id_ogloszenia=%d', $n);
        $query10 = mysql_query($sql10);

        $wym = nl2br($e->wymagania);

        $HTML.='<div class="container"><div class="col-sm-6"><div class="panel panel-default"><table class="table"><tr class="g"><td class="col-sm-3"><strong>Nazwa stanowiska</strong></td><td class="c col-sm-3">' . $stanowisko . '</td></tr><tr class="g"><td class="col-sm-3"><strong>Branża</strong></td><td class="c">' . $branza . '</td></tr><tr class="g"><td class="col-sm-3" ><strong>Miasto</strong></td><td class="c">' . $miasto . '</td></tr>'
                . '<tr class="g"><td class="col-sm-3"><strong>Województwo</strong></td><td class="c">' . $wojewodztwo . '</td></tr><tr  class="g"><td class"col-sm-3"><strong>Pensja minimalna</strong></td><td class="c">' . $e->pensjaMin . '</td></tr><tr class="g"><td class="col-sm-3"><strong>Pensja maksymalna</strong></td><td class="c">' . $e->pensjaMax . '</td></tr><tr></tr></table><table class="table t"><tr class="g"><td><strong>Opis pracy</strong><td></tr><tr><td>' . $e->opisPracy . '</td></tr>'
                . '<tr class="g"><td><strong>Wymagania</strong></td><tr><tr><td>' . $wym . '</td></tr><tr class="g"><td><strong>Wymagane umiejętności</strong></td></tr><tr class="g"><td class="c">';
       $sql10 = sprintf('SELECT * from ogloszenie_wym_umiejetnosc LEFT JOIN wymagana_umiejetnosc ON wymagana_umiejetnosc.id_umiejetnosci=ogloszenie_wym_umiejetnosc.id_umiejetnosci where ogloszenie_wym_umiejetnosc.id_ogloszenia=%d', $n);
        $query10 = mysql_query($sql10);
        $n=mysql_num_rows($query10);
       if ($n>0){
        while ($row = mysql_fetch_array($query10)) {
            $HTML.='' . $row[nazwa_umiejetnosci] . '</br>';
       }}
       else{
            $HTML.='Brak wymaganych umiejętności.</br>';
       }

        $HTML.= '</td></tr></table>'
                . '';


        return $HTML;
    }

    public function filtruj($miasto, $branza, $stanowisko) {
        $sql = 'SELECT * FROM ogloszenie ';
        $sql .= 'WHERE 1 ';
        if (!empty($miasto)) {
            $sql2 = sprintf('SELECT id_miasta from miasto WHERE nazwa_miasta = "%s"', $miasto);
            $result2 = mysql_query($sql2);
            $num = mysql_num_rows($result2);
            if ($num <= 0) {
                $sql1=sprintf('SELECT id_miasta from miasto LEFT JOIN wojewodztwo ON wojewodztwo.id_wojewodztwa=miasto.id_wojewodztwa WHERE nazwa_wojewodztwa = "%s"', $miasto);
                $query1= mysql_query($sql1);
                $num1=mysql_num_rows($query1);
                $i=$num1;
                if ($num1>0){
                    $sql .=  ' AND ('; 
                   while($row=mysql_fetch_array($query1)){
                       $sql .= sprintf('ogloszenie.id_miasta = %d ', $row[id_miasta]);
                       $i--; 
                       if ($i!=0){
                          $sql .= ' OR ' ;
                       }
                       else{
                              $sql .= ' ) ' ; 
                       }
                   }
                }

            } else {
                $sql .= sprintf(' AND ogloszenie.id_miasta = (SELECT id_miasta from miasto WHERE nazwa_miasta = "%s")', $miasto);
            }
        }
        if (!empty($branza)) {
            $sql .= sprintf(' AND ogloszenie.id_branzy = (SELECT id_branzy from branza WHERE nazwa_branzy =');
            $result2 = mysql_query($sql2);
            $i = 0;
            foreach ($branza as $idus) {
                $i++;
                $sql .= sprintf('"%s" ', $idus);
                if ($i != count($branza)) {
                    $sql .= sprintf('OR nazwa_branzy=');
                }
            }
            $sql .= sprintf(') ');
        }

  
        if (!empty($stanowisko)) {
      
            $sql2 = 'SELECT id_stanowiska from stanowisko WHERE stanowisko.nazwa_stanowiska like "%' .$stanowisko. '%"';
            $result2 = mysql_query($sql2) or var_dump(mysql_error()." ".$sql2);
            $row = mysql_fetch_assoc($result2);
         
            $idStanowiska = $row[id_stanowiska];

            $sql .= sprintf(' AND ogloszenie.id_stanowiska = %d ', $idStanowiska);
        }

    $sql .= ' order by id_ogloszenia ASC';
        return $sql;
    }

}

?>
