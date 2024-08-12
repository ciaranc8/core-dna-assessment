<?php

require_once 'http-client.php';

// Step 1: Get the authentication token
$client = new HttpClient('http://localhost:8000/mock-server.php');
$client->setMethod('OPTIONS');

try {
    $response = $client->sendRequest();
    $authToken = $response['headers']['Authorization'];
    echo "Authorization Token: $authToken\n";
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

// Step 2: Submit the data
$client->setMethod('POST');
$client->setHeaders(['Authorization' => $authToken]);
$client->setPayload([
    'name' => 'Ciaran Callaghan',
    'email' => 'ciarancallaghan1995@gmail.com',
    'url' => 'https://github.com/ciaranc8/core-dna-assessment/tree/main',
]);

try {
    $response = $client->sendRequest();
    print_r($response);
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
