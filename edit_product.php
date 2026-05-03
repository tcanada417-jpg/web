<?php
require_once 'config.php';
if(!isset($_SESSION['admin_logged_in'])) header("Location: admin_login.php");
$id = $_GET['id'];
$prod = $conn->query("SELECT * FROM materiel WHERE id=$id")->fetch_assoc();
if(!$prod) die("Produit introuvable");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom']; $desc = $_POST['description']; $prix = $_POST['prix']; $pa = $_POST['prix_achat']; $stock = $_POST['stock'];
    $img = $prod['image'];
    if($_FILES['image']['name']) { $img = $_FILES['image']['name']; move_uploaded_file($_FILES['image']['tmp_name'], "images/".$img); }
    $stmt = $conn->prepare("UPDATE materiel SET nom=?, description=?, prix=?, prix_achat=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("ssddssi", $nom, $desc, $prix, $pa, $stock, $img, $id);
    $stmt->execute();
    header("Location: admin.php");
}
require_once 'header.php';
?>
<div class="container"><div class="card" style="padding:30px;"><h2>Modifier parfum</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="nom" value="<?= htmlspecialchars($prod['nom']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($prod['description']) ?></textarea>
    <input type="number" step="0.01" name="prix" value="<?= $prod['prix'] ?>" required>
    <input type="number" step="0.01" name="prix_achat" value="<?= $prod['prix_achat'] ?>" required>
    <input type="number" name="stock" value="<?= $prod['stock'] ?>" required>
    <input type="file" name="image"> <small>Image actuelle: <?= $prod['image'] ?></small>
    <button type="submit" class="btn">Mettre à jour</button>
</form></div></div>
<?php require_once 'footer.php'; ?>