<?php
session_start();
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $books = [];
}

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';
$whatsapp_number = "221770925177"; 

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $domain;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations[$lang]['site_title']; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <i class="fas fa-book-reader"></i>
                <span><?php echo $translations[$lang]['site_name']; ?></span>
            </div>
            <div class="nav-links" id="navLinks">
                <a href="#books" onclick="closeMenu()"><?php echo $translations[$lang]['books']; ?></a>
                <a href="#about" onclick="closeMenu()"><?php echo $translations[$lang]['about']; ?></a>
                <a href="#contact" onclick="closeMenu()"><?php echo $translations[$lang]['contact']; ?></a>
                <div class="language-switcher">
                    <button onclick="changeLanguage('fr')" class="<?php echo $lang === 'fr' ? 'active' : ''; ?>">FR</button>
                    <button onclick="changeLanguage('wo')" class="<?php echo $lang === 'wo' ? 'active' : ''; ?>">WO</button>
                    <button onclick="changeLanguage('en')" class="<?php echo $lang === 'en' ? 'active' : ''; ?>">EN</button>
                </div>
            </div>
            <div class="mobile-menu-btn" id="menuBtn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
</header>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1><?php echo $translations[$lang]['hero_title']; ?></h1>
            <p><?php echo $translations[$lang]['hero_subtitle']; ?></p>
            <a href="#books" class="btn btn-primary">
                <?php echo $translations[$lang]['discover_books']; ?> <i class="fas fa-arrow-down"></i>
            </a>
        </div>
    </div>
</section>

<section id="books" class="books-section">
    <div class="container">
        <h2 class="section-title"><?php echo $translations[$lang]['our_books']; ?></h2>
        <div class="books-grid">
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <div class="book-image">
                        <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Couverture" loading="lazy">
                    </div>
                    <div class="book-info">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-author"><i class="fas fa-user"></i> <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="book-price"><?php echo number_format($book['price'], 0, ',', ' '); ?> FCFA</p>
                        <?php
                        $img_path = $book['cover_image'];
                        $full_img_url = (strpos($img_path, 'http') === 0) ? $img_path : $base_url . '/' . ltrim($img_path, '/');
                        
                        $message_wa = "Bonjour, je souhaite commander ce livre sur AKD Livres :\n\n";
                        $message_wa .= "📖 *Titre :* " . $book['title'] . "\n";
                        $message_wa .= "💰 *Prix :* " . number_format($book['price'], 0, ',', ' ') . " FCFA\n\n";
                        $message_wa .= "🔗 *Lien couverture :* " . $full_img_url;
                        
                        $url_wa = "https://wa.me/" . $whatsapp_number . "?text=" . urlencode($message_wa);
                        ?>
                        <a href="<?php echo $url_wa; ?>" target="_blank" class="btn btn-block whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> Commander sur WhatsApp
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1/-1; color: #7f8c8d;">Aucun livre disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="about" class="about-section">
    <div class="container">
        <h2 class="section-title"><?php echo $translations[$lang]['about_us']; ?></h2>
        <div class="about-content"><div class="about-text"><p><?php echo $translations[$lang]['about_text']; ?></p></div></div>
    </div>
</section>

<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title"><?php echo $translations[$lang]['contact_us']; ?></h2>
        <div class="contact-info">
            <div class="contact-item"><i class="fas fa-envelope"></i><p>karimdione125@gmail.com</p></div>
            <div class="contact-item"><i class="fas fa-phone"></i><p>+221 77 092 51 77</p></div>
            <div class="contact-item"><i class="fab fa-whatsapp"></i><p>+221 77 092 51 77</p></div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo $translations[$lang]['site_name']; ?></h3>
                <p><?php echo $translations[$lang]['footer_description']; ?></p>
            </div>
            <div class="footer-section">
                <h3>Liens Rapides</h3>
                <ul>
                    <li><a href="#books">Livres</a></li>
                    <li><a href="#about">À Propos</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="admin.php" style="color: #e74c3c; font-weight: bold;"><i class="fas fa-user-lock"></i> Admin</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="https://wa.me/221770925177" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 AKD Livres. Tous droits réservés.</p>
            <div class="footer-dev-name">Conçu par Moussa Diouf</div>
            <p class="footer-subtext">Ingénieur en Administration Systèmes Réseau & Développeur Fullstack</p>
            <a href="https://moussadioufportfolio.kesug.com" target="_blank" class="portfolio-link">moussadioufportfolio.kesug.com</a>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>