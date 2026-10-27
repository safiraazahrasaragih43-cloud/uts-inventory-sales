<?php
$candidates = [
    'hijab_pink_hands' => 'https://images.unsplash.com/photo-1772625717706-d9519ff1157b?auto=format&fit=crop&w=600&q=80',
    'hijab_pink_dress' => 'https://images.unsplash.com/photo-1772625717793-7ccdd11812e0?auto=format&fit=crop&w=600&q=80',
    'hijab_pink_smiles' => 'https://images.unsplash.com/photo-1772625717865-a6300f44543e?auto=format&fit=crop&w=600&q=80',
    'hijab_praja_wall' => 'https://images.unsplash.com/photo-1619544592515-8aae927088bc?auto=format&fit=crop&w=600&q=80',
    'hijab_white_silk' => 'https://images.unsplash.com/photo-1585728748176-455ac5eed962?auto=format&fit=crop&w=600&q=80',
];

foreach ($candidates as $k => $u) {
    file_put_contents("temp_inspect/{$k}.jpg", file_get_contents($u));
}
echo "Downloaded new candidates!\n";
