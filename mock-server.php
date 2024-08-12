<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Authorization: Bearer mock-token');
    echo json_encode(['message' => 'OPTIONS request received']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['name']) && isset($input['email']) && isset($input['url'])) {
        echo json_encode(['status' => 'success', 'data' => $input]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
