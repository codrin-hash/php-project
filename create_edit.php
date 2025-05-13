<?php
    require_once 'database/Database.php';
    $config = require 'database/config_db.php';

    $db = new Database($config['database']);
    $pdo = $db->conn;

    // Preia produsul pentru editare daca s-a trimis id
    $productToEdit = [
        'id' => '',
        'name' => '',
        'description' => '',
        'price' => '',
        'availability_date' => '',
        'stock_status' => 0,
        'image' => ''
    ];

    // Se efectueaza cererea GET pentru id
    if (isset($_GET['id'])) {
        $stmt = $db->query("SELECT * FROM products WHERE id = :id", [':id' => $_GET['id']]);
        $productToEdit = $stmt->fetch() ?? $productToEdit;
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create & Edit Product</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/product_form.js" defer></script>
    </head>
    <body>

        <h1>Product Management System</h1>

        <?php include 'navbar.php'; ?>

        <h2>Create Product</h2>
        <form id="create-form" class="grid-form" enctype="multipart/form-data">
            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Description:</label><br>
            <textarea name="description"></textarea><br><br>

            <label>Price (€):</label><br>
            <input type="number" name="price" step="0.01" required><br><br>

            <label>Image:</label><br>
            <input type="file" name="image"><br><br>

            <label>Availability Date:</label><br>
            <input type="date" name="availability_date"><br><br>

            <label><input type="checkbox" name="stock_status" value="1"> In Stock</label><br><br>

            <button type="submit">Create Product</button>
        </form>

        <hr>

        <h2>Edit Product</h2>
        <form id="edit-form" class="grid-form" enctype="multipart/form-data">
            <label>Choose Product ID:</label><br>
            <select name="id" id="edit-id" required>
                <option value="">-- Select Product ID --</option>
                <?php
                $ids = $db->query("SELECT id FROM products ORDER BY id")->fetchAll();
                foreach ($ids as $row) {
                    echo "<option value=\"{$row['id']}\">Product #{$row['id']}</option>";
                }
                ?>
            </select><br><br>

            <label>Name:</label><br>
            <input type="text" name="name" id="edit-name" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" id="edit-description"></textarea><br><br>

            <label>Price (€):</label><br>
            <input type="number" name="price" step="0.01" id="edit-price" required><br><br>

            <label>Image:</label><br>
            <input type="file" name="image"><br><br>

            <label>Availability Date:</label><br>
            <input type="date" name="availability_date" id="edit-date"><br><br>

            <label class="checkbox-inline">
                <input type="checkbox" name="stock_status" value="1">
                In Stock
            </label>

            <button type="submit">Update Product</button>
        </form>

        <div id="form-message"></div>

    </body>
</html>
