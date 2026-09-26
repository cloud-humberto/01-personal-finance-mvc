<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Category;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Session::get('user');

        $summary = Transaction::getSummary($user['id']);
        $recentTransactions = Transaction::getByUser($user['id'], 15);
        $categories = Category::all();
        $expensesByCategory = Transaction::getExpensesByCategory($user['id']);

        $this->render('dashboard/index', [
            'user'               => $user,
            'summary'            => $summary,
            'transactions'       => $recentTransactions,
            'categories'         => $categories,
            'expensesByCategory' => $expensesByCategory
        ]);
    }
}
