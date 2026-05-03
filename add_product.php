<?php
require_once 'config.php';
if(!isset($_SESSION['admin_logged_in'])) header("Location: admin_login.php");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $desc = $_POST['description'];
    $prix = $_POST['prix'];
    $pa = $_POST['prix_achat'];
    $stock = $_POST['stock'];
    $img = $_FILES['image']['name'] ?: 'default.jpg';
    if($_FILES['image']['name']) move_uploaded_file($_FILES['image']['tmp_name'], "images/".$img);
    $stmt = $conn->prepare("INSERT INTO materiel (nom, description, prix, prix_achat, stock, image) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssddds", $nom, $desc, $prix, $pa, $stock, $img);
    $stmt->execute();
    header("Location: admin.php?msg=added");
}
require_once 'header.php';
?>
<div class="container"><div class="card" style="padding:30px;"><h2>Ajouter un parfum</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="nom" placeholder="Nom du parfum" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="number" step="0.01" name="prix" placeholder="Prix public" required>
    <input type="number" step="0.01" name="prix_achat" placeholder="Prix d'achat" required>
    <input type="number" name="stock" placeholder="Quantité" required>
    <input type="file" name="image" accept="image/*">
    <button type="submit" class="btn">Ajouter</button>
</form></div></div>
<?php require_once 'footer.php'; ?>