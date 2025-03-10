<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and post model
include_once '../config/Database.php';
include_once '../models/Post.php';

// Initialize database and post object
$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

// Get post ID and user ID
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->post_id) && !empty($data->user_id)) {
    $post->id = $data->post_id;

    // Check if user owns the post
    if ($post->isOwnedBy($data->user_id)) {
        // Delete the post
        if ($post->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Post was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete post."));
        }
    } else {
        http_response_code(403);
        echo json_encode(array("message" => "You are not authorized to delete this post."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete post. Data is incomplete."));
}