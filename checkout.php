<?php
require_once 'config.php';
require_once 'header.php';
if(!isset($_SESSION['client_id'])) header("Location: login.php");
if(empty($_SESSION['cart'])) { header("Location: cart.php"); exit; }

$error = null;
$client_id = $_SESSION['client_id'];

// Vérifier stock avant validation
$insuffisant = [];
foreach($_SESSION['cart'] as $id => $qty) {
    $prod = $conn->query("SELECT stock, prix, nom FROM materiel WHERE id=$id")->fetch_assoc();
    if(!$prod || $prod['stock'] < $qty) {
        $insuffisant[] = $prod['nom'] . " (stock: " . ($prod['stock']??0) . ")";
        $error = true;
    }
}
if($error) {
    echo "<div class='container'><p style='color:red;font-size:1.2rem;'>❌ Commande impossible : rupture ou quantité insuffisante pour : " . implode(", ", $insuffisant) . "</p><a href='cart.php' class='btn'>Retour au panier</a></div>";
    require_once 'footer.php';
    exit;
}

// Tout est ok : créer commande
$total = 0;
$lignes = [];
foreach($_SESSION['cart'] as $id => $qty) {
    $prod = $conn->query("SELECT prix, nom FROM materiel WHERE id=$id")->fetch_assoc();
    $total += $prod['prix'] * $qty;
    $lignes[] = ['id'=>$id, 'qty'=>$qty, 'prix'=>$prod['prix']];
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO commande_client (client_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $client_id, $total);
    $stmt->execute();
    $commande_id = $conn->insert_id;
    foreach($lignes as $l) {
        $stmt2 = $conn->prepare("INSERT INTO commande_lignes (commande_id, produit_id, quantite, prix_unitaire) VALUES (?,?,?,?)");
        $stmt2->bind_param("iiid", $commande_id, $l['id'], $l['qty'], $l['prix']);
        $stmt2->execute();
        // Mettre à jour stock
        $conn->query("UPDATE materiel SET stock = stock - {$l['qty']} WHERE id = {$l['id']}");
    }
    $conn->commit();
    $_SESSION['cart'] = [];
    header("Location: facture.php?id=$commande_id");
    exit;
} catch(Exception $e) {
    $conn->rollback();
    die("Erreur lors de la validation : " . $e->getMessage());
}
?>