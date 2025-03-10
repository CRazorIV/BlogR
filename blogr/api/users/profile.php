<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database and models
include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../models/Post.php';

// Initialize database
$database = new Database();
$db = $database->getConnection();

// Get username from URL
$username = isset($_GET['username']) ? $_GET['username'] : die();

// Get user data
$query = "SELECT id, name, email, avatar FROM users WHERE name = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(":username", $username);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get user's posts
    $post = new Post($db);
    $query = "SELECT 
                p.id, p.title, p.excerpt, p.featured_image, p.content,
                p.created_at, p.updated_at,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
            FROM posts p
            WHERE p.author_id = :author_id AND p.status = 'published'
            ORDER BY p.created_at DESC";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":author_id", $user_data['id']);
    $stmt->execute();

    $posts = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Get categories for this post
        $post->id = $row['id'];
        $categories_stmt = $post->getCategories();
        $categories = array();

        while ($category_row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($categories, array(
                "id" => $category_row['id'],
                "name" => $category_row['name']
            ));
        }

        array_push($posts, array_merge($row, array("categories" => $categories)));
    }

    // Get user stats
    $stats_query = "SELECT 
        (SELECT COUNT(*) FROM posts WHERE author_id = :author_id AND status = 'published') as posts_count,
        (SELECT COUNT(*) FROM likes l JOIN posts p ON l.post_id = p.id WHERE p.author_id = :author_id) as total_likes,
        (SELECT COUNT(*) FROM comments c JOIN posts p ON c.post_id = p.id WHERE p.author_id = :author_id) as total_comments";

    $stats_stmt = $db->prepare($stats_query);
    $stats_stmt->bindParam(":author_id", $user_data['id']);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

    // Create response
    $response = array(
        "user" => $user_data,
        "posts" => $posts,
        "stats" => $stats
    );

    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "User not found."));
}