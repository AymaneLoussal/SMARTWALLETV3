CREATE table users(
    id SERIAL PRIMARY KEY ,
    full_name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE incomes (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,  
    description VARCHAR(255),
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE expenses (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL, 
    description VARCHAR(255),
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Categories table (added to match Category model)
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- SEED DATA: System User & Predefined Categories
-- ============================================

-- System/Default User (id=1) for predefined categories
-- Password: 'demo' hashed with PASSWORD_DEFAULT (PHP)
INSERT INTO users (id, full_name, email, password) VALUES
(1, 'System', 'system@smartwallet.local', '$2y$10$YIjlrJ5Z.vZQ8Z9Z9Z9Z9e0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z0Z');

-- Income Categories (available to all users - user_id 1 is system/default)
INSERT INTO categories (user_id, name) VALUES (1, 'Salary');
INSERT INTO categories (user_id, name) VALUES (1, 'Bonus');
INSERT INTO categories (user_id, name) VALUES (1, 'Investment');
INSERT INTO categories (user_id, name) VALUES (1, 'Freelance');
INSERT INTO categories (user_id, name) VALUES (1, 'Gift');
INSERT INTO categories (user_id, name) VALUES (1, 'Other Income');

-- Expense Categories (available to all users - user_id 1 is system/default)
INSERT INTO categories (user_id, name) VALUES (1, 'Food');
INSERT INTO categories (user_id, name) VALUES (1, 'Rent');
INSERT INTO categories (user_id, name) VALUES (1, 'Transport');
INSERT INTO categories (user_id, name) VALUES (1, 'Shopping');
INSERT INTO categories (user_id, name) VALUES (1, 'Entertainment');
INSERT INTO categories (user_id, name) VALUES (1, 'Bills');
INSERT INTO categories (user_id, name) VALUES (1, 'Healthcare');
INSERT INTO categories (user_id, name) VALUES (1, 'Other Expense');
