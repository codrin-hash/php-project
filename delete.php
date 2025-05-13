<?php
    require_once 'database/Database.php';
    $config = require 'database/config_db.php';

    $db = new Database($config['database']);
    $products = $db->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete Products</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/delete_product.js" defer></script>
    </head>
    <body>

    <h1>Product Management System</h1>
    
    <?php include 'navbar.php'; ?>

    <h2>Delete Products</h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Availability</th>
                    <th>In Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <?php foreach ($products as $p): ?>
                    <tr data-id="<?= $p['id'] ?>">
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td><?= number_format($p['price'], 2) ?> €</td>
                        <td>
                            <?php if (!empty($p['image'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($p['image']) ?>" alt="Image" style="max-width: 60px;">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['availability_date']) ?></td>
                        <td><?= $p['stock_status'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <button class="delete-btn" data-id="<?= $p['id'] ?>">🗑 Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="delete-message" class="form-message"></div>

    </body>
</html>
