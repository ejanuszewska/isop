<?php
include '../../lib/functions.php';

// nale¿y utworzyæ lub kontynuowaæ sesjê i zapisaæ ci±g znaków CAPTCHA
// w $_SESSION, by by³ dostêpny w ramach innych wywo³añ
if (!isset($_SESSION))
{
    session_start();
    header('Cache-control: private');
}

// utworzenie obrazka o wymiarach 65x20 pikseli
$width = 65;
$height = 20;
$image = imagecreate(65, 20);

// wype³nienie obrazka kolorem t³a
$bg_color = imagecolorallocate($image, 0x33, 0x66, 0xFF);
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// pobranie losowego tekstu
$text = random_text(5);

// ustalenie wspó³rzêdnych x i y do wy¶rodkowania tekstu
$font = 5;
$x = imagesx($image) / 2 - strlen($text) * imagefontwidth($font) / 2;
$y = imagesy($image) / 2 - imagefontheight($font) / 2;

// wypisanie tekstu na obrazku
$fg_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagestring($image, $font, $x, $y, $text, $fg_color);

// zapisanie ci±gu znaków CAPTCHA do pó¼niejszego porównania
$_SESSION['captcha'] = $text;

// zwrócenie obrazka
header('Content-type: image/png');
imagepng($image);

imagedestroy($image);
?>
