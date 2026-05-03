<?php require_once 'includes/config.php'; require_once 'includes/header.php'; ?>
<!-- Carrousel fade + zoom (identique au HTML) -->
<div class="hero-carousel">...</div>
<div class="category-bar"><div class="category-list"><a href="#">Coffret</a> ...</div></div>
<div class="services-bar"><div class="services-list">...</div></div>
<div class="collection-header"><h3><i class="fas fa-spray-can-sparkles"></i> NOUVEAUTÉS & ICONIQUES</h3> ...</div>
<div class="filters-panel">...</div>
<div id="productsGrid" class="products-grid">
    <?php
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id LIMIT 12");
    while($p = $stmt->fetch()) {
        echo '<div class="product-card">...'; // génération HTML selon le design
    }
    ?>
</div>
<?php require_once 'includes/footer.php'; ?>
<?php
session_start();
if($_POST['admin_pass'] !== 'admin123') die('Accès refusé');
// Liste produits, formulaire d’ajout, suppression
?>