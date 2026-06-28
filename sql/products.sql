CREATE DATABASE IF NOT EXISTS DOANCUOIKY
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE DOANCUOIKY;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    old_price DECIMAL(12,2) DEFAULT 0,
    image VARCHAR(255),
    tag VARCHAR(50),
    category VARCHAR(50),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, old_price, image, tag, category, stock) VALUES 
('Embroidered Signature Hooded Blouson', 1450000, 1850000, 'images/ao1.jpg', 'LOUIS VUITTON', 'outerwear', 5),
('Reversible Nylon Hybrid Track Top', 1250000, 1500000, 'images/ao2.jpg', 'LOUIS VUITTON', 'outerwear', 8),
('Jacket', 890000, 0, 'images/ao8.jpg', 'DIOR', 'outerwear', 10),
('Off-The-Shoulder Jacket', 450000, 600000, 'images/ao4.jpg', 'DIOR', 'outerwear', 2),
('Cotton t-shirt', 2100000, 2500000, 'images/ao3.jpg', 'DOLCE&GABBANA', 'T-shirts and polos', 3),
('Short-Sleeved Shirt', 1800000, 2200000, 'images/aosm1.jpg', 'DIOR', 'T-shirts and polos', 6),
('Dioriviera Tied Cropped Blouse', 1100000, 0, 'images/aosm2.jpg', 'DIOR', 'T-shirts and polos', 12),
('Quần Chinos Dài Lacoste', 950000, 1200000, 'images/quan4.png', 'LACOSTE', 'trousers', 15),
('Linen pants', 850000, 0, 'images/quan5.png', 'PRADA', 'trousers', 18),
('Black grain de poudre wool trousers', 550000, 750000, 'images/quan5.png', 'FENDI', 'trousers', 19),
('Cotton-Linen Western Pant', 390000, 0, 'images/quan7.png', 'RALPH LAUREN', 'trousers', 9),
('Fit T-Shirt in white dry jersey', 390000, 0, 'images/ao9.png', 'BALENCIAGA', 'T-shirts and polos', 19);