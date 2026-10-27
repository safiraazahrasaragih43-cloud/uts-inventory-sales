<?php
function scrapePage($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/https:\/\/images\.unsplash\.com\/(photo-[a-zA-Z0-9\-]+)/', $html, $matches);
    return array_unique($matches[1]);
}

$photos = scrapePage('https://unsplash.com/@przbe');
print_r(array_slice($photos, 0, 10));
