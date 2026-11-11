<?php
$host     = 'localhost';
$db_name  = 'users_db';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    try {
        $temp_pdo = new PDO("mysql:host={$host};charset=utf8mb4", $username, $password);
        $temp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $temp_pdo->exec("USE `{$db_name}`;");
        $temp_pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
            `firstname` VARCHAR(100) NOT NULL,
            `middlename` VARCHAR(100) DEFAULT NULL,
            `lastname` VARCHAR(100) NOT NULL,
            `username` VARCHAR(100) NOT NULL UNIQUE,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $ex) {
        die("<strong>Database Connection Failed:</strong> " . htmlspecialchars($ex->getMessage()));
    }
}

// Create the faculty table and migrate the first version of the module when needed.
try {
    $userColumns = [];
    foreach ($pdo->query('SHOW COLUMNS FROM users')->fetchAll() as $column) $userColumns[$column['Field']] = true;
    if (isset($userColumns['uid']) && !isset($userColumns['id'])) {
        $pdo->exec('ALTER TABLE users CHANGE `uid` `id` INT(11) NOT NULL AUTO_INCREMENT');
        $userColumns['id'] = true;
    }
    if (!isset($userColumns['middlename'])) $pdo->exec("ALTER TABLE users ADD `middlename` VARCHAR(100) DEFAULT NULL AFTER `firstname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `faculty` (
        `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        `firstname` VARCHAR(100) NOT NULL,
        `middlename` VARCHAR(100) DEFAULT NULL,
        `lastname` VARCHAR(100) NOT NULL,
        `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
        `department` VARCHAR(100) NOT NULL,
        `position` VARCHAR(100) NOT NULL,
        `contact_num` VARCHAR(30) NOT NULL,
        `gender` VARCHAR(20) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `employment_status` VARCHAR(50) NOT NULL DEFAULT 'Permanent',
        `specialization` VARCHAR(150) NOT NULL,
        `date_hired` DATE NOT NULL,
        `birthday` DATE NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $columns = [];
    foreach ($pdo->query('SHOW COLUMNS FROM faculty')->fetchAll() as $column) {
        $columns[$column['Field']] = true;
    }
    $renames = [
        'faculty_id' => 'id', 'first_name' => 'firstname', 'middle_name' => 'middlename',
        'last_name' => 'lastname', 'birthdate' => 'birthday', 'sex' => 'gender',
        'contact_number' => 'contact_num'
    ];
    foreach ($renames as $old => $new) {
        if (isset($columns[$old]) && !isset($columns[$new])) {
            $type = $new === 'id' ? 'INT(11) NOT NULL AUTO_INCREMENT' : ($new === 'birthday' ? 'DATE NOT NULL' : ($new === 'middlename' ? 'VARCHAR(100) DEFAULT NULL' : 'VARCHAR(100) NOT NULL'));
            $pdo->exec("ALTER TABLE faculty CHANGE `$old` `$new` $type");
            $columns[$new] = true;
        }
    }
    $missing = [
        'status' => "VARCHAR(50) NOT NULL DEFAULT 'Active'",
        'employment_status' => "VARCHAR(50) NOT NULL DEFAULT 'Permanent'",
        'specialization' => "VARCHAR(150) NOT NULL DEFAULT ''"
    ];
    foreach ($missing as $name => $definition) {
        if (!isset($columns[$name])) $pdo->exec("ALTER TABLE faculty ADD `$name` $definition");
    }
} catch (PDOException $e) {
    // The application will show the database error when a faculty operation is attempted.
}
?>
