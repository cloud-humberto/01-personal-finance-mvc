<?php
$pageTitle = 'Dashboard — FinanFlow';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="dashboard-header">
    <div>
        <h1 class="page-title">Hello, <?= htmlspecialchars($user['name']) ?>! 👋</h1>
        <p class="page-subtitle">Track and optimize your personal finances in real-time.</p>
    </div>
    <div class="dashboard-actions">
        <button type="button" class="btn btn-primary" onclick="openTransactionModal()">
            <span class="btn-icon">+</span> New Transaction
        </button>
    </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
    <div class="metric-card metric-balance">
        <div class="metric-icon">💰</div>
        <div class="metric-info">
            <span class="metric-label">Current Balance</span>
            <h3 class="metric-value <?= $summary['balance'] >= 0 ? 'text-positive' : 'text-negative' ?>">
                $ <?= number_format($summary['balance'], 2, '.', ',') ?>
            </h3>
        </div>
    </div>

    <div class="metric-card metric-income">
        <div class="metric-icon">📈</div>
        <div class="metric-info">
            <span class="metric-label">Total Income</span>
            <h3 class="metric-value text-positive">
                + $ <?= number_format($summary['income'], 2, '.', ',') ?>
            </h3>
        </div>
    </div>

    <div class="metric-card metric-expense">
        <div class="metric-icon">📉</div>
        <div class="metric-info">
            <span class="metric-label">Total Expenses</span>
            <h3 class="metric-value text-negative">
                - $ <?= number_format($summary['expense'], 2, '.', ',') ?>
            </h3>
        </div>
    </div>
</div>

<!-- Charts & Summary -->
<div class="dashboard-analytics-grid">
    <div class="panel-card">
        <div class="panel-header">
            <h3>Expenses Breakdown by Category</h3>
        </div>
        <div class="chart-container">
            <?php if (empty($expensesByCategory)): ?>
                <div class="empty-state">
                    <p>No expenses recorded yet to render breakdown chart.</p>
                </div>
            <?php else: ?>
                <canvas id="expensesChart" height="220"></canvas>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-header">
            <h3>Quick Insights</h3>
        </div>
        <div class="summary-list">
            <div class="summary-item">
                <span>Recorded Transactions:</span>
                <strong><?= count($transactions) ?> entries</strong>
            </div>
            <div class="summary-item">
                <span>Savings Rate:</span>
                <strong>
                    <?= $summary['income'] > 0 
                        ? round((($summary['income'] - $summary['expense']) / $summary['income']) * 100, 1) . '%' 
                        : '0%' 
                    ?>
                </strong>
            </div>
            <div class="summary-item">
                <span>Database Engine:</span>
                <span class="badge badge-tech">SQLite 3 (PDO)</span>
            </div>
            <div class="summary-item">
                <span>Architecture:</span>
                <span class="badge badge-tech">Custom MVC (PHP 8.2+)</span>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="panel-card mt-4">
    <div class="panel-header flex-between">
        <h3>Recent Transactions</h3>
        <span class="badge"><?= count($transactions) ?> entries</span>
    </div>

    <?php if (empty($transactions)): ?>
        <div class="empty-state">
            <div class="empty-icon">📂</div>
            <h4>No transactions found</h4>
            <p>Click the "+ New Transaction" button above to add your first record.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($t['date'])) ?></td>
                            <td><strong><?= htmlspecialchars($t['description']) ?></strong></td>
                            <td>
                                <span class="category-tag" style="background-color: <?= htmlspecialchars($t['category_color']) ?>20; color: <?= htmlspecialchars($t['category_color']) ?>">
                                    <?= htmlspecialchars($t['category_name']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['type'] === 'income'): ?>
                                    <span class="badge badge-success">Income</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Expense</span>
                                <?php endif; ?>
                            </td>
                            <td class="<?= $t['type'] === 'income' ? 'text-positive' : 'text-negative' ?> font-semibold">
                                <?= $t['type'] === 'income' ? '+' : '-' ?> $ <?= number_format((float)$t['amount'], 2, '.', ',') ?>
                            </td>
                            <td class="text-right">
                                <form action="/transactions/delete" method="POST" onsubmit="return confirm('Are you sure you want to remove this transaction?');" style="display:inline;">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn-icon-danger" title="Delete Transaction">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- New Transaction Modal -->
<div class="modal-overlay" id="transactionModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>New Transaction Record</h3>
            <button class="modal-close" onclick="closeTransactionModal()">&times;</button>
        </div>
        <form action="/transactions/create" method="POST" class="modal-form">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label>Transaction Type</label>
                <div class="radio-pill-group">
                    <label class="radio-pill">
                        <input type="radio" name="type" value="expense" checked onchange="filterCategories('expense')">
                        <span class="pill-label pill-expense">📉 Expense</span>
                    </label>
                    <label class="radio-pill">
                        <input type="radio" name="type" value="income" onchange="filterCategories('income')">
                        <span class="pill-label pill-income">📈 Income</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" placeholder="e.g. Monthly Salary, Groceries, Cloud Hosting" required>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="amount">Amount ($)</label>
                    <input type="number" step="0.01" min="0.01" id="amount" name="amount" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group col-6">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" data-type="<?= $cat['type'] ?>">
                            <?= htmlspecialchars($cat['name']) ?> (<?= ucfirst($cat['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeTransactionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.chartData = <?= json_encode($expensesByCategory) ?>;
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
