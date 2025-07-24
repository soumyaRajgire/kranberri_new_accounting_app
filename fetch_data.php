<?php
include("config.php");

$categories = [];
$companies = [];

// Fetch Categories
$categoryQuery = "SELECT category_name FROM categories";
$result = $conn->query($categoryQuery);
while ($row = $result->fetch_assoc()) {
    $categories[] = ['name' => $row['category_name']];
}

// Fetch Companies
$companyQuery = "SELECT company_name FROM companies";
$result = $conn->query($companyQuery);
while ($row = $result->fetch_assoc()) {
    $companies[] = ['name' => $row['company_name']];
}

// Return JSON Response
header('Content-Type: application/json');
echo json_encode(['categories' => $categories, 'companies' => $companies]);
?>