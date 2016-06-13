<?php
include '../lib/db.php';

$q = isset($_POST['query']) ? mysql_real_escape_string($_POST['query']) : '';

$result = mysql_query("select * from wymagana_umiejetnosc where wymagana_umiejetnosc.nazwa_umiejetnosci like '%".$q."%'", $GLOBALS['DB']);

$rows = array();
while ($row = mysql_fetch_array($result)) {
    $rows[] = $row;
}

echo json_encode($rows);



