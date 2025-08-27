<?php
echo "<h1>Fixing Employees Table Structure</h1>\n";

try {
    $dbPath = dirname(__DIR__) . '/database/database.sqlite';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get current columns
    $columns = $pdo->query("PRAGMA table_info(employees)")->fetchAll(PDO::FETCH_COLUMN, 1);
    echo "<p>Current columns: " . implode(', ', $columns) . "</p>\n";
    
    // Add missing columns
    $missingColumns = [
        'position' => 'VARCHAR(100)',
        'department' => 'VARCHAR(100)',
        'hire_date' => 'DATE',
        'salary' => 'DECIMAL(10,2)',
        'status' => 'VARCHAR(50) DEFAULT "active"',
        'phone' => 'VARCHAR(50)',
        'created_at' => 'TIMESTAMP NULL',
        'updated_at' => 'TIMESTAMP NULL'
    ];
    
    foreach ($missingColumns as $column => $type) {
        try {
            $pdo->exec("ALTER TABLE employees ADD COLUMN $column $type");
            echo "<p>✓ Added column: $column</p>\n";
        } catch (Exception $e) {
            echo "<p>ℹ Column $column may already exist</p>\n";
        }
    }
    
    // Add sample data if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO employees (company_id, first_name, last_name, email, phone, position, department, hire_date, salary, status, created_at, updated_at) VALUES 
        (1, 'John', 'Doe', 'john.doe@techsolutions.com', '+1-555-0001', 'Senior Developer', 'Engineering', '2020-01-15', 85000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        (1, 'Jane', 'Smith', 'jane.smith@techsolutions.com', '+1-555-0002', 'Product Manager', 'Product', '2019-03-20', 95000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        (2, 'Mike', 'Johnson', 'mike.johnson@globalmfg.com', '+1-555-0003', 'Production Supervisor', 'Operations', '2015-06-10', 65000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        (3, 'Sarah', 'Wilson', 'sarah.wilson@creativestudio.com', '+1-555-0004', 'Lead Designer', 'Design', '2021-02-28', 75000.00, 'active', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        echo "<p>✓ Added sample employee data</p>\n";
    }
    
    echo "<h2>✓ Employees table fixed successfully!</h2>\n";
    echo "<p>Next steps:</p>\n";
    echo "<ol>\n";
    echo "<li>Try creating a new employee to test if it works</li>\n";
    echo "<li>If everything works, you can delete this file</li>\n";
    echo "<li>If you still have issues, check the error message and we can adjust the fix</li>\n";
    echo "</ol>\n";
    
} catch (Exception $e) {
    echo "<p>⚠ Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
