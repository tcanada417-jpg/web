<?php
require_once 'config.php';
if(isset($_SESSION['admin_logged_in'])) header("Location: admin.php");

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM administrateur WHERE nom = ?");
    $stmt->bind_param("s", $nom);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows == 1) {
        $admin = $res->fetch_assoc();
        if(password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_nom'] = $admin['nom'];
            header("Location: admin.php");
            exit;
        }
    }
    $error = "ACCES NON AUTORISE";
}
require_once 'header.php';
?>
<div class="container" style="max-width:500px; margin:80px auto;">
    <div class="card" style="padding:40px;">
        <h2>Espace Administrateur</h2>
        <?php if($error):?><p style="color:red;"><?= $error ?></p><?php endif;?>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="btn">Se connecter</button>
        </form>
    </div>
</div>
<?php require_once 'footer.php'; ?>