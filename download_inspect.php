<?php
$urls = [
    '1_rose_floral' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=400&q=80',
    '2_satin_wrap' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=400&q=80',
    '3_peach_blouse' => 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?auto=format&fit=crop&w=400&q=80',
    '4_lace_shirt' => 'https://images.unsplash.com/photo-1551803091-e20673f15770?auto=format&fit=crop&w=400&q=80',
    '5_pleated_skirt' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=400&q=80',
    '6_culottes' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=400&q=80',
    '7_cardigan' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=400&q=80',
    '8_cable_knit' => 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=400&q=80',
    '9_voile_hijab' => 'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?auto=format&fit=crop&w=400&q=80',
    '10_satin_pashmina' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=400&q=80',
    '11_maroon_dress' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=400&q=80',
    '12_flannel_shirt' => 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=400&q=80',
    '13_blus_peony' => 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=400&q=80',
];

if (!is_dir('temp_inspect')) {
    mkdir('temp_inspect', 0777, true);
}

foreach ($urls as $name => $url) {
    $dest = "temp_inspect/{$name}.jpg";
    if (!file_exists($dest)) {
        echo "Downloading $name...\n";
        $data = @file_get_contents($url);
        if ($data) {
            file_put_contents($dest, $data);
        } else {
            echo "Failed $name\n";
        }
    }
}
echo "Done!\n";
