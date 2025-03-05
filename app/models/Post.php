<?php
namespace App\Models;

use App\Core\Database;

class Post {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPosts($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare("
            SELECT 
                p.*, 
                u.username, 
                u.profile_image,
                COUNT(DISTINCT c.comment_id) as comment_count,
                COALESCE(SUM(v.vote_type), 0) as vote_score
            FROM posts p
            LEFT JOIN users u ON p.user_id = u.user_id
            LEFT JOIN comments c ON p.post_id = c.post_id
            LEFT JOIN votes v ON p.post_id = v.post_id
            GROUP BY p.post_id
            ORDER BY p.created_at DESC
            LIMIT :offset, :limit
        ");

        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function createPost($userId, $title, $content, $imagePath = null) {
        $stmt = $this->db->prepare("
            INSERT INTO posts (user_id, title, content, image_path) 
            VALUES (:user_id, :title, :content, :image_path)
        ");

        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':content' => $content,
            ':image_path' => $imagePath
        ]);
    }
} 