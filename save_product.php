<?php
header('Content-Type: application/json');

require_once 'database/Database.php';
$config = require 'database/config_db.php';
$db = new Database($config['database']);
$pdo = $db->conn;

// Colecteaza datele din POST
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? '';
$availability = $_POST['availability_date'] ?? null;
$stock_status = isset($_POST['stock_status']) ? 1 : 0;
$id = $_POST['id'] ?? null;

$errors = [];

// Validare in plus fata de validarea basic din html
if ($name === '') $errors[] = 'Name is required.';
if (!is_numeric($price) || $price <= 0) $errors[] = 'Price must be a positive number.';

$imageName = null;

// Procesarea imaginii
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) {
        $errors[] = 'Invalid image format. Allowed: jpg, jpeg, png, gif.';
    } else {
        $imageName = uniqid('img_') . '.' . $ext;
        move_uploaded_file($fileTmp, 'assets/uploads/' . $imageName);
    }
}

// Tratarea erorilor - returneaza raspuns JSON
if (!empty($errors)) {
    $response = ['success' => false, 'errors' => $errors];

    // Aceasta linie suprascrie JSON-ul la fiecare obiect adaugat si afiseaza un singur raspuns dupa care isi da refresh
    file_put_contents('assets/responses/save_log.json', json_encode($response, JSON_PRETTY_PRINT));
    
    echo json_encode($response);
    exit;
}


if (empty($id)) {
    // INSERT
    $sql = "INSERT INTO products (name, description, price, image, availability_date, stock_status)
            VALUES (:name, :description, :price, :image, :availability_date, :stock_status)";
} else {
    // UPDATE
    $sql = "UPDATE products SET
                name = :name,
                description = :description,
                price = :price,
                availability_date = :availability_date,
                stock_status = :stock_status" .
                ($imageName ? ", image = :image" : "") . "
            WHERE id = :id";
}

// Parametri primiti
$params = [
    ':name' => $name,
    ':description' => $description,
    ':price' => $price,
    ':availability_date' => $availability,
    ':stock_status' => $stock_status
];

if ($imageName) {
    $params[':image'] = $imageName;
}

if (!empty($id)) {
    $params[':id'] = $id;
}

$db->query($sql, $params);

// Se returneaza un raspuns JSON in urma efectuarii operatiei
$response = ['success' => true];
file_put_contents('assets/responses/save_log.json', json_encode($response, JSON_PRETTY_PRINT));
echo json_encode($response);

?>