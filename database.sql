CREATE DATABASE IF NOT EXISTS faces_beauty;
USE faces_beauty;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    is_new BOOLEAN DEFAULT FALSE,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(500) DEFAULT 'assets/images/default.jpg',
    rating DECIMAL(2,1) DEFAULT 4.0,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Panier (lié à l'utilisateur, session ou user_id)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Wishlist
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wish (user_id, product_id)
);

-- Commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','paid','shipped','delivered') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Détails de commande
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL, -- prix au moment de l'achat
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Newsletter avancée
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    preferences VARCHAR(100) DEFAULT 'femme,homme,contenu',
    subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insertion de produits exemples (12 parfums)
INSERT INTO products (name, brand, price, is_new, stock, image, rating, description) VALUES
('Power of You', 'EMPORIO ARMANI', 890, 1, 5, 'assets/images/1.jpg', 4.5, 'Un parfum puissant...'),
('YSL', 'YVES SAINT LAURENT', 920, 0, 3, 'assets/images/2.jpg', 4.2, 'Élégance absolue...'),
('Coco', 'CHANEL', 950, 1, 2, 'assets/images/3.jpg', 4.8, 'Féminin, intense...'),
('Sauvage', 'DIOR', 720, 1, 4, 'assets/images/4.jpg', 4.7, 'Sauvage, une composition...'),
('La Nuit de L\'Homme', 'YSL', 880, 0, 1, 'assets/images/5.jpg', 4.4, 'Mystérieux et envoûtant...'),
('Acqua di Giò', 'GIORGIO ARMANI', 750, 0, 0, 'assets/images/6.jpg', 4.3, 'Fraîcheur marine...'),
('Black Opium', 'YSL', 880, 1, 4, 'assets/images/7.jpg', 4.6, 'Café, vanille...'),
('Coco Mademoiselle', 'CHANEL', 950, 0, 2, 'assets/images/8.jpg', 4.9, 'Orange, patchouli...'),
('Le Male', 'JEAN PAUL GAULTIER', 640, 0, 0, 'assets/images/9.jpg', 4.1, 'Iconique, vanille...'),
('Libre', 'YSL', 820, 1, 3, 'assets/images/10.jpg', 4.5, 'Lavande, orange...'),
('Baccarat Rouge 540', 'MFK', 1350, 0, 1, 'assets/images/11.jpg', 4.9, 'Safran, ambre...'),
('Aventus', 'CREED', 1250, 1, 1, 'assets/images/12.jpg', 4.8, 'Ananas, cassis...');