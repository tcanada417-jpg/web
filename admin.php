<?php
require_once 'config.php';
if(!isset($_SESSION['admin_logged_in'])) header("Location: admin_login.php");

// Delete product
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM materiel WHERE id = $id");
    header("Location: admin.php?msg=deleted");
}

// Filter orders
$filter = "";
$params = [];
if(!empty($_GET['filter_product'])) {
    $filter .= " AND m.id = ?";
    $params[] = intval($_GET['filter_product']);
}
if(!empty($_GET['filter_date'])) {
    $filter .= " AND DATE(c.date_commande) = ?";
    $params[] = $_GET['filter_date'];
}

$sql = "SELECT c.id, cl.nom, cl.prenom, c.date_commande, c.total, GROUP_CONCAT(CONCAT(m.nom,' (',l.quantite,')') SEPARATOR ', ') as produits 
        FROM commande_client c 
        JOIN client cl ON c.client_id = cl.id
        JOIN commande_lignes l ON c.id = l.commande_id
        JOIN materiel m ON l.produit_id = m.id
        WHERE 1=1 $filter
        GROUP BY c.id ORDER BY c.date_commande DESC";
$stmt = $conn->prepare($sql);
if(count($params)) $stmt->bind_param(str_repeat('i', count($params)), ...$params);
$stmt->execute();
$orders = $stmt->get_result();

$products = $conn->query("SELECT id, nom FROM materiel");
require_once 'header.php';
?>
<div class="container admin-panel">
    <h2>Administration Parfums Shop</h2>
    <div style="display:flex; gap:15px; margin-bottom:20px;">
        <a href="add_product.php" class="btn">➕ Ajouter un parfum</a>
        <a href="admin.php" class="btn">🔄 Rafraîchir</a>
    </div>

    <h3>État du stock</h3>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Prix</th><th>Prix d'achat</th><th>Stock</th><th>Actions</th></tr>
        <?php $stock = $conn->query("SELECT * FROM materiel ORDER BY stock ASC");
        while($p = $stock->fetch_assoc()): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nom']) ?></td>
            <td><?= $p['prix'] ?> €</td>
            <td><?= $p['prix_achat'] ?> €</td>
            <td style="<?= $p['stock']<5 ? 'color:red;font-weight:bold' : '' ?>"><?= $p['stock'] ?></td>
            <td><a href="edit_product.php?id=<?= $p['id'] ?>">✏️</a> | <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Supprimer ?')">🗑️</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h3 style="margin-top:40px;">Commandes clients</h3>
    <form method="get" style="display:flex; gap:15px; margin-bottom:20px;">
        <select name="filter_product">
            <option value="">-- Tous les produits --</option>
            <?php $prod = $conn->query("SELECT id, nom FROM materiel"); while($r=$prod->fetch_assoc()): ?>
            <option value="<?= $r['id'] ?>" <?= isset($_GET['filter_product']) && $_GET['filter_product']==$r['id'] ? 'selected' : '' ?>><?= $r['nom'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="date" name="filter_date" value="<?= $_GET['filter_date'] ?? '' ?>">
        <button type="submit" class="btn-secondary btn">Filtrer</button>
        <a href="admin.php" class="btn">Réinitialiser</a>
    </form>
    <table>
        <tr><th>Commande #</th><th>Client</th><th>Date</th><th>Total</th><th>Produits</th></tr>
        <?php while($ord = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $ord['id'] ?></td>
            <td><?= htmlspecialchars($ord['prenom']." ".$ord['nom']) ?></td>
            <td><?= $ord['date_commande'] ?></td>
            <td><?= $ord['total'] ?> €</td>
            <td><?= htmlspecialchars($ord['produits']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><strong>⚠️ Produits à commander (stock < 5) :</strong> 
        <?php $low = $conn->query("SELECT nom, stock FROM materiel WHERE stock < 5");
        while($l=$low->fetch_assoc()) echo $l['nom']." (".$l['stock'].") "; ?>
    </p>
</div>
<?php require_once 'footer.php'; ?>