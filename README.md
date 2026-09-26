# 💰 FinanFlow — Personal Finance Management System (MVC)

> **Portfolio Project for Junior PHP Developer Applications**  
> A complete web application built with a **custom, handcrafted MVC architecture** without framework overhead, demonstrating a solid grasp of core web development concepts behind tools like Laravel and Symfony.

---

## 🚀 Key Technical Competencies

- **MVC (Model-View-Controller) Architecture**: Strict separation of concerns between business domain logic, application control flow, and user interface.
- **Modern PHP 8+ & OOP**: Strict typing (`declare(strict_types=1);`), namespaces, abstract classes, singletons, and reusable static helpers.
- **PSR-4 Compliant Autoloading**: Native class loading using `spl_autoload_register`.
- **Persistence with PDO & SQLite**: Prepared statements ensuring complete immunity against SQL Injections, relational integrity (`PRAGMA foreign_keys = ON`), transactions, and automatic database boot-seeding.
- **Web Application Security**:
  - Secure password hashing using `password_hash()` with standard Bcrypt (`cost: 12`).
  - Active protection against **CSRF (Cross-Site Request Forgery)** using session token validation.
  - Output sanitization with `htmlspecialchars()` preventing **XSS**.
  - Secure session cookies (`HttpOnly`, `SameSite=Lax`).
- **Modern & Responsive UI**: SaaS-style Dark Mode design, interactive doughnut charts using **Chart.js**, and fluid modal windows.

---

## 📁 Project Structure

```text
01-personal-finance-mvc/
├── database/
│   └── finances.sqlite        # SQLite database automatically generated on boot
├── public/                    # Web server document root
│   ├── css/
│   │   └── style.css          # Custom CSS design system
│   ├── js/
│   │   └── app.js             # Chart.js initialization and modal interactions
│   └── index.php              # Front Controller (Application entry point)
├── src/
│   ├── Controllers/
│   │   ├── AuthController.php          # Sign in, Sign up, and Session destruction
│   │   ├── DashboardController.php     # Metrics consolidation & view assembly
│   │   └── TransactionController.php   # Transaction store & delete actions
│   ├── Core/
│   │   ├── Controller.php      # Base controller (rendering, json, redirects)
│   │   ├── Database.php        # PDO Singleton and initial table schema
│   │   ├── Router.php          # Lightweight HTTP router
│   │   └── Session.php         # Session management, flash alerts, and CSRF
│   ├── Models/
│   │   ├── Category.php        # Financial category definitions
│   │   ├── Transaction.php     # Income/expense transactions logic
│   │   └── User.php            # User authentication & persistence
│   └── Views/
│       ├── auth/               # Login & Register views
│       ├── dashboard/          # Dashboard metrics & transaction tables
│       └── layouts/            # Reusable header and footer layouts
└── README.md
```

---

## 🛠️ How to Run Locally

### Prerequisites
- PHP 8.1+ with the `pdo_sqlite` extension enabled.

### Run in 1 Step:
Open your terminal inside the project directory and launch PHP's built-in development server pointing to `public`:

```bash
cd 01-personal-finance-mvc
php -S localhost:8000 -t public
```

Open in your browser:
👉 **[http://localhost:8000](http://localhost:8000)**

> **Note:** On initial load, the SQLite database (`database/finances.sqlite`) will be created and seeded automatically with default categories (Salary, Freelance, Food & Dining, Housing, Transportation, etc.).

---

## 🎯 Features

1. **Full Authentication System**:
   - Secure account registration with input validation.
   - Session login and logout with flash alerts.
2. **Interactive Financial Dashboard**:
   - Live metric cards (Current Balance, Total Income, Total Expenses).
   - Dynamic doughnut chart displaying expense percentages by category.
   - Savings rate calculation indicator.
3. **Transaction Management**:
   - Fast modal creation with live category filtering between Income and Expense.
   - Recent records table ordered by date with CSRF-protected deletion.
