<?php

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';



if (!isset($_GET['uid']) || !isset($_GET['token']))
{
    $GLOBALS['TEMPLATE']['content'] = '<p><strong>Otrzymane informacje ' .
        'są niepełne.</strong></p> <p>Prosimy spróbować ponownie.</p>';
    include '../templates/template-page.php';
    exit();
}


if (!$user = User::getById($_GET['uid']))
{
    $GLOBALS['TEMPLATE']['content'] = '<p><strong>Podane konto nie istnieje.</strong>' .
        '</p> <p>Prosimy spróbować ponownie.</p>';
}

else
{
    if ($user->isActive)
    {
        $GLOBALS['TEMPLATE']['content'] = '<p><strong>Konto ' .
            'zostało już zweryfikowane.</strong></p>';
    }

    else 
    {
        if ($user->setActive($_GET['token']))
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
