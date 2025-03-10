<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database and post model
include_once '../config/Database.php';
include_once '../models/Post.php';

// Initialize database and post object
$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

// Get ID from URL
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

// Read single post
$stmt = $post->readOne();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get categories for this post
    $categories_stmt = $post->getCategories();
    $categories = array();

    while ($category_row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($categories, array(
            "id" => $category_row['id'],
            "name" => $category_row['name']
        ));
    }

    // Create post array
    $post_arr = array(
        "id" => $row['id'],
        "title" => $row['title'],
        "content" => $row['content'],
        "excerpt" => $row['excerpt'],
        "featured_image" => $row['featured_image'],
        "created_at" => $row['created_at'],
        "updated_at" => $row['updated_at'],
        "author" => array(
            "id" => $row['author_id'],
            "name" => $row['author_name'],
            "avatar" => $row['author_avatar']
        ),
        "categories" => $categories,
        "likes_count" => $row['likes_count'],
        "comments_count" => $row['comments_count']
    );

    http_response_code(200);
    echo json_encode($post_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Post not found."));
}