<?php
echo "Starting database setup...<br>";

// Initialize SQLite database
$dbPath = dirname(__DIR__) . '/database/database.sqlite';
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        is_admin BOOLEAN DEFAULT 0,
        remember_token VARCHAR(100),
        last_login_at TIMESTAMP NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");

    // Create companies table
    $pdo->exec("CREATE TABLE IF NOT EXISTS companies (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255),
        logo VARCHAR(255),
        website VARCHAR(255),
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");

    // Create employees table
    $pdo->exec("CREATE TABLE IF NOT EXISTS employees (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        company_id INTEGER,
        email VARCHAR(255),
        phone VARCHAR(255),
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
    )");

    // Create activity_logs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        description TEXT NOT NULL,
        user_id INTEGER,
        subject_type VARCHAR(255),
        subject_id INTEGER,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");

    // Insert admin user
    $adminPassword = password_hash('password', PASSWORD_BCRYPT);
    $now = date('Y-m-d H:i:s');
    
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['Admin', 'admin@admin.com', $adminPassword, 1, $now, $now]);

    echo "Database tables created successfully.<br>";
    echo "Admin user created with:<br>";
    echo "Email: admin@admin.com<br>";
    echo "Password: password<br>";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
    exit;
}

echo "<br>Setup completed successfully!<br>";
echo "Next steps:<br>";
echo "1. Delete simple-setup.php and cleanup-and-setup.php<br>";
echo "2. Rename both .htaccess.bak files back to .htaccess<br>";
echo "3. Try logging in with admin@admin.com / password<br>";
?>
