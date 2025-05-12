<?php
    require 'database\Database.php';

    // Incarca configurarea bazei de date
    $config = require 'database\config_db.php';

    // Creeaza o instanta a bazei de date si obtine conexiunea
    $db = new Database($config['database']);
    $pdo = $db->conn;

    // Setari pentru paginare si cautare
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $params = [];
    $sql = "SELECT * FROM products";
    $countSql = "SELECT COUNT(*) FROM products";

    // Daca exista termen de cautare
    if ($search !== '') {
        $sql .= " WHERE name LIKE :search";
        $countSql .= " WHERE name LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

    // Numarul total de rezultate pentru paginare
    $countStmt = $pdo->prepare($countSql);
    foreach ($params as $key => $val) {
        $countStmt->bindValue($key, $val);
    }
    $countStmt->execute();
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total / $limit);
?>


<!-- Structura HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Product List</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>

        <h1>Product List</h1>

        <!-- Sectiunea Search -->
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Sectiunea Tabel -->
        <table>
            <thead>
                <tr>
                    <th>Name</th><th>Description</th><th>Price</th>
                    <th>Image</th><th>Availability</th><th>In Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6">No products found.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['description']) ?></td>
                            <td><?= number_format($p['price'], 2) ?> €</td>
                            <td>
                                <?php if ($p['image']): ?>
                                    <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="Image">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['availability_date']) ?></td>
                            <td><?= $p['stock_status'] ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">⟨ Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"
                class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next ⟩</a>
            <?php endif; ?>
        </div>

    </body>
</html>