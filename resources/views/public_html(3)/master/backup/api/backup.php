<?php
$host = 'localhost';
$db = 'cooperative';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $backupFile = $db . date("Y-m-d-H-i-s") . '.sql';
    $output = fopen($backupFile, 'w');

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        $rows = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_keys($rows[0]);

        fputcsv($output, $columnNames);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    echo "Database backup successfully created";
} catch (PDOException $e) {
    echo "Error creating database backup: " . $e->getMessage();
}
?>