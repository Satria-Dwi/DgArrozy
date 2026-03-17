<?php
session_start();
require_once("../conf/conf.php");
header("Content-type: image/png");

// Reset session
$_SESSION["Capcay"] = "";

// Ukuran canvas
$width  = 220;
$height = 60;

$image = imagecreatetruecolor($width, $height);

// Background gradient sederhana
$bg1 = imagecolorallocate($image, 30, 144, 255);
$bg2 = imagecolorallocate($image, 0, 200, 255);
imagefill($image, 0, 0, $bg1);

// Font
$font = __DIR__ . "/blackjack.otf";
$font_size = 22;

// Generate angka
$captcha = "";
for ($i = 0; $i < 6; $i++) {
    $captcha .= rand(0, 9);
}

// Simpan AES (tetap seperti kamu)
$_SESSION["Capcay"] = getOne2("select aes_encrypt(" . $captcha . ",'windi')");

// 🔥 posisi tengah total
$bbox = imagettfbbox($font_size, 0, $font, $captcha);
$text_width  = $bbox[2] - $bbox[0];
$text_height = $bbox[1] - $bbox[7];

$x_start = ($width - $text_width) / 2;
$y = ($height + $text_height) / 2;

// 🔥 render per karakter biar natural
$x = $x_start;
for ($i = 0; $i < strlen($captcha); $i++) {

    $angle = rand(-15, 15);

    // warna random terang
    $text_color = imagecolorallocate(
        $image,
        rand(200, 255),
        rand(200, 255),
        rand(200, 255)
    );

    // offset dikit biar natural
    $y_offset = $y + rand(-3, 3);

    imagettftext(
        $image,
        $font_size,
        $angle,
        $x,
        $y_offset,
        $text_color,
        $font,
        $captcha[$i]
    );

    $x += 28; // jarak antar angka
}


// 🔥 Noise garis
for ($i = 0; $i < 6; $i++) {
    $line_color = imagecolorallocate(
        $image,
        rand(100, 255),
        rand(100, 255),
        rand(100, 255)
    );
    imageline(
        $image,
        rand(0, $width),
        rand(0, $height),
        rand(0, $width),
        rand(0, $height),
        $line_color
    );
}

// 🔥 Noise titik
for ($i = 0; $i < 150; $i++) {
    $dot_color = imagecolorallocate(
        $image,
        rand(150, 255),
        rand(150, 255),
        rand(150, 255)
    );
    imagesetpixel($image, rand(0, $width), rand(0, $height), $dot_color);
}

// Output
imagepng($image);
imagedestroy($image);
