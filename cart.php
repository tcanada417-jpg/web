<?php
require_once 'config.php';
require_once 'header.php';
if(!isset($_SESSION['client_id'])) header("Location: login.php");

if(isset($_GET['add'])) {
    $id = intval($_GET['add']);
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}
if(isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
}
if(isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
}
?>
<div class="container">
    <h2>Mon panier - Simulation d'achat</h2>
    <?php if(empty($_SESSION['cart'])): ?>
        <p>Votre panier est vide.</p>
        <a href="shop.php" class="btn">Continuer les achats</a>
    <?php else:
        $total = 0;
        $items = [];
        foreach($_SESSION['cart'] as $id => $qty) {
            $prod = $conn->query("SELECT * FROM materiel WHERE id=$id")->fetch_assoc();
            if($prod) {
                $items[] = ['prod'=>$prod, 'qty'=>$qty];
                $total += $prod['prix'] * $qty;
            }
        }
    ?>
    <table class="cart-table">
        <tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Total</th><th></th></tr>
        <?php foreach($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['prod']['nom']) ?></td>
            <td><?= $item['prod']['prix'] ?> €</td>
            <td><?= $item['qty'] ?></td>
            <td><?= $item['prod']['prix'] * $item['qty'] ?> €</td>
            <td><a href="?remove=<?= $item['prod']['id'] ?>" class="btn-danger btn">Retirer</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="simulation-total">Montant total de vos achats : <?= number_format($total,2) ?> €</div>
    <div style="display:flex; gap:15px; margin-top:30px;">
        <a href="checkout.php" class="btn">Valider la commande</a>
        <a href="?clear=1" class="btn btn-danger">Vider le panier</a>
        <a href="shop.php" class="btn btn-secondary">Ajouter d'autres produits</a>
    </div>
    <?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>