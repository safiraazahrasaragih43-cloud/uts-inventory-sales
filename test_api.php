<?php
function searchUnsplash($query) {
    $url = "https://unsplash.com/napi/search/photos?query=" . urlencode($query) . "&per_page=5";
    $ctx = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\nAccept: application/json\r\n"
        ]
    ]);
    $res = @file_get_contents($url, false, $ctx);
    if (!$res) return null;
    $data = json_decode($res, true);
    $results = [];
    if (!empty($data['results'])) {
        foreach ($data['results'] as $item) {
            $results[] = [
                'id' => $item['id'],
                'desc' => $item['alt_description'] ?? $item['description'] ?? 'No desc',
                'url' => $item['urls']['regular'] ?? null,
                'raw' => $item['urls']['raw'] ?? null,
            ];
        }
    }
    return $results;
}

$queries = [
    'hijab model',
    'hijab square',
    'satin scarf',
    'pleated skirt',
    'linen culottes women',
    'knit cardigan pastel',
    'cable knit sweater pink',
    'flannel shirt women',
    'floral blouse women',
    'pink satin dress',
    'maroon evening dress',
    'floral midi dress'
];

foreach ($queries as $q) {
    echo "=== Query: $q ===\n";
    $items = searchUnsplash($q);
    if ($items) {
        foreach ($items as $it) {
            echo "  - [{$it['id']}] {$it['desc']}\n    {$it['url']}\n";
        }
    } else {
        echo "  (No results or blocked)\n";
    }
}
