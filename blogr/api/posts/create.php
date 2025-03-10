<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and post model
include_once '../config/Database.php';
include_once '../models/Post.php';

// Initialize database and post object
$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->title) &&
    !empty($data->content) &&
    !empty($data->excerpt) &&
    !empty($data->author_id)
) {
    // Set post property values
    $post->title = $data->title;
    $post->content = $data->content;
    $post->excerpt = $data->excerpt;
    $post->featured_image = $data->featured_image ?? '';
    $post->author_id = $data->author_id;

    // Create the post
    if ($post->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Post was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create post."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create post. Data is incomplete."));
}