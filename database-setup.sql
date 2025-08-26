-- SQLite schema for Admin Genie
-- This file will be used by simple-setup.php to initialize the database

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT 0,
    remember_token VARCHAR(100),
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Companies table
CREATE TABLE IF NOT EXISTS companies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL,
    website VARCHAR(255) NULL,
    logo VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Employees table
CREATE TABLE IF NOT EXISTS employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    company_id INTEGER NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NULL,
    hire_date DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    UNIQUE(company_id, email)
);

-- Activity logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    action VARCHAR(255) NOT NULL,
    subject_type VARCHAR(255) NULL,
    subject_id INTEGER NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create indexes
CREATE INDEX IF NOT EXISTS activity_logs_user_id_index ON activity_logs(user_id);
CREATE INDEX IF NOT EXISTS activity_logs_subject_type_subject_id_index ON activity_logs(subject_type, subject_id);
CREATE INDEX IF NOT EXISTS employees_company_id_index ON employees(company_id);

-- Insert admin user (password is 'password')
INSERT INTO users (name, email, password, is_admin, created_at, updated_at)
VALUES (
    'Admin',
    'admin@admin.com',
    '$2y$12$3X0KZ6gBQpLKBLqpJ8.H1.41F0Vd1S6UXEQwK/TF8wVYvPQZ.YhGi',
    1,
    datetime('now'),
    datetime('now')
);