<?php
$ch = curl_init('https://unsplash.com/s/photos/hijab');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);
echo "Length: " . strlen($html) . "\n";
echo substr($html, 0, 500) . "\n";
