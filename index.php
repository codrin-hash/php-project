<?php
    require_once 'database/Database.php';
    $config = require 'database/config_db.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product List</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/script.js" defer></script>
    </head>
    <body>

        <h1>Product List</h1>

        <!-- Zona de search -->
        <form method="GET" action="index.php" id="search-form">
            <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Zona pentru afisarea tabelului si paginare (AJAX) -->
        <div id="product-list">
            <?php include 'products_ajax.php'; ?>
        </div>

    </body>
</html>
