<?php
$redirectUrl = 'https://vertexaisearch.cloud.google.com/grounding-api-redirect/AUZIYQHvJsYxWarVqeqIgzZ_xJXkJ23xOr0AhsKv1gEusbZEoAoGmVL9aUfDxUfehsiZTMfbYtP-nTPnkUmfMHEgPyjacdm1rP4GiR7Mg2ONtte6VuFf0CZV3wr8tSqQVk8JIsy0YJu4ydPmIz0Yjsqqtt4XqMaQ-GnTRmwujnurr8WHUH9w-DO-Iw==';
$ch = curl_init($redirectUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_exec($ch);
$target = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
curl_close($ch);
echo "Target: $target\n";
