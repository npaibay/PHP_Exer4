<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\SessionManager;
use App\Models\Subject;

class SubjectController extends Controller
{
    private Subject $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new Subject();
    }

    public function list(): void
    {
        Auth::requireLogin();

        $searchText = isset($_GET['search']) ? trim($_GET['search']) : '';

        $currentUser = Auth::currentUser();
        $role = $currentUser['account_type'] ?? '';
        $canManage = in_array($role, ['admin', 'staff'], true);

        $result = $this->subjectModel->search($searchText);

        $this->view('subjects/list', compact('result', 'searchText', 'canManage'));
    }

    public function create(): void
    {
        Auth::requireAdminOrStaff();
        $this->view('subjects/new');
    }

    public function store(): void
    {
        Auth::requireAdminOrStaff();

        $code = trim($_POST['code'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $unit = trim($_POST['unit'] ?? '');

        $error = '';
        $unitInt = (int) $unit;

        if ($code === '' || $title === '' || !is_numeric($unit) || $unitInt <= 0) {
            $error = 'Please fill all fields correctly. Unit must be a number greater than 0.';
        } elseif ($this->subjectModel->codeExists($code)) {
            $error = "Subject code '{$code}' already exists!";
        } else {
            $createdBy = (int) SessionManager::get('user_id');

            if ($this->subjectModel->create($code, $title, $unitInt, $createdBy)) {
                SessionManager::set('flash_success', 'Subject added successfully.');
                $this->redirect('index.php?controller=subject&action=list');
            } else {
                $error = 'Failed to add subject.';
            }
        }

        $this->view('subjects/new', compact('error', 'code', 'title', 'unit'));
    }

    public function edit(): void
    {
        Auth::requireAdminOrStaff();

        $id = (int) ($_GET['subject_id'] ?? 0);
        $subject = $this->subjectModel->getById($id);

        if (!$subject) {
            SessionManager::set('flash_error', 'Subject not found.');
            $this->redirect('index.php?controller=subject&action=list');
        }

        $this->view('subjects/edit', compact('subject'));
    }

    public function update(): void
    {
        Auth::requireAdminOrStaff();

        $id = (int) ($_GET['subject_id'] ?? 0);

        $code = trim($_POST['code'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $unit = trim($_POST['unit'] ?? '');

        $error = '';
        $unitInt = (int) $unit;

        if ($code === '' || $title === '' || !is_numeric($unit) || $unitInt <= 0) {
            $error = 'Please fill all fields correctly. Unit must be a number greater than 0.';
        } elseif ($this->subjectModel->codeExists($code, $id)) {
            $error = "Subject code '{$code}' already exists!";
        } else {
            $updatedBy = (int) SessionManager::get('user_id');

            if ($this->subjectModel->update($id, $code, $title, $unitInt, $updatedBy)) {
                SessionManager::set('flash_success', 'Subject updated successfully.');
                $this->redirect('index.php?controller=subject&action=list');
            } else {
                $error = 'Failed to update subject.';
            }
        }

        $subject = [
            'subject_id' => $id,
            'code' => $code,
            'title' => $title,
            'unit' => $unit,
        ];

        $this->view('subjects/edit', compact('error', 'subject'));
    }
}