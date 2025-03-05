<?php
namespace App\Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = "../app/views/{$view}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View {$view} not found");
        }
    }

    protected function model($model) {
        $modelFile = "../app/models/{$model}.php";
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $modelClass = "App\\Models\\{$model}";
            return new $modelClass();
        }
        die("Model {$model} not found");
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
} 