<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Session::has('user')) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login');
    }

    public function login(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::validateCsrfToken($token)) {
            Session::setFlash('error', 'Invalid security token (CSRF).');
            $this->redirect('/login');
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            Session::setFlash('error', 'Please enter a valid email address and password.');
            $this->redirect('/login');
        }

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password_hash'])) {
            Session::setFlash('error', 'Invalid credentials.');
            $this->redirect('/login');
        }

        // Successfully logged in
        Session::set('user', [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email']
        ]);

        Session::setFlash('success', "Welcome back, {$user['name']}!");
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (Session::has('user')) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/register');
    }

    public function register(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::validateCsrfToken($token)) {
            Session::setFlash('error', 'Invalid security token (CSRF).');
            $this->redirect('/register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (mb_strlen($name) < 3) {
            Session::setFlash('error', 'Name must contain at least 3 characters.');
            $this->redirect('/register');
        }

        if (!$email) {
            Session::setFlash('error', 'Please provide a valid email address.');
            $this->redirect('/register');
        }

        if (mb_strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters long.');
            $this->redirect('/register');
        }

        if (User::findByEmail($email)) {
            Session::setFlash('error', 'This email is already registered.');
            $this->redirect('/register');
        }

        $userId = User::create($name, $email, $password);

        Session::set('user', [
            'id'    => $userId,
            'name'  => $name,
            'email' => $email
        ]);

        Session::setFlash('success', 'Account created successfully! Welcome to your financial dashboard.');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
