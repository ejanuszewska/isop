<?php
include '../../lib/functions.php';

// nale�y utworzy� lub kontynuowa� sesj� i zapisa� ci�g znak�w CAPTCHA
// w $_SESSION, by by� dost�pny w ramach innych wywo�a�
if (!isset($_SESSION))
{
    session_start();
    header('Cache-control: private');
}

// utworzenie obrazka o wymiarach 65x20 pikseli
$width = 65;
$height = 20;
$image = imagecreate(65, 20);

// wype�nienie obrazka kolorem t�a
$bg_color = imagecolorallocate($image, 0x33, 0x66, 0xFF);
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// pobranie losowego tekstu
$text = random_text(5);

// ustalenie wsp�rz�dnych x i y do wy�rodkowania tekstu
$font = 5;
$x = imagesx($image) / 2 - strlen($text) * imagefontwidth($font) / 2;
$y = imagesy($image) / 2 - imagefontheight($font) / 2;

// wypisanie tekstu na obrazku
$fg_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagestring($image, $font, $x, $y, $text, $fg_color);

// zapisanie ci�gu znak�w CAPTCHA do p�niejszego por�wnania
$_SESSION['captcha'] = $text;

// zwr�cenie obrazka
header('Content-type: image/png');
imagepng($image);

imagedestroy($image);
?>
