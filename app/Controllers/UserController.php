<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\SessionManager;
use App\Models\User;
use App\Helpers\Hash;
use App\Helpers\Validator;

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function list(): void
    {
        Auth::requireAdmin();

        $result = $this->userModel->getAll();
        $this->view('users/list', compact('result'));
    }

    public function create(): void
    {
        Auth::requireAdmin();
        $this->view('users/new');
    }

    public function store(): void
    {
        Auth::requireAdmin();

        $error = '';
        $username = '';
        $accountType = 'student';

        $validRoles = ['admin', 'staff', 'teacher', 'student'];

        $username = trim($_POST['username'] ?? '');
        $accountType = $_POST['account_type'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!Validator::required($username) || !Validator::required($password) || !Validator::required($confirmPassword)) {
            $error = 'Please fill in all fields.';
        } elseif (!in_array($accountType, $validRoles, true)) {
            $error = 'Invalid account type.';
        } elseif (!Validator::minLength($password, 8)) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } elseif ($this->userModel->usernameExists($username)) {
            $error = 'Username already exists.';
        } else {
            $passwordHash = Hash::make($password);
            $createdBy = (int) SessionManager::get('user_id');

            if ($this->userModel->create($username, $passwordHash, $accountType, $createdBy)) {
                SessionManager::set('flash_success', 'User added successfully.');
                $this->redirect('index.php?controller=user&action=list');
            } else {
                $error = 'Failed to create user.';
            }
        }

        $this->view('users/new', compact('error', 'username', 'accountType', 'validRoles'));
    }

    public function edit(): void
    {
        Auth::requireAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $user = $this->userModel->getById($id);

        if (!$user) {
            SessionManager::set('flash_error', 'User not found.');
            $this->redirect('index.php?controller=user&action=list');
        }

        $validRoles = ['admin', 'staff', 'teacher', 'student'];

        $this->view('users/edit', compact('user', 'validRoles'));
    }

    public function update(): void
    {
        Auth::requireAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $error = '';
        $validRoles = ['admin', 'staff', 'teacher', 'student'];

        $username = trim($_POST['username'] ?? '');
        $accountType = $_POST['account_type'] ?? '';

        if ($username === '') {
            $error = 'Username is required.';
        } elseif (!in_array($accountType, $validRoles, true)) {
            $error = 'Invalid account type.';
        } elseif ($this->userModel->usernameExists($username, $id)) {
            $error = 'Username already exists.';
        } else {
            $updatedBy = (int) SessionManager::get('user_id');

            if ($this->userModel->update($id, $username, $accountType, $updatedBy)) {
                SessionManager::set('flash_success', 'User updated successfully.');
                $this->redirect('index.php?controller=user&action=list');
            } else {
                $error = 'Failed to update user.';
            }
        }

        $user = [
            'id' => $id,
            'username' => $username,
            'account_type' => $accountType
        ];

        $this->view('users/edit', compact('error', 'user', 'validRoles'));
    }

    public function changePassword(): void
    {
        Auth::requireLogin();
        $this->view('users/change_password');
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();

        $error = '';

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

        if (!Validator::required($currentPassword) ||
            !Validator::required($newPassword) ||
            !Validator::required($confirmNewPassword)) {
            $error = 'Please fill in all fields.';
        } elseif (!Validator::minLength($newPassword, 8)) {
            $error = 'New password must be at least 8 characters.';
        } elseif ($newPassword !== $confirmNewPassword) {
            $error = 'New passwords do not match.';
        } else {
            $userId = (int) SessionManager::get('user_id');
            $user = $this->userModel->getById($userId);

            if (!$user) {
                $error = 'User account not found.';
            } else {
                $userWithPassword = $this->userModel->getByUsername($user['username']);

                if (!$userWithPassword || !Hash::verify($currentPassword, $userWithPassword['password'])) {
                    $error = 'Current password is incorrect.';
                } else {
                    $newPasswordHash = Hash::make($newPassword);

                    if ($this->userModel->updatePassword($userId, $newPasswordHash, $userId)) {
                        SessionManager::set('flash_success', 'Password updated successfully.');
                        $this->redirect('index.php?controller=home&action=index');
                    } else {
                        $error = 'Failed to update password.';
                    }
                }
            }
        }

        $this->view('users/change_password', compact('error'));
    }
}