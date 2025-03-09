<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers for CORS and JSON response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include database and config files
require_once 'config.php';
require_once 'database.php';
require_once 'controllers/UserController.php';

// Get request method and URI
$request_method = $_SERVER["REQUEST_METHOD"];
$request_uri = $_SERVER["REQUEST_URI"];

// Remove base path from URI to get the endpoint
$base_path = API_BASE_URL;
$endpoint = str_replace($base_path, '', $request_uri);
$endpoint = trim($endpoint, '/');

// Parse endpoint parts
$endpoint_parts = explode('/', $endpoint);
$resource = $endpoint_parts[0] ?? '';
$id = $endpoint_parts[1] ?? null;

// Create database connection
$database = new Database();
$db = $database->connect();

// Test database connection
if ($resource === 'test-connection') {
    try {
        if ($db) {
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Database connection successful',
                'database' => DB_NAME
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database connection failed'
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Handle user endpoints
if ($resource === 'users') {
    $userController = new UserController($db);
    
    switch ($request_method) {
        case 'GET':
            if ($id) {
                // Get user by ID
                $result = $userController->getById($id);
                http_response_code($result['status'] === 'success' ? 200 : 404);
            } else {
                // Get all users
                $result = $userController->getAll();
                http_response_code(200);
            }
            echo json_encode($result);
            break;
            
        case 'POST':
            // Create user
            $data = json_decode(file_get_contents("php://input"));
            $result = $userController->create($data);
            http_response_code($result['status'] === 'success' ? 201 : 400);
            echo json_encode($result);
            break;
            
        case 'PUT':
            // Update user
            if ($id) {
                $data = json_decode(file_get_contents("php://input"));
                $result = $userController->update($id, $data);
                http_response_code($result['status'] === 'success' ? 200 : 400);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ]);
            }
            break;
            
        case 'DELETE':
            // Delete user
            if ($id) {
                $result = $userController->delete($id);
                http_response_code($result['status'] === 'success' ? 200 : 400);
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ]);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Method not allowed'
            ]);
            break;
    }
    exit;
}

// Handle other API endpoints here
// For now, return a 404 for any other endpoint
http_response_code(404);
echo json_encode([
    'status' => 'error',
    'message' => 'Endpoint not found'
]); 