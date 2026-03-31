<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\SessionManager;
use App\Models\Program;

class ProgramController extends Controller
{
    private Program $programModel;

    public function __construct()
    {
        $this->programModel = new Program();
    }

    public function list(): void
    {
        Auth::requireLogin();

        $searchText = isset($_GET['search']) ? trim($_GET['search']) : '';

        $currentUser = Auth::currentUser();
        $role = $currentUser['account_type'] ?? '';
        $canManage = in_array($role, ['admin', 'staff'], true);

        $result = $this->programModel->search($searchText);

        $this->view('programs/list', compact('result', 'searchText', 'canManage'));
    }

    public function create(): void
    {
        Auth::requireAdminOrStaff();
        $this->view('programs/new');
    }

    public function store(): void
    {
        Auth::requireAdminOrStaff();

        $code = trim($_POST['code'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $years = trim($_POST['years'] ?? '');

        $error = '';
        $yearsInt = (int) $years;

        if ($code === '' || $title === '' || !is_numeric($years) || $yearsInt < 1 || $yearsInt > 6) {
            $error = 'Please fill all fields correctly. Years must be between 1 and 6.';
        } elseif ($this->programModel->codeExists($code)) {
            $error = "Program code '{$code}' already exists.";
        } else {
            $createdBy = (int) SessionManager::get('user_id');

            if ($this->programModel->create($code, $title, $yearsInt, $createdBy)) {
                SessionManager::set('flash_success', 'Program added successfully.');
                $this->redirect('index.php?controller=program&action=list');
            } else {
                $error = 'Failed to add program.';
            }
        }

        $this->view('programs/new', compact('error', 'code', 'title', 'years'));
    }

    public function edit(): void
    {
        Auth::requireAdminOrStaff();

        $id = (int) ($_GET['program_id'] ?? 0);
        $program = $this->programModel->getById($id);

        if (!$program) {
            SessionManager::set('flash_error', 'Program not found.');
            $this->redirect('index.php?controller=program&action=list');
        }

        $this->view('programs/edit', compact('program'));
    }

    public function update(): void
    {
        Auth::requireAdminOrStaff();

        $id = (int) ($_GET['program_id'] ?? 0);

        $code = trim($_POST['code'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $years = trim($_POST['years'] ?? '');

        $error = '';
        $yearsInt = (int) $years;

        if ($code === '' || $title === '' || !is_numeric($years) || $yearsInt < 1 || $yearsInt > 6) {
            $error = 'Please fill all fields correctly. Years must be between 1 and 6.';
        } elseif ($this->programModel->codeExists($code, $id)) {
            $error = "Program code '{$code}' already exists.";
        } else {
            $updatedBy = (int) SessionManager::get('user_id');

            if ($this->programModel->update($id, $code, $title, $yearsInt, $updatedBy)) {
                SessionManager::set('flash_success', 'Program updated successfully.');
                $this->redirect('index.php?controller=program&action=list');
            } else {
                $error = 'Failed to update program.';
            }
        }

        $program = [
            'program_id' => $id,
            'code' => $code,
            'title' => $title,
            'years' => $years,
        ];

        $this->view('programs/edit', compact('error', 'program'));
    }
}