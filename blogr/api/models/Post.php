<?php
class Post
{
    private $conn;
    private $table_name = "posts";

    public $id;
    public $title;
    public $content;
    public $excerpt;
    public $featured_image;
    public $author_id;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all posts with author and category information
    public function read()
    {
        $query = "
            SELECT 
                p.id, p.title, p.excerpt, p.featured_image, p.content,
                p.created_at, p.updated_at,
                u.id as author_id, u.name as author_name, u.avatar as author_avatar,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
            FROM
                " . $this->table_name . " p
                LEFT JOIN users u ON p.author_id = u.id
            WHERE
                p.status = 'published'
            ORDER BY
                p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Get single post with all details
    public function readOne()
    {
        $query = "
            SELECT 
                p.id, p.title, p.content, p.excerpt, p.featured_image,
                p.created_at, p.updated_at,
                u.id as author_id, u.name as author_name, u.avatar as author_avatar,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comments_count
            FROM
                " . $this->table_name . " p
                LEFT JOIN users u ON p.author_id = u.id
            WHERE
                p.id = :id AND p.status = 'published'
            LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $stmt;
    }

    // Get categories for a post
    public function getCategories()
    {
        $query = "
            SELECT 
                c.id, c.name
            FROM
                categories c
                JOIN post_categories pc ON c.id = pc.category_id
            WHERE
                pc.post_id = :post_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":post_id", $this->id);
        $stmt->execute();

        return $stmt;
    }

    // Create new post
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                (title, content, excerpt, featured_image, author_id, status)
                VALUES
                (:title, :content, :excerpt, :featured_image, :author_id, 'published')";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->excerpt = htmlspecialchars(strip_tags($this->excerpt));
        $this->featured_image = htmlspecialchars(strip_tags($this->featured_image));

        // Bind parameters
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":excerpt", $this->excerpt);
        $stmt->bindParam(":featured_image", $this->featured_image);
        $stmt->bindParam(":author_id", $this->author_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}