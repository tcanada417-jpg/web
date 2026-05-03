<?php
require_once 'config.php';
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom']; $prenom = $_POST['prenom']; $email = $_POST['email']; $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); $adresse = $_POST['adresse']; $tel = $_POST['telephone'];
    $stmt = $conn->prepare("INSERT INTO client (nom, prenom, email, password, adresse, telephone) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $nom, $prenom, $email, $pass, $adresse, $tel);
    if($stmt->execute()) header("Location: login.php?msg=inscrit");
    else $error = "Email déjà utilisé";
}
require_once 'header.php';
?>
<div class="container" style="max-width:600px;"><div class="card" style="padding:30px;"><h2>Inscription client</h2>
<?php if($error):?><p style="color:red;"><?= $error ?></p><?php endif;?>
<form method="post"><input type="text" name="nom" placeholder="Nom" required><input type="text" name="prenom" placeholder="Prénom" required><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Mot de passe" required><input type="text" name="adresse" placeholder="Adresse"><input type="text" name="telephone" placeholder="Téléphone"><button class="btn">S'inscrire</button></form><p>Déjà inscrit ? <a href="login.php">Connectez-vous</a></p></div></div>
<?php require_once 'footer.php'; ?>