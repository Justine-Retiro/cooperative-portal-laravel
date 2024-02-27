<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Database connection
$host = 'localhost';
$db   = 'cooperative';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Query
$stmt = $pdo->query('SELECT c.*, la.*, lp.* FROM clients c LEFT JOIN loan_applications la ON c.account_number = la.account_number LEFT JOIN loan_payments lp ON la.loanNo = lp.loanNo');

// Column names
$columnNames = array('client_id', 'user_id', 'account_number', 'last_name', 'middle_name', 'first_name', 'citizenship', 'civil_status', 'city_address', 'provincial_address', 'mailing_address', 'account_status', 'phone_num', 'taxID_num', 'spouse_name', 'birth_date', 'birth_place', 'date_employed', 'position', 'natureOf_work', 'balance', 'amountOf_share', 'remarks', 'loan_id', 'loanNo', 'customer_name', 'age', 'birth_date', 'date_employed', 'contact_num', 'college', 'taxID_num', 'loan_type', 'work_position', 'retirement_year', 'application_date', 'applicant_sign', 'applicant_receipt', 'application_status', 'amount_before', 'amount_after', 'remarks', 'time_pay', 'loan_term_Type', 'dueDate', 'action_taken', 'payment_id', 'loanNo', 'remarks', 'amount_paid', 'payment_date', 'audit_description');
$columnIndex = 1;
foreach($columnNames as $columnName) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 1, $columnName);
    $columnIndex++;
}

// Fetch data from database and add it to the spreadsheet
$rowIndex = 2;
while ($row = $stmt->fetch()) {
    $columnIndex = 1;
    foreach($columnNames as $columnName) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $row[$columnName]);
        $columnIndex++;
    }
    $rowIndex++;
}

// Write an Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('clients_loans_payments.xlsx');