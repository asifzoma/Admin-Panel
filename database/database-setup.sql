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
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    logo VARCHAR(255),
    website VARCHAR(255),
    industry VARCHAR(100),
    founded_year INTEGER,
    employee_count INTEGER,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Employees table
CREATE TABLE IF NOT EXISTS employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    company_id INTEGER NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(50),
    position VARCHAR(100),
    department VARCHAR(100),
    hire_date DATE,
    salary DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Activity logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    subject_type VARCHAR(255),
    subject_id INTEGER,
    event VARCHAR(255) NOT NULL,
    description TEXT,
    properties TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Password reset tokens table
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Personal access tokens table
CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Failed jobs table
CREATE TABLE IF NOT EXISTS failed_jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_companies_name ON companies(name);
CREATE INDEX IF NOT EXISTS idx_employees_company_id ON employees(company_id);
CREATE INDEX IF NOT EXISTS idx_employees_email ON employees(email);
CREATE INDEX IF NOT EXISTS idx_activity_logs_user_id ON activity_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_activity_logs_subject ON activity_logs(subject_type, subject_id);
CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);
CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_token ON personal_access_tokens(token);

-- Insert default admin user
INSERT OR IGNORE INTO users (name, email, password, is_admin, created_at, updated_at) VALUES 
('Admin User', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insert sample companies
INSERT OR IGNORE INTO companies (name, email, phone, address, industry, founded_year, employee_count, status, created_at, updated_at) VALUES 
('Tech Solutions Inc', 'info@techsolutions.com', '+1-555-0123', '123 Tech Street, Silicon Valley, CA', 'Technology', 2015, 150, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Global Manufacturing', 'contact@globalmfg.com', '+1-555-0456', '456 Industrial Ave, Detroit, MI', 'Manufacturing', 1990, 500, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Creative Design Studio', 'hello@creativestudio.com', '+1-555-0789', '789 Creative Blvd, New York, NY', 'Design', 2018, 25, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- Insert sample employees
INSERT OR IGNORE INTO employees (company_id, first_name, last_name, email, phone, position, department, hire_date, salary, status, created_at, updated_at) VALUES 
(1, 'John', 'Doe', 'john.doe@techsolutions.com', '+1-555-0001', 'Senior Developer', 'Engineering', '2020-01-15', 85000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1, 'Jane', 'Smith', 'jane.smith@techsolutions.com', '+1-555-0002', 'Product Manager', 'Product', '2019-03-20', 95000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 'Mike', 'Johnson', 'mike.johnson@globalmfg.com', '+1-555-0003', 'Production Supervisor', 'Operations', '2015-06-10', 65000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 'Sarah', 'Wilson', 'sarah.wilson@creativestudio.com', '+1-555-0004', 'Lead Designer', 'Design', '2021-02-28', 75000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
