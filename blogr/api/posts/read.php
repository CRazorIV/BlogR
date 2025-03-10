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

// Read posts
$stmt = $post->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $posts_arr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Get categories for this post
        $post->id = $id;
        $categories_stmt = $post->getCategories();
        $categories = array();

        while ($category_row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($categories, array(
                "id" => $category_row['id'],
                "name" => $category_row['name']
            ));
        }

        $post_item = array(
            "id" => $id,
            "title" => $title,
            "content" => $content,
            "excerpt" => $excerpt,
            "featured_image" => $featured_image,
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "author" => array(
                "id" => $author_id,
                "name" => $author_name,
                "avatar" => $author_avatar
            ),
            "categories" => $categories,
            "likes_count" => $likes_count,
            "comments_count" => $comments_count
        );

        array_push($posts_arr, $post_item);
    }

    http_response_code(200);
    echo json_encode($posts_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No posts found."));
}