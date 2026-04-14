<?php
// Save this as create-favicon.php in your public folder
// Then visit: http://yourdomain.com/create-favicon.php

$sourceImage = 'images/TK.png';

if (file_exists($sourceImage)) {
    // Create 32x32 favicon
    $img32 = imagecreatetruecolor(32, 32);
    $img16 = imagecreatetruecolor(16, 16);
    
    // Load source image
    $source = imagecreatefrompng($sourceImage);
    
    // Get original dimensions
    $width = imagesx($source);
    $height = imagesy($source);
    
    // Preserve transparency
    imagealphablending($img32, false);
    imagesavealpha($img32, true);
    $transparent = imagecolorallocatealpha($img32, 0, 0, 0, 127);
    imagefill($img32, 0, 0, $transparent);
    
    imagealphablending($img16, false);
    imagesavealpha($img16, true);
    imagefill($img16, 0, 0, $transparent);
    
    // Resize and copy
    imagecopyresampled($img32, $source, 0, 0, 0, 0, 32, 32, $width, $height);
    imagecopyresampled($img16, $source, 0, 0, 0, 0, 16, 16, $width, $height);
    
    // Save files
    imagepng($img32, 'favicon-32x32.png');
    imagepng($img16, 'favicon-16x16.png');
    
    // Create ICO file (optional - better compatibility)
    // You'll need to install a library or use online tool for ICO
    
    echo "✅ Favicon files created successfully!<br>";
    echo "📁 favicon-32x32.png created<br>";
    echo "📁 favicon-16x16.png created<br>";
    echo "<br>Now add this to your HTML:<br>";
    echo "<code>&lt;link rel='icon' type='image/png' sizes='32x32' href='{{ asset('favicon-32x32.png') }}'&gt;<br>";
    echo "&lt;link rel='icon' type='image/png' sizes='16x16' href='{{ asset('favicon-16x16.png') }}'&gt;</code>";
    
    imagedestroy($img32);
    imagedestroy($img16);
    imagedestroy($source);
} else {
    echo "❌ Error: Cannot find images/TK.png";
}
?>