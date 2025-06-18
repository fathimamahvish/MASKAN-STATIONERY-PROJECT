<?php
require_once 'vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id_token'])) {
    echo json_encode(['success' => false, 'message' => 'No ID token provided.']);
    exit;
}

$client = new Google_Client(['client_id' => 'YOUR_CLIENT_ID_HERE']); // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($input['id_token']);

if ($payload) {
    $email = $payload['email'];
    $name = $payload['name'];
    $picture = $payload['picture'];

    // Now login or register the user using your DB
    // Example: search in DB using $email

    // If user exists, log them in
    // If not, create user and then log in

    echo json_encode(['success' => true, 'message' => 'Login successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID token.']);
}
