<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FACES Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="top-banner">
    <i class="fas fa-truck"></i> LIVRAISON GRATUITE dès 600 DHS <i class="fas fa-gift"></i> | CODE PROMO : FACES10 <i class="fas fa-percent"></i> -10%
</div>
<header>
    <div class="container header-main">
        <div class="logo"><h1>FACES<span>beauty</span></h1><p>PARFUMS & COSMÉTIQUES</p></div>
        <div class="search-wrapper"><div class="search-bar"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Entrez un parfum..."></div></div>
        <div class="header-actions">
            <div class="wishlist-icon" id="wishlistIcon"><i class="fas fa-heart"></i><span class="wishlist-count"><?= isLoggedIn() ? getWishlistCount($pdo, $_SESSION['user_id']) : 0 ?></span></div>
            <div class="cart-icon" id="cartIcon"><i class="fas fa-shopping-bag"></i><span class="cart-count"><?= isLoggedIn() ? getCartCount($pdo, $_SESSION['user_id']) : 0 ?></span></div>
            <button class="account-btn" id="accountBtn"><i class="far fa-user"></i> <?= isLoggedIn() ? htmlspecialchars($_SESSION['username']) : 'Compte' ?></button>
        </div>
    </div>
</header>
<div class="nav-menu"><div class="container"><ul class="nav-links"><li><a href="index.php">NEW PRODUCTS</a></li><li><a href="shop.php?brands=1">BRANDS</a></li><li><a href="shop.php" class="active">PERFUMES</a></li><li><a href="#">MAKEUP</a></li><li><a href="#">FACIAL TREATMENT</a></li><li><a href="#">HAIR & BODY</a></li><li><a href="#">MEN</a></li><li><a href="#">ACCESSORIES</a></li><li><a href="#">GIFT SETS</a></li></ul></div></div>
<main class="container">