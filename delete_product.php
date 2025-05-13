<?php
    ob_clean();
    header('Content-Type: application/json');

    require_once 'database/Database.php';
    $config = require 'database/config_db.php';
    $db = new Database($config['database']);

    $id = $_POST['id'] ?? null;

    $response = [];

    if (!$id || !is_numeric($id)) {
        $response = ['success' => false, 'error' => 'Invalid ID'];
    } else {
        try {
            $db->query("DELETE FROM products WHERE id = :id", [':id' => $id]);
            $response = ['success' => true, 'id' => $id];
        } catch (Exception $e) {
            $response = ['success' => false, 'error' => 'Database error'];
        }
    }

    // Adaugare in JSON
    file_put_contents('assets/responses/delete_log.json', json_encode($response, JSON_PRETTY_PRINT));

    // Output final 
    echo json_encode($response);
