<?php
declare(strict_types=1);
use App\Core\Session;
$flashSuccess = Session::getFlash('success');
$flashError = Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'FinanFlow — Intelligent Personal Finance Tracker' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="app-header">
        <div class="container header-container">
            <a href="/" class="brand-logo">
                <span class="logo-icon">💎</span>
                <span class="logo-text">Finan<strong>Flow</strong></span>
                <span class="badge-mvc">MVC</span>
            </a>

            <nav class="header-nav">
                <?php if (isset($currentUser) && $currentUser): ?>
                    <div class="user-pill">
                        <span class="user-avatar"><?= strtoupper(substr($currentUser['name'], 0, 1)) ?></span>
                        <span class="user-name"><?= htmlspecialchars($currentUser['name']) ?></span>
                    </div>
                    <a href="/logout" class="btn btn-outline-danger btn-sm">Log out</a>
                <?php else: ?>
                    <a href="/login" class="nav-link">Sign In</a>
                    <a href="/register" class="btn btn-primary btn-sm">Get Started Free</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success" id="alert-msg">
                    <span><?= htmlspecialchars($flashSuccess) ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($flashError): ?>
                <div class="alert alert-danger" id="alert-msg">
                    <span><?= htmlspecialchars($flashError) ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
