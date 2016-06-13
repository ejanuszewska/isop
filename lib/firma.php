<?php
class Firma
{
    private $fid;     
    private $fields;  

  
    public function __construct()
    {
        $this->fid = null;
        $this->fields = array('nazwa' => '',
                              'opis' => '',
			      'adresEmail' => '',
                              'idMiasta' => '',
                              'ulica' => '',
                              'numer_domu' => '',
                              'numer_lokalu' => '',
                              'idBranzy' => '');
            
            
    }

   
    public function __get($field)
    {
        if ($field == 'fId')
        {
            return $this->fid;
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
    

    public static function getById($fid)
    {
        $f = new Firma();

        $query = sprintf('SELECT nazwa_firmy, opis_firmy, adres_email, id_miasta, ulica, nr_lokalu, nr_domu, id_branzy ' .
            ' FROM firma WHERE id_firmy = %d',
            $fid);
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
            $f->nazwa = $row['nazwa_firmy'];
	    $f->opis = $row['opis_firmy'];
            $f->adresEmail = $row['adres_email'];
            $f->idMiasta = $row['id_miasta'];
            $f->ulica = $row['ulica'];
            $f->numer_domu = $row['nr_domu'];
            $f->numer_lokalu = $row['nr_lokalu'];
            $f->idBranzy = $row['id_branzy'];
            $f->fid = $fid;
        }
        mysql_free_result($result);

        return $f;
    }


    public static function getByUsermail($usermail)
    {
        $f = new Firma();

        $query = sprintf('SELECT id_firmy, nazwa_firmy, opis_firmy, adres_email, id_miasta, ulica, nr_domu, nr_lokalu, id_branzy' .
            'FROM firma WHERE adres_email = "%s"',
            mysql_real_escape_string($usermail, $GLOBALS['DB']));
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
             $f->nazwa = $row['nazwa_firmy'];
	    $f->opis = $row['opis_firmy'];
            $f->adresEmail = $row['adres_email'];
            $f->idMiasta = $row['id_miasta'];
            $f->ulica = $row['ulica'];
            $f->numer_domu = $row['nr_domu'];
            $f->numer_lokalu = $row['nr_lokalu'];
            $f->idBranzy = $row['id_branzy'];
            $f->fid = $row['id_firmy'];
        }

        mysql_free_result($result);
        return $f;
    }


    public function save()
    {
        if ($this->fid)
        {
              $query = sprintf('UPDATE firma SET adres_email = "%s", ' .
                'nazwa_firmy = "%s", opis_firmy = "%s", id_miasta=%d, ulica="%s", nr_domu=%d, nr_lokalu=%d, id_branzy=%d ' .
                'WHERE id_firmy = %d',
                
                mysql_real_escape_string($this->adresEmail, $GLOBALS['DB']),
				mysql_real_escape_string($this->nazwa, $GLOBALS['DB']),
				  mysql_real_escape_string($this->opis, $GLOBALS['DB']),
                                  $this->idMiasta,
                                  mysql_real_escape_string($this->ulica, $GLOBALS['DB']),
                                  $this->numer_domu,
                                  $this->numer_lokalu,
                                  $this->idBranzy,
                $this->fId);
            mysql_query($query, $GLOBALS['DB']);
        }
        else
        {
            $query = sprintf('INSERT INTO firma (adres_email, nazwa_firmy, opis_firmy, id_miasta, ulica, nr_domu, nr_lokalu, id_branzy) 
			VALUES ("%s", "%s", "%s", %d, "%s", %d, %d, %d)',
                mysql_real_escape_string($this->adresEmail, $GLOBALS['DB']),
                mysql_real_escape_string($this->nazwa, $GLOBALS['DB']),
                mysql_real_escape_string($this->opis, $GLOBALS['DB']),
                $this->idMiasta,
                mysql_real_escape_string($this->ulica, $GLOBALS['DB']),
                $this->numer_domu,
                $this->numer_lokalu,
                $this->idBranzy);
            mysql_query($query, $GLOBALS['DB']);

            $this->fid = mysql_insert_id($GLOBALS['DB']);
        }
    }

    

}
?>
