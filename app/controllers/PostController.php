<?php
namespace App\Controllers;

use App\Core\Controller;

class PostController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = $this->model('Post');
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $posts = $this->postModel->getAllPosts($page);
        
        $this->view('posts/index', [
            'posts' => $posts,
            'currentPage' => $page
        ]);
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $imagePath = $this->handleFileUpload($_FILES['image']);
            }

            // Create post
            $result = $this->postModel->createPost(
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['content'],
                $imagePath
            );

            if ($result) {
                $_SESSION['flash_message'] = 'Post created successfully';
                $this->redirect('/');
            }
        }

        $this->view('posts/create');
    }

    private function handleFileUpload($file) {
        // Add file upload logic here
    }
} 