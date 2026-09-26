<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function store(): void
    {
        $this->requireAuth();
        $user = Session::get('user');

        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::validateCsrfToken($token)) {
            Session::setFlash('error', 'Invalid security token (CSRF).');
            $this->redirect('/dashboard');
        }

        $description = trim($_POST['description'] ?? '');
        $amount = (float) ($_POST['amount'] ?? 0);
        $type = $_POST['type'] ?? 'expense';
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');

        if (empty($description)) {
            Session::setFlash('error', 'Description is required.');
            $this->redirect('/dashboard');
        }

        if ($amount <= 0) {
            Session::setFlash('error', 'Amount must be greater than zero.');
            $this->redirect('/dashboard');
        }

        if (!in_array($type, ['income', 'expense'], true)) {
            Session::setFlash('error', 'Invalid transaction type.');
            $this->redirect('/dashboard');
        }

        Transaction::create($user['id'], $categoryId, $description, $amount, $type, $date);

        Session::setFlash('success', 'Transaction saved successfully!');
        $this->redirect('/dashboard');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $user = Session::get('user');

        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::validateCsrfToken($token)) {
            Session::setFlash('error', 'Invalid security token (CSRF).');
            $this->redirect('/dashboard');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Transaction::delete($id, $user['id']);
            Session::setFlash('success', 'Transaction deleted successfully.');
        }

        $this->redirect('/dashboard');
    }
}
