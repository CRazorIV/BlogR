<?php
// Include database and config files
require_once 'config.php';
require_once 'database.php';

// Set headers for JSON response
header("Content-Type: application/json; charset=UTF-8");

try {
    // Create database instance and connect
    $database = new Database();
    $db = $database->connect();
    
    if ($db) {
        // Connection successful
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Database connection successful',
            'database' => DB_NAME,
            'host' => DB_HOST,
            'username' => DB_USERNAME
        ]);
    } else {
        // Connection failed
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed'
        ]);
    }
} catch (Exception $e) {
    // Exception occurred
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
} 