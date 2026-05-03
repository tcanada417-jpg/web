<?php
require_once 'config.php';
require_once 'header.php';
if(!isset($_SESSION['client_id'])) header("Location: login.php");
$commande_id = intval($_GET['id']);
$commande = $conn->query("SELECT * FROM commande_client WHERE id=$commande_id AND client_id=".$_SESSION['client_id'])->fetch_assoc();
if(!$commande) die("Commande introuvable");
$client = $conn->query("SELECT * FROM client WHERE id=".$_SESSION['client_id'])->fetch_assoc();
$lignes = $conn->query("SELECT l.*, m.nom, m.image FROM commande_lignes l JOIN materiel m ON l.produit_id=m.id WHERE l.commande_id=$commande_id");
?>
<div class="container">
    <h2>Facture - Merci pour votre achat</h2>
    <div style="background:#f9f1e6; padding:20px; border-radius:30px;">
        <p><strong>Client :</strong> <?= htmlspecialchars($client['prenom']." ".$client['nom']) ?></p>
        <p><strong>Email :</strong> <?= $client['email'] ?></p>
        <p><strong>Adresse :</strong> <?= $client['adresse'] ?: 'Non renseignée' ?></p>
        <p><strong>Date commande :</strong> <?= $commande['date_commande'] ?></p>
        <p><strong>N° commande :</strong> <?= $commande['id'] ?></p>
        <h3>Détail des achats</h3>
        <table>
            <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Total</th></tr>
            <?php $final = 0; while($l = $lignes->fetch_assoc()): $totalLigne = $l['quantite'] * $l['prix_unitaire']; $final += $totalLigne; ?>
            <tr><td><?= htmlspecialchars($l['nom']) ?></td><td><?= $l['quantite'] ?></td><td><?= $l['prix_unitaire'] ?> €</td><td><?= $totalLigne ?> €</td></tr>
            <?php endwhile; ?>
            <tr><td colspan="3"><strong>Montant total</strong></td><td><strong><?= number_format($final,2) ?> €</strong></td></tr>
        </table>
        <p>💰 Paiement à la livraison | Livraison offerte dès 100€ d'achat.</p>
        <a href="shop.php" class="btn">Continuer mes achats</a>
    </div>
</div>
<?php require_once 'footer.php'; ?>