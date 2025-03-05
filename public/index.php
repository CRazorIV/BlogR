<?php
require_once '../config/config.php';
require_once '../app/core/autoload.php';

$router = new App\Core\Router();

// Define routes
$router->add('', ['controller' => 'Post', 'action' => 'index']);
$router->add('posts/create', ['controller' => 'Post', 'action' => 'create']);
$router->add('login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('register', ['controller' => 'Auth', 'action' => 'register']);

// Dispatch the route
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
    // Handle 404 or other errors
    echo '404 Not Found';
} 