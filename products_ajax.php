<?php
    // Stabileste conexiunea la DB
    if (!isset($db)) {
        require_once 'database/Database.php';
        $config = require 'database/config_db.php';
        $db = new Database($config['database']);
    }

    // Setarile pentru paginare si search
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    $search = $_GET['search'] ?? '';
    $params = [];

    $filter = '';
    if ($search !== '') {
        $filter = "WHERE name LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    $sql = "SELECT * FROM products $filter LIMIT :limit OFFSET :offset";
    $countSql = "SELECT COUNT(*) AS total FROM products $filter";

    $paramsWithLimits = $params;
    $paramsWithLimits[':limit'] = $limit;
    $paramsWithLimits[':offset'] = $offset;

    $stmt = $db->query($sql, $paramsWithLimits);
    $products = $stmt->fetchAll();

    // Total pagini
    $countStmt = $db->query($countSql, $params);
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total / $limit);
?>

<div class="table-container">
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
                            <?= $p['image'] ? '<img src="assets/uploads/' . htmlspecialchars($p['image']) . '">' : 'No image' ?>
                        </td>
                        <td><?= htmlspecialchars($p['availability_date']) ?></td>
                        <td><?= $p['stock_status'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="#" data-page="<?= $page - 1 ?>">&laquo; Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="#" data-page="<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="#" data-page="<?= $page + 1 ?>">Next &raquo;</a>
    <?php endif; ?>
</div>
