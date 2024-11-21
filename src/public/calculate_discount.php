<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Services\DiscountCalculator;

// Ensure the request is a POST request
if ($_SERVER[ 'REQUEST_METHOD' ] === 'POST') {
    // Get the raw POST data and decode the JSON
    $input = file_get_contents('php://input');
    // Validate input JSON
    if (isset($input)) {
        $calculator = new DiscountCalculator();
        try {
            $discounts = $calculator->calculate($input);


            echo json_encode($discounts, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            exit();
        } catch (JsonException $e) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON'], JSON_THROW_ON_ERROR);
            exit;
        }
    } else {
        // Bad request
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request'], JSON_THROW_ON_ERROR);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed'], JSON_THROW_ON_ERROR);
}