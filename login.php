<?php
require_once 'config.php';
if(isset($_SESSION['client_id'])) header("Location: shop.php");
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, nom, prenom, password FROM client WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute(); $res = $stmt->get_result();
    if($res->num_rows == 1) { $user = $res->fetch_assoc(); if(password_verify($pass, $user['password'])) { $_SESSION['client_id'] = $user['id']; $_SESSION['client_nom'] = $user['prenom']." ".$user['nom']; header("Location: shop.php"); exit; } }
    $error = "Email ou mot de passe incorrect";
}
require_once 'header.php';
?>
<div class="container" style="max-width:500px; margin:80px auto;"><div class="card" style="padding:40px;"><h2>Connexion client</h2><?php if($error):?><p style="color:red;"><?= $error ?></p><?php endif;?><form method="post"><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Mot de passe" required><button class="btn">Se connecter</button></form><p>Pas encore inscrit ? <a href="register.php">Créez un compte</a></p></div></div>
<?php require_once 'footer.php'; ?>