<?php
require_once 'config.php';
require_once 'header.php';
if(!isset($_SESSION['client_id'])) { header("Location: login.php"); exit; }
$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM materiel WHERE id=$id")->fetch_assoc();
if(!$product) die("Parfum introuvable");
?>
<div class="container" style="display:flex; gap:40px; align-items:center; flex-wrap:wrap; margin:40px auto;">
    <div style="flex:1; text-align:center;"><img src="images/<?= $product['image'] ?>" style="max-width:300px; border-radius:30px;"></div>
    <div style="flex:1;">
        <h2><?= htmlspecialchars($product['nom']) ?></h2>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p class="price">Prix : <?= $product['prix'] ?> €</p>
        <p>Stock disponible : <?= $product['stock'] ?></p>
        <a href="cart.php?add=<?= $product['id'] ?>" class="btn">Ajouter au panier 🛒</a>
        <a href="shop.php" class="btn btn-secondary">Retour au catalogue</a>
    </div>
</div>
<?php require_once 'footer.php'; ?>