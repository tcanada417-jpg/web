<?php
require_once 'config.php';
require_once 'header.php';
if(!isset($_SESSION['client_id'])) { header("Location: login.php"); exit; }

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM materiel WHERE nom LIKE ? OR description LIKE ?";
$like = "%$search%";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$products = $stmt->get_result();
?>
<div class="container">
    <h2>Nos parfums</h2>
    <form method="get" style="margin-bottom:30px; display:flex; gap:10px;">
        <input type="text" name="search" placeholder="Rechercher un parfum..." value="<?= htmlspecialchars($search) ?>" style="flex:1;">
        <button type="submit" class="btn">🔍 Rechercher</button>
    </form>
    <div class="products-grid">
        <?php while($p = $products->fetch_assoc()): ?>
        <div class="card">
            <a href="details.php?id=<?= $p['id'] ?>"><div class="product-img" style="background-image: url('images/<?= $p['image'] ?>');"></div></a>
            <div class="product-info">
                <h3><?= htmlspecialchars($p['nom']) ?></h3>
                <div class="price"><?= $p['prix'] ?> €</div>
                <a href="details.php?id=<?= $p['id'] ?>" class="btn">Voir détails</a>
                <a href="cart.php?add=<?= $p['id'] ?>" class="btn btn-secondary">Ajouter au panier</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>