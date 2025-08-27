<?php
/**
 * Database Migration Script
 * This script safely updates the database schema without breaking existing data
 */

echo "<h1>Database Migration Script</h1>\n";
echo "<p>Fixing database schema issues...</p>\n";

$dbPath = dirname(__DIR__) . '/database/database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check what tables exist
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>✓ Found existing tables: " . implode(', ', $existingTables) . "</p>\n";
    
    // Check companies table structure
    if (in_array('companies', $existingTables)) {
        $columns = $pdo->query("PRAGMA table_info(companies)")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>✓ Companies table columns: " . implode(', ', $columns) . "</p>\n";
        
        // Add missing columns safely
        $missingColumns = [
            'phone' => 'VARCHAR(50)',
            'address' => 'TEXT',
            'logo' => 'VARCHAR(255)',
            'website' => 'VARCHAR(255)',
            'industry' => 'VARCHAR(100)',
            'founded_year' => 'INTEGER',
            'employee_count' => 'INTEGER',
            'status' => 'VARCHAR(50) DEFAULT "active"',
            'created_at' => 'TIMESTAMP NULL',
            'updated_at' => 'TIMESTAMP NULL'
        ];
        
        foreach ($missingColumns as $column => $type) {
            try {
                $pdo->exec("ALTER TABLE companies ADD COLUMN $column $type");
                echo "<p>✓ Added column: $column</p>\n";
            } catch (Exception $e) {
                echo "<p>ℹ Column $column may already exist</p>\n";
            }
        }
    }
    
    // Create other tables if they don't exist
    $tables = [
        'users' => "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            is_admin BOOLEAN DEFAULT 0,
            remember_token VARCHAR(100),
            last_login_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        )",
        
        'employees' => "CREATE TABLE IF NOT EXISTS employees (
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
        )",
        
        'activity_logs' => "CREATE TABLE IF NOT EXISTS activity_logs (
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
        )",
        
        'password_reset_tokens' => "CREATE TABLE IF NOT EXISTS password_reset_tokens (
            email VARCHAR(255) PRIMARY KEY,
            token VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL
        )",
        
        'personal_access_tokens' => "CREATE TABLE IF NOT EXISTS personal_access_tokens (
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
        )",
        
        'failed_jobs' => "CREATE TABLE IF NOT EXISTS failed_jobs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            uuid VARCHAR(255) NOT NULL UNIQUE,
            connection TEXT NOT NULL,
            queue TEXT NOT NULL,
            payload TEXT NOT NULL,
            exception TEXT NOT NULL,
            failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $tableName => $createSQL) {
        $pdo->exec($createSQL);
        echo "<p>✓ Created/verified table: $tableName</p>\n";
    }
    
    // Create indexes
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)",
        "CREATE INDEX IF NOT EXISTS idx_companies_name ON companies(name)",
        "CREATE INDEX IF NOT EXISTS idx_employees_company_id ON employees(company_id)",
        "CREATE INDEX IF NOT EXISTS idx_employees_email ON employees(email)",
        "CREATE INDEX IF NOT EXISTS idx_activity_logs_user_id ON activity_logs(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_activity_logs_subject ON activity_logs(subject_type, subject_id)",
        "CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_tokenable ON personal_access_tokens(tokenable_type, tokenable_id)",
        "CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_token ON personal_access_tokens(token)"
    ];
    
    foreach ($indexes as $indexSQL) {
        try {
            $pdo->exec($indexSQL);
            echo "<p>✓ Created index</p>\n";
        } catch (Exception $e) {
            echo "<p>ℹ Index may already exist</p>\n";
        }
    }
    
    // Insert default admin user if not exists
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $pdo->exec("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES 
        ('Admin User', 'admin@admin.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        echo "<p>✓ Inserted default admin user</p>\n";
    }
    
    // Insert sample companies if not exists
    $companyCount = $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn();
    if ($companyCount == 0) {
        $pdo->exec("INSERT INTO companies (name, email, phone, address, industry, founded_year, employee_count, status, created_at, updated_at) VALUES 
        ('Tech Solutions Inc', 'info@techsolutions.com', '+1-555-0123', '123 Tech Street, Silicon Valley, CA', 'Technology', 2015, 150, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        ('Global Manufacturing', 'contact@globalmfg.com', '+1-555-0456', '456 Industrial Ave, Detroit, MI', 'Manufacturing', 1990, 500, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        ('Creative Design Studio', 'hello@creativestudio.com', '+1-555-0789', '789 Creative Blvd, New York, NY', 'Design', 2018, 25, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        echo "<p>✓ Inserted sample companies</p>\n";
    }
    
    echo "<p>✓ Database migration completed successfully!</p>\n";
    echo "<p><a href='/fix-employees-table.php'>Next: Fix Employees Table</a></p>\n";
    
} catch (Exception $e) {
    echo "<p>⚠ Database migration error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
