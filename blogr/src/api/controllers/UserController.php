<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    // Get all users
    public function getAll() {
        $stmt = $this->user->getAll();
        $users = $stmt->fetchAll();
        
        return [
            'status' => 'success',
            'count' => count($users),
            'data' => $users
        ];
    }

    // Get user by ID
    public function getById($id) {
        $this->user->id = $id;
        
        if ($this->user->getById()) {
            return [
                'status' => 'success',
                'data' => [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'email' => $this->user->email,
                    'created_at' => $this->user->created_at,
                    'updated_at' => $this->user->updated_at
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'User not found'
            ];
        }
    }

    // Create user
    public function create($data) {
        // Validate required fields
        if (empty($data->username) || empty($data->email) || empty($data->password)) {
            return [
                'status' => 'error',
                'message' => 'Missing required fields'
            ];
        }

        // Check if email already exists
        $this->user->email = $data->email;
        if ($this->user->emailExists()) {
            return [
                'status' => 'error',
                'message' => 'Email already exists'
            ];
        }

        // Set user properties
        $this->user->username = $data->username;
        $this->user->email = $data->email;
        $this->user->password = $data->password;

        // Create user
        if ($this->user->create()) {
            return [
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'email' => $this->user->email
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to create user'
            ];
        }
    }

    // Update user
    public function update($id, $data) {
        // Validate required fields
        if (empty($data->username) || empty($data->email)) {
            return [
                'status' => 'error',
                'message' => 'Missing required fields'
            ];
        }

        // Set user properties
        $this->user->id = $id;
        $this->user->username = $data->username;
        $this->user->email = $data->email;

        // Update user
        if ($this->user->update()) {
            return [
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'email' => $this->user->email
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to update user'
            ];
        }
    }

    // Delete user
    public function delete($id) {
        $this->user->id = $id;

        if ($this->user->delete()) {
            return [
                'status' => 'success',
                'message' => 'User deleted successfully'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to delete user'
            ];
        }
    }
} 