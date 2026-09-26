<?php
$pageTitle = 'Login — FinanFlow';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back 👋</h2>
            <p>Access your dashboard to track personal income and expenses.</p>
        </div>

        <form action="/login" method="POST" class="auth-form">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="user@example.com" 
                    required 
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="••••••••" 
                    required 
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Sign In to Dashboard
            </button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account yet? <a href="/register">Sign up for free</a></p>
            <div class="demo-credentials">
                <span class="badge">💡 Testing Tip</span>
                <p>Register a quick test account in 5 seconds to explore the dashboard!</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
