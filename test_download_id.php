<?php
$url = 'https://unsplash.com/photos/SNv62ysp-ow/download?force=true&w=800';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$data = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
echo "HTTP code: " . $info['http_code'] . "\n";
echo "Effective URL: " . $info['url'] . "\n";
echo "Content length: " . strlen($data) . "\n";
if (strlen($data) > 1000) {
    file_put_contents('temp_inspect/test_hijab_snv.jpg', $data);
}
