<?php

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/rec.php';



if (!isset($_GET['rid']) || !isset($_GET['token']))
{
    $GLOBALS['TEMPLATE']['content'] = '<p><strong>Otrzymane informacje ' .
        's� niepe�ne.</strong></p> <p>Prosimy spr�bowa� ponownie.</p>';
    include '../templates/template-page.phptemplate_page.php';
    exit();
}


if (!$rekruter = Rekruter::getById($_GET['rid']))
{
    $GLOBALS['TEMPLATE']['content'] = '<p><strong>Podane konto nie istnieje.</strong>' .
        '</p> <p>Prosimy spr�bowa� ponownie.</p>';
}

else
{
    if ($rekruter->isActive)
    {
        $GLOBALS['TEMPLATE']['content'] = '<p><strong>Konto ' .
            'zosta�o ju� zweryfikowane.</strong></p>';
    }

    else 
    {
        if ($rekruter->setActive($_GET['token']))
        {
            $GLOBALS['TEMPLATE']['content'] = '<p><strong>Dziękujemy ' .
                'za zweryfikowanie konta.</strong></p> <p>Można się ' .
                'teraz <a href="login.php">zalogować</a>.</p>';
        }
        else
        {
            $GLOBALS['TEMPLATE']['content'] = '<p><strong>Podano ' .
                'nieprawidłowe dane.</strong></p> <p>Prosimy spróbować ponownie.</p>';
        }
    }
}


include '../templates/template-page.php';
?>
