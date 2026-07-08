<?php
try {
    $host = '127.0.0.1';
    $db   = 'akd_db';
    $user = 'root';
    $pass = ''; // Mets ton mot de passe si tu en as un sur XAMPP/Laragon
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
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
?>