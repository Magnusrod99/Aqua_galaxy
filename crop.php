<?php
$src = "C:/Users/rodri/.gemini/antigravity/brain/d396edaf-4288-4b98-9127-17a6c8e46a09/.user_uploaded/media_1795540102354_9479c63e.jpg";
if (!file_exists($src)) die("Source file not found");
$im = imagecreatefromjpeg($src);
$w = imagesx($im);
$h = imagesy($im);

// Crop top bar (tabs + address bar)
$header = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $w, 'height' => 120]);
imagepng($header, __DIR__ . '/header_crop.png');
echo "Cropped: {$w}x{$h}\n";
