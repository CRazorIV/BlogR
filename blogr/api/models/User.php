<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $avatar;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register new user
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                (name, email, password, avatar)
                VALUES
                (:name, :email, :password, :avatar)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":avatar", $this->avatar);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login user
    public function login($email, $password)
    {
        $query = "SELECT id, name, email, password, avatar 
                FROM " . $this->table_name . "
                WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->avatar = $row['avatar'];
                return true;
            }
        }
        return false;
    }

    // Check if email exists
    public function emailExists()
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}