<?php

echo "=== ESP32 API Test ===\n\n";

$baseUrl = 'http://localhost/api/v1/esp32datas';
$apiKey = 'IanRan1qaz@WSX';

// Test 1: Health Check
echo "1. Testing Health Check...\n";
$healthUrl = $baseUrl . '/health';
echo "URL: $healthUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $healthUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 2: GET Request with Data
echo "2. Testing GET Request with Data...\n";
$getUrl = $baseUrl . '/input?sensor=temp&location=room1&value1=25.5&value2=60&value3=1013&api_key=' . urlencode($apiKey);
echo "URL: $getUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: POST Request with Data
echo "3. Testing POST Request with Data...\n";
$postUrl = $baseUrl . '/input';
echo "URL: $postUrl\n";

$postData = http_build_query([
    'sensor' => 'humidity',
    'location' => 'kitchen',
    'value1' => '45.2',
    'value2' => '70',
    'value3' => '1015',
    'api_key' => $apiKey
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $postUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Posted data: $postData\n";
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 4: Invalid API Key
echo "4. Testing Invalid API Key...\n";
$invalidUrl = $baseUrl . '/input?sensor=temp&location=room1&value1=25.5&api_key=wrong_key';
echo "URL: $invalidUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $invalidUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

echo "=== Test Complete ===\n";
?>
