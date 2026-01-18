# Smart Wallet MVC - Personal Finance Management Application


---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Current Status](#current-status)
3. [Getting Started](#getting-started)
4. [Features](#features)
5. [Security](#security)
6. [Architecture](#architecture)
7. [Documentation](#documentation)
8. [Development](#development)
9. [Testing](#testing)
10. [License](#license)

---

## 🎯 Project Overview

Smart Wallet is a personal finance web application that helps users manage their income and expenses. The application provides:

- **User Management:** Secure registration and login
- **Income Tracking:** Record and categorize income
- **Expense Tracking:** Record and categorize expenses
- **Dashboard:** View financial statistics and balance
- **Category Management:** Organize transactions by category

### Technology Stack

- **Backend:** PHP 7.4+ (Native OOP, no framework)
- **Database:** PostgreSQL with PDO
- **Architecture:** MVC (Model-View-Controller)
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Security:** CSRF tokens, password hashing, prepared statements

### Key Principles

- ✅ **MVC Architecture** - Clean separation of concerns
- ✅ **SOLID Principles** - Professional code design
- ✅ **DRY (Don't Repeat Yourself)** - Reusable code patterns
- ✅ **Security-First** - Best practices throughout
- ✅ **Enterprise-Level** - Production-ready code quality

## 🏃 Getting Started

### Prerequisites

- PHP 7.4 or higher
- PostgreSQL 10 or higher
- Web server (Apache, Nginx, or built-in PHP server)
- PDO PHP extension enabled

### Installation

1. **Clone/Setup Project**
   ```bash
   cd C:\laragon\www\smartWalletMvc
   ```

2. **Configure Database**
   - Update `config/config.php` with your database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'smartWallet');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     ```

3. **Create Database Schema**
   ```bash
   # Run the SQL from database/database.sql in your PostgreSQL client
   psql -U postgres -d smartWallet -f database/database.sql
   ```

4. **Start Web Server**
   ```bash
   # If using PHP built-in server
   php -S localhost:8000 -t public
   ```

5. **Access Application**
   ```
   http://localhost/smartWalletMvc/public/
   ```

---

## ✨ Features

### Phase 1: Authentication System ✅

#### User Registration
- Create new account with name, email, password
- Email validation and uniqueness checking
- Password strength validation (minimum 6 characters)
- Password confirmation matching
- CSRF token protection
- Secure password hashing

**Route:** `GET /auth/register`  
**Method:** POST to `/auth/handleRegister`

#### User Login
- Authenticate with email and password
- Password verification using password_verify()
- Session regeneration for security
- CSRF token protection
- Clear error messages without info leakage

**Route:** `GET /auth/login`  
**Method:** POST to `/auth/handleLogin`

#### User Logout
- Secure session destruction
- Cookie cleanup
- Session variable clearing
- Proper redirect

**Route:** `GET /auth/logout`

---

## 🔒 Security

### Implemented Security Measures

**CSRF Protection**
- Token generation using `random_bytes(32)`
- Tokens stored in `$_SESSION`
- Validation using `hash_equals()` (constant-time comparison)
- Required on all form submissions

**Password Security**
- Hashing with `password_hash()` using PASSWORD_DEFAULT algorithm
- Verification with `password_verify()`
- Minimum 6 character requirement
- Never stored or logged in plain text

**SQL Injection Prevention**
- All queries use prepared statements
- Parameter binding with PDO
- No dynamic SQL string concatenation

**XSS (Cross-Site Scripting) Prevention**
- Output escaping with `htmlspecialchars()`
- ENT_QUOTES flag for all HTML context
- UTF-8 encoding enforced
- Input tag stripping

**Session Security**
- Session regeneration on login: `session_regenerate_id(true)`
- Secure session destruction with cookie cleanup
- Session-based message handling
- No sensitive data in cookies

### Security Checklist

- ✅ Prepared statements on all SQL queries
- ✅ Password hashing with password_hash()
- ✅ CSRF token protection on all forms
- ✅ Output sanitization with htmlspecialchars()
- ✅ Session regeneration on login
- ✅ No hardcoded credentials in public files
- ✅ Error messages don't leak information
- ✅ Input validation on all fields
