<?php
session_start();
require_once 'config.php';

$action_message = "";
$edit_book = null;

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    if ($stmt->execute([$id_to_delete])) {
        header("Location: admin.php?success=Livre+supprimé");
        exit;
    }
}

// Récupération des données pour modification
if (isset($_GET['edit'])) {
    $id_to_edit = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id_to_edit]);
    $edit_book = $stmt->fetch();
}

// Insertion ou Mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $author = htmlspecialchars($_POST['author']);
    $price = (int)$_POST['price'];
    $cover_image = htmlspecialchars($_POST['cover_image']);
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

    if (!empty($title) && !empty($author) && $price > 0 && !empty($cover_image)) {
        if ($book_id > 0) {
            // Mise à jour
            $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, price = ?, cover_image = ? WHERE id = ?");
            $stmt->execute([$title, $author, $price, $cover_image, $book_id]);
            header("Location: admin.php?success=Livre+mis+à+jour");
            exit;
        } else {
            // Ajout simple
            $stmt = $pdo->prepare("INSERT INTO books (title, author, price, cover_image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $author, $price, $cover_image]);
            header("Location: admin.php?success=Livre+ajouté+avec+succès");
            exit;
        }
    } else {
        $action_message = "<p class='alert error'>Veuillez remplir correctement tous les champs.</p>";
    }
}

// Récupération finale de la liste complète des livres
$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
$all_books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administration - AKD Livres</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { padding: 40px 0; background: #fff; min-height: 80vh; margin-top: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .admin-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 40px; margin-top: 30px; }
        @media (max-width: 768px) { .admin-grid { grid-template-columns: 1fr; } }
        .form-panel, .list-panel { background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; text-align: center; }
        .alert.success { background: #d1fae5; color: #065f46; }
        .alert.error { background: #fee2e2; color: #991b1b; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        .admin-table th, .admin-table td { padding: 12px; border: 1px solid #e2e8f0; text-align: left; font-size: 14px; }
        .admin-table th { background: #2c3e50; color: white; }
        .thumb-img { width: 45px; height: 60px; object-fit: cover; border-radius: 4px; }
        .actions-btn { display: flex; gap: 8px; }
        .btn-edit { background: #3498db; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; }
        .btn-delete { background: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; }
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="container">
            <div class="logo"><i class="fas fa-user-shield"></i> <span>AKD Panel Admin</span></div>
            <div class="nav-links"><a href="index.php"><i class="fas fa-eye"></i> Voir le site public</a></div>
        </div>
    </nav>
</header>

<main class="container admin-container">
    <h2 style="text-align: center; color: #2c3e50;"><i class="fas fa-tasks"></i> Gestion de l'inventaire des Livres</h2>
    
    <?php if (isset($_GET['success'])) echo "<div class='alert success'>".htmlspecialchars($_GET['success'])."</div>"; ?>
    <?php echo $action_message; ?>

    <div class="admin-grid">
        <!-- Formulaire d'Ajout ou de Modification -->
        <div class="form-panel">
            <h3><?php echo $edit_book ? "✏️ Modifier le livre" : "➕ Ajouter un livre"; ?></h3>
            <form action="admin.php" method="POST" style="margin-top: 15px;">
                <?php if ($edit_book): ?>
                    <input type="hidden" name="book_id" value="<?php echo $edit_book['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Titre de l'ouvrage</label>
                    <input type="text" name="title" value="<?php echo $edit_book ? htmlspecialchars($edit_book['title']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Auteur</label>
                    <input type="text" name="author" value="<?php echo $edit_book ? htmlspecialchars($edit_book['author']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <input type="number" name="price" value="<?php echo $edit_book ? $edit_book['price'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Chemin de la couverture (ex: images/livre.jpg)</label>
                    <input type="text" name="cover_image" value="<?php echo $edit_book ? htmlspecialchars($edit_book['cover_image']) : 'images/'; ?>" required>
                </div>
                
                <button type="submit" class="btn whatsapp-btn btn-block" style="border-radius:6px; margin-top:10px;">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                
                <?php if ($edit_book): ?>
                    <a href="admin.php" style="display:block; text-align:center; margin-top:10px; color:#7f8c8d; font-size:13px;">Annuler la modification</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tableau d'affichage de la base de données actuelle -->
        <div class="list-panel">
            <h3>📚 Liste des livres en base (<?php echo count($all_books); ?>)</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Visuel</th>
                        <th>Détails</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($all_books)): foreach($all_books as $b): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($b['cover_image']); ?>" class="thumb-img" alt="Couverture"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($b['title']); ?></strong><br>
                            <span style="color:#7f8c8d; font-size:12px;"><?php echo htmlspecialchars($b['author']); ?></span>
                        </td>
                        <td><strong><?php echo number_format($b['price'], 0, ',', ' '); ?> F</strong></td>
                        <td>
                            <div class="actions-btn">
                                <a href="admin.php?edit=<?php echo $b['id']; ?>" class="btn-edit" title="Modifier"><i class="fas fa-edit"></i></a>
                                <a href="admin.php?delete=<?php echo $b['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ce livre du catalogue ?')" title="Supprimer"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" style="text-align:center; color:#7f8c8d;">Aucun livre trouvé en base de données.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>