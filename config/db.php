<?php
/**
 * Database connection (included by every page that uses the database).
 *
 * Settings come from the environment variables DB_HOST, DB_PORT, DB_NAME,
 * DB_USER and DB_PASS; the defaults match a stock local XAMPP install.
 *
 * If the database does not exist yet, it is created with the tables from
 * database.sql (without the sample records). Import database.sql yourself to
 * get the sample records and the default test account.
 */

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = (int) (getenv('DB_PORT') ?: 3306);
$dbName = getenv('DB_NAME') ?: 'users_db';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

$dbOptions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

/** Log the real error for the developer and show visitors a friendly page. */
function db_fail(string $context, Throwable $e): never
{
    error_log("[faculty-system] database {$context} failed: " . $e->getMessage());
    http_response_code(503);
    exit('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Database unavailable</title></head>'
        . '<body style="font-family:system-ui,sans-serif;max-width:560px;margin:4rem auto;padding:0 1rem">'
        . '<h1>Database unavailable</h1><p>The system cannot connect to the database. '
        . 'Make sure MySQL is running in the XAMPP Control Panel, then reload this page.</p></body></html>');
}

try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, $dbOptions);
} catch (PDOException $e) {
    // Error 1049 = unknown database: create it on first run. Anything else is a real failure.
    if ((int) $e->getCode() !== 1049) {
        db_fail('connection', $e);
    }
    try {
        $safeName = str_replace('`', '', $dbName);
        $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};charset=utf8mb4", $dbUser, $dbPass, $dbOptions);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$safeName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$safeName}`");

        // Run only the CREATE TABLE statements from database.sql.
        $sql = file_get_contents(__DIR__ . '/../database.sql');
        foreach (preg_split('/;\s*\n/', $sql) as $statement) {
            $statement = trim(preg_replace('/^\s*--.*$/m', '', $statement));
            if (stripos($statement, 'CREATE TABLE') === 0) {
                $pdo->exec($statement);
            }
        }
    } catch (PDOException $e) {
        db_fail('creation', $e);
    }
}

unset($dbHost, $dbPort, $dbName, $dbUser, $dbPass, $dbOptions, $safeName, $sql, $statement);
