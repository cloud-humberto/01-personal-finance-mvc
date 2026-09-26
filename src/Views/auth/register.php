<?php
$pageTitle = 'Sign Up — FinanFlow';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create Your Free Account ✨</h2>
            <p>Start tracking and managing your cash flow easily.</p>
        </div>

        <form action="/register" method="POST" class="auth-form">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    placeholder="John Doe" 
                    required 
                    autocomplete="name"
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="john@example.com" 
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
                    placeholder="Minimum 6 characters" 
                    minlength="6"
                    required 
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Create Account & Enter Dashboard
            </button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="/login">Sign In</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
