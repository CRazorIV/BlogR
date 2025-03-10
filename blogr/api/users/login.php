<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and user model
include_once '../config/Database.php';
include_once '../models/User.php';

// Initialize database and user object
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    if ($user->login($data->email, $data->password)) {
        // Create array of user data
        $user_data = array(
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "avatar" => $user->avatar
        );

        http_response_code(200);
        echo json_encode(array(
            "message" => "Login successful.",
            "user" => $user_data
        ));
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Invalid email or password."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Email and password are required."));
}