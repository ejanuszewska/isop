<?php
session_start();
class Rekruter
{
    public $rid;     
    public $fields;  

   
    public function __construct()
    {
        $this->rid = null;
        $this->fields = array('adresEmail' => '',
                              'haslo' => '',
			      'imie' => '',
			      'nazwisko' => '',
				'id_firmy' => '',	  
                              'isActive' => false);
    }

 
    public function __get($field)
    {
        if ($field == 'rId')
        {
            return $this->rid;
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
    
 
    public static function getById($rid)
    {
        $r = new Rekruter();
	

        $query = sprintf('SELECT adres_email, haslo, imie, nazwisko, IS_ACTIVE, id_firmy ' .
            'FROM rekruter WHERE id_rekrutera = %d',
            $rid);

	

        $result = mysql_query($query, $GLOBALS['DB']);
	
        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
            $r->adresEmail = $row['adres_email'];
            $r->haslo = $row['haslo'];
			$r->imie = $row['imie'];
			$r->nazwisko = $row['nazwisko'];
            $r->isActive = $row['IS_ACTIVE'];
            $r->id_firmy=$row[id_firmy];
            $r->rid = $rid;
        }
        mysql_free_result($result);

        return $r;
    }

  
    public static function getByUsermail($usermail)
    {
        $r = new Rekruter();

        $query = sprintf('SELECT id_rekrutera, haslo, imie, nazwisko, IS_ACTIVE, id_firmy ' .
            'FROM rekruter WHERE adres_email = "%s"',
            mysql_real_escape_string($usermail, $GLOBALS['DB']));
        $result = mysql_query($query, $GLOBALS['DB']);

        if (mysql_num_rows($result))
        {
            $row = mysql_fetch_assoc($result);
            $r->adresEmail = $row['adres_email'];
            $r->haslo = $row['haslo'];
            $r->isActive = $row['IS_ACTIVE'];
			$r->imie = $row['imie'];
		    $r->nazwisko = $row['nazwisko'];
                    $r->id_firmy = $row['id_firmy'];
            $r->rid = $row['id_rekrutera'];
        }

        mysql_free_result($result);
        return $r;
    }


    public function save()
    {
        if ($this->rid)
        {
              $query = sprintf('UPDATE rekruter SET adres_email = "%s", ' .
                'haslo = "%s", imie = "%s", nazwisko = "%s", IS_ACTIVE = %d, id_firmy = %d ' .
                'WHERE id_rekrutera = %d',
                
                mysql_real_escape_string($this->adresEmail, $GLOBALS['DB']),
				mysql_real_escape_string($this->haslo, $GLOBALS['DB']),
				  mysql_real_escape_string($this->imie, $GLOBALS['DB']),
				  mysql_real_escape_string($this->nazwisko, $GLOBALS['DB']),
                $this->isActive,
                $this->id_firmy,
                $this->rId);
            mysql_query($query, $GLOBALS['DB']);
        }
        else
        {
            $query = sprintf('INSERT INTO rekruter (adres_email, haslo, IS_ACTIVE, imie, nazwisko, id_firmy) 
			VALUES ("%s", "%s", %d, "%s", "%s", %d)',
                mysql_real_escape_string($this->adresEmail, $GLOBALS['DB']),
                mysql_real_escape_string($this->haslo, $GLOBALS['DB']),
                $this->isActive,
				mysql_real_escape_string($this->imie, $GLOBALS['DB']),
				mysql_real_escape_string($this->nazwisko, $GLOBALS['DB']),
                                $this->id_firmy);
            mysql_query($query, $GLOBALS['DB']);

            $this->rid = mysql_insert_id($GLOBALS['DB']);
        }
    }


    public function setInactive()
    {
        $this->isActive = false;
        $this->save();

        $token = random_text(5);
        $query = sprintf('INSERT INTO REKPENDING (id_rekrutera, TOKEN) ' . 
            'VALUES (%d, "%s")',
            $this->rid,
            $token);
        mysql_query($query, $GLOBALS['DB']);

        return $token;
    }

   
    public function setActive($token)
    {
        $query = sprintf('SELECT TOKEN FROM REKPENDING WHERE id_rekrutera = %d ' . 
            'AND TOKEN = "%s"',
            $this->rid,
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
            $query = sprintf('DELETE FROM REKPENDING WHERE id_rekrutera = %d ' .
                'AND TOKEN = "%s"', 
                $this->rid,
                mysql_real_escape_string($token, $GLOBALS['DB']));
            mysql_query($query, $GLOBALS['DB']);

            $this->isActive = true;
            $this->save();
            return true;
        }
    }
}
?>
