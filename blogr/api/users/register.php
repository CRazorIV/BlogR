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

if (
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    // Set user property values
    $user->name = htmlspecialchars(strip_tags($data->name));
    $user->email = htmlspecialchars(strip_tags($data->email));
    $user->password = $data->password;
    $user->avatar = $data->avatar ?? '';

    // Check if email already exists
    if ($user->emailExists()) {
        http_response_code(400);
        echo json_encode(array("message" => "Email already exists."));
        exit();
    }

    // Create the user
    if ($user->create()) {
        // Login the user to get their data
        if ($user->login($user->email, $data->password)) {
            $user_data = array(
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "avatar" => $user->avatar
            );

            http_response_code(201);
            echo json_encode(array(
                "message" => "User was created.",
                "user" => $user_data
            ));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "User was created but login failed."));
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}