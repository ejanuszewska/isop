<?php
class User
{
    private $uid;    
    private $fields;  

    public function __construct()
    {
        $this->uid = null;
        $this->fields = array('emailAddr' => '',
                              'password' => '',
							  'imie' => '',
							  'nazwisko' => '',
							  'data_ur' =>'',
                              'isActive' => false);
    }


    public function __get($field)
    {
        if ($field == 'userId')
        {
            return $this->uid;
        }
        else 
        {
            return $this->fields[$field];
        }
    }

   
    public function __set($field, $value)
    {
        if (array_key_exists($field, $this->fields))
        {
            $this->fields[$field] = $value;
        }
    }

 
    

    public static function validateEmailAddr($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    
    public static function getById($uid)
    {
        $u = new User();

        $query = sprintf('SELECT EMAIL_ADDR, PASSWORD, IMIE, NAZWISKO, DATA_UR, IS_ACTIVE ' .
            'FROM kandydat WHERE USER_ID = %d',
         
            $uid);
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
            $u->emailAddr = $row['EMAIL_ADDR'];
            $u->password = $row['PASSWORD'];
			$u->imie = $row['IMIE'];
			$u->nazwisko = $row['NAZWISKO'];
			$u->data_ur = $row['DATA_UR'];
            $u->isActive = $row['IS_ACTIVE'];
            $u->uid = $uid;
        }
        mysql_free_result($result);

        return $u;
    }

  
    public static function getByUsermail($usermail)
    {
        $u = new User();

        $query = sprintf('SELECT USER_ID, PASSWORD, IMIE, NAZWISKO, DATA_UR, IS_ACTIVE ' .
            'FROM kandydat WHERE EMAIL_ADDR = "%s"',
           
            mysql_real_escape_string($usermail, $GLOBALS['DB']));
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
            $u->emailAddr = $row['EMAIL_ADDR'];
            $u->password = $row['PASSWORD'];
            $u->isActive = $row['IS_ACTIVE'];
			$u->imie = $row['IMIE'];
		    $u->nazwisko = $row['NAZWISKO'];
			$u->data_ur = $row['DATA_UR'];
            $u->uid = $row['USER_ID'];
        }

        mysql_free_result($result);
        return $u;
    }

  
    public function save()
    {
        if ($this->uid)
        {
              $query = sprintf('UPDATE kandydat SET EMAIL_ADDR = "%s", ' .
                'PASSWORD = "%s", IMIE = "%s", NAZWISKO = "%s", DATA_UR = "%s", IS_ACTIVE = %d ' .
                'WHERE USER_ID = %d',
           
                
                mysql_real_escape_string($this->emailAddr, $GLOBALS['DB']),
				mysql_real_escape_string($this->password, $GLOBALS['DB']),
				  mysql_real_escape_string($this->imie, $GLOBALS['DB']),
				  mysql_real_escape_string($this->nazwisko, $GLOBALS['DB']),
				  	  mysql_real_escape_string($this->data_ur, $GLOBALS['DB']),
                $this->isActive,
                $this->userId);
            mysql_query($query, $GLOBALS['DB']);
        }
        else
        {
            $query = sprintf('INSERT INTO kandydat (EMAIL_ADDR, PASSWORD, IS_ACTIVE, IMIE, NAZWISKO, DATA_UR) 
			VALUES ("%s", "%s", %d, "%s", "%s", "%s")',
             
                mysql_real_escape_string($this->emailAddr, $GLOBALS['DB']),
                mysql_real_escape_string($this->password, $GLOBALS['DB']),
                $this->isActive,
				mysql_real_escape_string($this->imie, $GLOBALS['DB']),
				mysql_real_escape_string($this->nazwisko, $GLOBALS['DB']),
				mysql_real_escape_string($this->data_ur, $GLOBALS['DB']));
            mysql_query($query, $GLOBALS['DB']);

            $this->uid = mysql_insert_id($GLOBALS['DB']);
        }
    }

    public function setInactive()
    {
        $this->isActive = false;
        $this->save(); 

        $token = random_text(5);
        $query = sprintf('INSERT INTO pending (USER_ID, TOKEN) ' . 
            'VALUES (%d, "%s")',
         
            $this->uid,
            $token);
        mysql_query($query, $GLOBALS['DB']);

        return $token;
    }


    public function setActive($token)
    {
        $query = sprintf('SELECT TOKEN FROM pending WHERE USER_ID = %d ' . 
            'AND TOKEN = "%s"',
  
            $this->uid,
            mysql_real_escape_string($token, $GLOBALS['DB']));
        $result = mysql_query($query, $GLOBALS['DB']);

        if (!mysql_num_rows($result))
        {
            mysql_free_result($result);
            return false;
        }
        else
        {
            mysql_free_result($result);
            $query = sprintf('DELETE FROM pending WHERE USER_ID = %d ' .
                'AND TOKEN = "%s"', 
                $this->uid,
                mysql_real_escape_string($token, $GLOBALS['DB']));
            mysql_query($query, $GLOBALS['DB']);

            $this->isActive = true;
            $this->save();
            return true;
        }
    }
    
    public function Wyswietl($uid, $idOgloszenia, $idEtapu){
        $u=User::getById($uid);

        
        $HTML.='<td>'.$u->imie.'</td>';
        $HTML.='<td>'.$u->nazwisko.'</td>';
        $HTML.='<td>'.$u->data_ur.'</td>';
        if (isset($idOgloszenia) && !isset($idEtapu)){
         $HTML.='<td><a href="profil_rek.php?id='.$uid.'&idOgl='.$idOgloszenia.'">Zobacz profil</a></td>';   
        }
        elseif (isset($idOgloszenia) && isset($idEtapu)){
                     $HTML.='<td><a href="profil_rek.php?id='.$uid.'&idOgl='.$idOgloszenia.'&idEtapu='.$idEtapu.'">Zobacz profil</a></td></tr>';  
        }
        else{
                    $HTML.='<td><a href="profil_rek.php?id='.$uid.'">Zobacz profil</a></td></tr>';    
        }
        
  
        return $HTML;
    }
    
     public function WyswietlPelny($uid){
        $u=User::getById($uid);
        $HTML='';
        $HTML.='<div class="container"><div class="col-md-6"><div class="panel panel-default"><div class="panel-heading"><strong>Dane kandydata</strong></div><table class="table"><tr><td class="col-sm-3">Imię:</td><td class="col-sm-3">'.$u->imie.'</td><tr>';
        $HTML.='<tr><td class="col-sm-3">Nazwisko:</td><td class="col-sm-3">'.$u->nazwisko.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Data urodzenia:</td><td class="col-sm-3">'.$u->data_ur.'</td></tr></table>';
        $HTML.='<div class="panel-heading"><strong>Wykształcenie</strong></div><table class="table">'; 
        
        $sql=sprintf('SELECT * from wyksztalcenie LEFT JOIN wyksztalcenie_kandydat ON wyksztalcenie.id_wyksztalcenia=wyksztalcenie_kandydat.id_wyksztalcenia WHERE wyksztalcenie_kandydat.USER_ID=%d', $uid);
        $query=mysql_query($sql);
        while($w = mysql_fetch_assoc($query)){
        $id_wyk = $w['id_wyksztalcenia'];
        $data_rozp = $w['data_rozpoczecia'];
        $data_zak = $w['data_zakonczenia'];
        $sql2=sprintf('select nazwa_szkoly from szkola LEFT JOIN wyksztalcenie ON szkola.id_szkoly=wyksztalcenie.id_szkoly WHERE wyksztalcenie.id_wyksztalcenia=%d',$id_wyk);
        $query2=mysql_query($sql2);
        $n = mysql_fetch_assoc($query2);
        $nazwa_szkoly = $n['nazwa_szkoly'];
        $sql3=sprintf('select nazwa_kierunku from kierunek LEFT JOIN wyksztalcenie ON kierunek.id_kierunku=wyksztalcenie.id_kierunku WHERE wyksztalcenie.id_wyksztalcenia=%d',$id_wyk);
        $query3=mysql_query($sql3);
        $k = mysql_fetch_assoc($query3);
        $nazwa_kierunku = $k['nazwa_kierunku'];
        $HTML.='<tr><td class="col-sm-3">Nazwa szkoły:</td><td class="col-sm-3">'.$nazwa_szkoly.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Kierunek:</td><td class="col-sm-3">'.$nazwa_kierunku.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Data rozpoczęcia:</td><td class="col-sm-3">'.$data_rozp.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Data zakończenia:</td><td class="col-sm-3">'.$data_zak.'</td></tr>';
        }

        $HTML.='</table></br><div class="panel-heading"><strong>Doświadczenie</strong></div>';
        $sql4=sprintf('SELECT * from doswiadczenie LEFT JOIN doswiadczenie_kandydat ON doswiadczenie.id_doswiadczenia=doswiadczenie_kandydat.id_doswiadczenia WHERE doswiadczenie_kandydat.USER_ID=%d', $uid);
        $query=mysql_query($sql4);
        while($d = mysql_fetch_assoc($query)){
        $id_dosw = $d['id_doswiadczenia'];
        $data_rozp_d = $d['data_rozpoczecia'];
        $data_zak_d = $d['data_zakonczenia'];
        $sql5=sprintf('select nazwa_firmy from firma LEFT JOIN doswiadczenie ON firma.id_firmy=doswiadczenie.id_firmy WHERE doswiadczenie.id_doswiadczenia=%d',$id_dosw);
        $query5=mysql_query($sql5);
        $f = mysql_fetch_assoc($query5);
        $nazwa_firmy = $f['nazwa_firmy'];
      
        $sql6=sprintf('select nazwa_miasta from miasto LEFT JOIN doswiadczenie ON miasto.id_miasta=doswiadczenie.id_miasta WHERE doswiadczenie.id_doswiadczenia=%d',$id_dosw);
        $query6=mysql_query($sql6);
        $m = mysql_fetch_assoc($query6);
        $nazwa_miasta = $m['nazwa_miasta'];
     
       
        $HTML.='<table class="table"><tr><td class="col-sm-3">Nazwa firmy:</td><td class="col-sm-3">'.$nazwa_firmy.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Miasto:</td><td class="col-sm-3">'.$nazwa_miasta.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Data rozpoczęcia:</td><td class="col-sm-3">'.$data_rozp_d.'</td></tr>';
        $HTML.='<tr><td class="col-sm-3">Data zakończenia:</td><td class="col-sm-3">'.$data_zak_d.'</td></tr></table></br>';
        }
      
        $HTML.='<div class="panel-heading"><strong>Umiejętności</strong></div>';
        $sql7=sprintf('SELECT * from umiejetnosc LEFT JOIN umiejetnosc_kandydat ON umiejetnosc.id_umiejetnosci=umiejetnosc_kandydat.id_umiejetnosci WHERE umiejetnosc_kandydat.USER_ID=%d', $uid);
        $query7=mysql_query($sql7);
               $HTML.='<table class="table">';
           $HTML.='<tr><td class="col-sm-3">Typ umiejętnosci</td><td class="col-sm-3">Poziom</td></tr>';
        while($u = mysql_fetch_assoc($query7)){
        $id_um = $u['id_umiejetnosci'];
        $sql8=sprintf('select typ_umiejetnosci from umiejetnosc  WHERE id_umiejetnosci=%d',$id_um);
        $query8=mysql_query($sql8);
        $t = mysql_fetch_assoc($query8);
        $typ = $t['typ_umiejetnosci'];
        $sql9=sprintf('select nazwa_poziomu from poziom LEFT JOIN umiejetnosc_kandydat ON poziom.id_poziomu=umiejetnosc_kandydat.id_poziomu WHERE umiejetnosc_kandydat.id_umiejetnosci=%d',$id_um);
        $query9=mysql_query($sql9);
  
        $p = mysql_fetch_assoc($query9);
        $poziom = $p['nazwa_poziomu'];
      
        $HTML.='<tr><td class="col-sm-3">'.$typ.'</td><td class="col-sm-3">'.$poziom.'</td></tr>';
     
        }
        $HTML.= '</table>';
        $HTML.='<div class="panel-heading"><strong>Wymagane umiejętności</strong></div>';
        $sql7=sprintf('SELECT * from wymagana_umiejetnosc LEFT JOIN kandydat_wym_umiejetnosc ON wymagana_umiejetnosc.id_umiejetnosci=kandydat_wym_umiejetnosc.id_umiejetnosci WHERE kandydat_wym_umiejetnosc.USER_ID=%d', $uid);
        $query7=mysql_query($sql7);
               $HTML.='<table class="table">';
          
        while($u = mysql_fetch_assoc($query7)){
        $id_um = $u['id_umiejetnosci'];
        $sql8=sprintf('select nazwa_umiejetnosci from wymagana_umiejetnosc  WHERE id_umiejetnosci=%d',$id_um);
        $query8=mysql_query($sql8);
        $t = mysql_fetch_assoc($query8);
        $typ = $t['nazwa_umiejetnosci'];
      
      
        $HTML.='<tr><td class="col-sm-3">'.$typ.'</td></tr>';
     
        }
        $HTML.= '</table></div></div></div>';
        return $HTML;
    }
}
?>
