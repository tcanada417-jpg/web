<?php
// ==================== search.php ====================
require_once 'config.php';
require_once 'header.php';

// Only logged-in clients can access the shop/search
if(!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit;
}

$search_term = $_GET['q'] ?? '';
$results = [];

if(!empty($search_term)) {
    $like = "%$search_term%";
    $stmt = $conn->prepare("SELECT * FROM materiel WHERE nom LIKE ? OR description LIKE ?");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<div class="container">
    <h2>Recherche de parfums</h2>
    <form method="get" action="search.php" style="margin-bottom: 30px; display: flex; gap: 10px;">
        <input type="text" name="q" placeholder="Nom ou description..." value="<?= htmlspecialchars($search_term) ?>" style="flex: 1; padding: 12px;" required>
        <button type="submit" class="btn">🔍 Rechercher</button>
    </form>

    <?php if(!empty($search_term)): ?>
        <?php if($results->num_rows > 0): ?>
            <h3><?= $results->num_rows ?> résultat(s) trouvé(s) pour "<?= htmlspecialchars($search_term) ?>"</h3>
            <div class="products-grid">
                <?php while($p = $results->fetch_assoc()): ?>
                <div class="card">
                    <a href="details.php?id=<?= $p['id'] ?>">
                        <div class="product-img" style="background-image: url('images/<?= htmlspecialchars($p['image']) ?>');"></div>
                    </a>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['nom']) ?></h3>
                        <div class="price"><?= number_format($p['prix'], 2) ?> €</div>
                        <a href="details.php?id=<?= $p['id'] ?>" class="btn">Voir détails</a>
                        <a href="cart.php?add=<?= $p['id'] ?>" class="btn btn-secondary">Ajouter au panier</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="alert">Aucun parfum ne correspond à "<?= htmlspecialchars($search_term) ?>".</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Entrez un mot-clé pour trouver votre parfum idéal.</p>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>