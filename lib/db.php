<?php


define('DB_HOST', 'mysql.cba.pl');
define('DB_USER', 'ewa2909');
define('DB_PASSWORD', '');
define('DB_SCHEMA', 'ogloszeniapraca_cba_pl');




if (!$GLOBALS['DB'] = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
{
    die('Błąd');
}
if (!mysql_select_db(DB_SCHEMA, $GLOBALS['DB']))
{
    mysql_close($GLOBALS['DB']);
    die('Błąd');
}
?>
