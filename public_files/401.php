<?php

include '../lib/common.php';


session_start();
header('Cache-control: private');


if (!isset($_SESSION['access']) || $_SESSION['access'] != TRUE)
{
    header('HTTP/1.0 401 Authorization Error');
    ob_start();
?>
<script type="text/javascript">
window.seconds = 10; 
window.onload = function()
{
    if (window.seconds != 0)
    {
        document.getElementById('secondsDisplay').innerHTML = '' +
            window.seconds + ' sekund' + ((window.seconds > 4) ? '' : 'y');
        window.seconds--;
        setTimeout(window.onload, 1000);
    }
    else
    {
        window.location = 'login.php';
    }
}
</script>
<?php
    $GLOBALS['TEMPLATE']['extra_head'] = ob_get_contents();
    ob_clean();

?>
<p>Wywo�any zas�b wymaga uwierzytelnienia si�. Nie wpisano
odpowiednich danych uwierzytelniaj�cych lub podane dane
uwierzytelniaj�ce nie uprawniaj� do uzyskania dost�pu do zasobu.</p>

<p><strong>Za <span id="secondsDisplay">10 sekund</span> nast�pi
przekierowanie do strony logowania.</strong></p>

<p>Je�eli przekierowanie nie nast�pi automatycznie , nale�y klikn�� nast�puj�ce ��cze: 
<a href="login.php">Logowanie</a></p>
<?php
    $GLOBALS['TEMPLATE']['content'] = ob_get_clean();

    include '../templates/template-page.php';
    exit();
}
?>
