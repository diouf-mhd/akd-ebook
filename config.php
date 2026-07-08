<?php
declare(strict_types=1);
// Environnement: définir APP_ENV=development pour afficher les erreurs en dev
$appEnv = getenv('APP_ENV') ?: 'production';
if ($appEnv !== 'development') {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
try {
    $host = '127.0.0.1';
    // Sur cette machine, MySQL écoute sur 13306 (XAMPP portable ou conflit de port possible).
    // Vous pouvez définir DB_PORT dans l'environnement si vous préférez un autre port.
    $port = getenv('DB_PORT') ?: '13306';
    $db   = 'akd_db';
    $user = 'root';
    $pass = getenv('DB_PASS') ?: ''; // Mets ton mot de passe si tu en as un sur XAMPP/Laragon (ou définir DB_PASS dans l'environnement)
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT           => 5,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    $hint = 'Vérifiez que le serveur MySQL est démarré (XAMPP Control Panel), que l\'hôte, le port et les identifiants sont corrects.';
    // Log détaillé côté serveur
    error_log('DB connection error: ' . $e->getMessage());

    if (php_sapi_name() === 'cli') {
        // Pour les développeurs en CLI, afficher le détail
        fwrite(STDERR, "Database connection failed: {$e->getMessage()}\n{$hint}\n");
        exit(1);
    }

    // Pour le web en production, ne rien divulguer — afficher un message générique
    if ($appEnv === 'development') {
        echo '<h2>Erreur de connexion à la base de données</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>' . htmlspecialchars($hint) . '</p>';
    } else {
        // Message non détaillé pour les visiteurs
        http_response_code(500);
        echo '<h2>Une erreur est survenue</h2>';
        echo '<p>Veuillez réessayer plus tard.</p>';
    }
    exit;
}

// Tableau global de traductions
$translations = [
    'fr' => [
        'site_title' => 'AKD Livres - Catalogue Officiel',
        'site_name' => 'AKD Livres',
        'books' => 'Livres',
        'about' => 'À Propos',
        'contact' => 'Contact',
        'hero_title' => 'Votre Bibliothèque Digitale',
        'hero_subtitle' => 'Découvrez notre catalogue complet et commandez vos ouvrages préférés.',
        'discover_books' => 'Découvrir les livres',
        'our_books' => 'Nos Ouvrages disponibles',
        'about_us' => 'À propos de nous',
        'about_text' => 'AKD Livres est une plateforme moderne conçue pour simplifier l\'accès au savoir. Parcourez, choisissez et achetez vos livres en un instant.',
        'contact_us' => 'Contactez-nous',
        'footer_description' => 'Le savoir à portée de main.'
    ],
    'en' => [
        'site_title' => 'AKD Livres - Official Catalog',
        'site_name' => 'AKD Livres',
        'books' => 'Books',
        'about' => 'About',
        'contact' => 'Contact',
        'hero_title' => 'Your Digital Library',
        'hero_subtitle' => 'Discover our full catalog and order your favorite books.',
        'discover_books' => 'Discover books',
        'our_books' => 'Our Available Books',
        'about_us' => 'About Us',
        'about_text' => 'AKD Livres is a modern platform designed to simplify access to knowledge. Browse, choose, and buy your books instantly.',
        'contact_us' => 'Contact Us',
        'footer_description' => 'Knowledge at your fingertips.'
    ],
    'wo' => [
        'site_title' => 'AKD Livres - Téere yi',
        'site_name' => 'AKD Livres',
        'books' => 'Téere yi',
        'about' => 'Lu jëm ci nun',
        'contact' => 'Jokkoo',
        'hero_title' => 'Sa Sàkkuwaay Téere',
        'hero_subtitle' => 'Seetall téere yi fi nekk te nga mën koo bajjal ci saa si.',
        'discover_books' => 'Xool téere yi',
        'our_books' => 'Téere yi am',
        'about_us' => 'Lu jëm ci nun',
        'about_text' => 'AKD Livres bërëb la bu yombal jot ci xam-xam. Seetal, tànnal te nga jënd say téere ci saa si.',
        'contact_us' => 'Jokkoo ak nun',
        'footer_description' => 'Xam-xam ci sa loxo.'
    ]
];