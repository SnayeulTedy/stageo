<?php
require_once '../config.php';

$errors = [];
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $secteur = trim($_POST['secteur'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');

    if (empty($nom)) {
        $errors[] = 'Le nom de l\'entreprise est requis.';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'email de l\'entreprise est invalide.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO entreprises (nom, secteur, ville, email, telephone)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$nom, $secteur, $ville, $email, $telephone]);
            $successMessage = 'Entreprise ajoutée avec succès.';
        } catch (PDOException $e) {
            $errors[] = 'Erreur base de données : ' . htmlspecialchars($e->getMessage());
        }
    }
}

$entreprises = [];
try {
    $stmt = $pdo->query('SELECT * FROM entreprises ORDER BY date_creation DESC');
    $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Impossible de charger les entreprises : ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprises - Stagiel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
   <div class="logo"><a href="../index.php">Stagiel</a></div>
    <nav class="menu-desktop">
        <a href="../index.php">Accueil</a>
        <a href="offres.php">Offres</a>
        <a href="application_form.php">Postuler</a>
        <a href="entreprise.php">Entreprises</a>
        <a href="candidature.php">Candidatures</a>
    </nav>
    <button class="burger" id="burger" aria-label="Menu">☰</button>
    <nav class="menu-mobile" id="menuMobile">
        <a href="../index.php">Accueil</a>
        <a href="offres.php">Offres</a>
        <a href="application_form.php">Postuler</a>
        <a href="entreprise.php">Entreprises</a>
        <a href="candidature.php">Candidatures</a>
    </nav>
</header>

<main>
    <h1>Entreprises partenaires</h1>
    <p class="subtitle">Liste des entreprises enregistrées dans votre base de données.</p>

    <?php if (!empty($successMessage)): ?>
        <p style="color: #0b6623; text-align: center; margin-bottom: 1.5rem;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p style="color: #d32f2f; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <section class="offers-grid" style="margin-bottom: 3rem;">
        <?php if (empty($entreprises)): ?>
            <p class="subtitle">Aucune entreprise enregistrée pour le moment.</p>
        <?php else: ?>
            <?php foreach ($entreprises as $entreprise): ?>
                <div class="offer-card">
                    <h3><?= htmlspecialchars($entreprise['nom']) ?></h3>
                    <p class="company"><?= htmlspecialchars($entreprise['secteur'] ?: 'Secteur non renseigné') ?></p>
                    <p class="details"><?= htmlspecialchars($entreprise['ville'] ?: 'Ville non renseignée') ?></p>
                    <p>Email : <?= htmlspecialchars($entreprise['email'] ?: 'Non renseigné') ?></p>
                    <p>Téléphone : <?= htmlspecialchars($entreprise['telephone'] ?: 'Non renseigné') ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section>
        <h2>Ajouter une entreprise</h2>
        <form method="post" action="" novalidate>
            <div class="form-group">
                <label for="nom">Nom de l'entreprise <span class="required">*</span></label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="secteur">Secteur</label>
                <input type="text" id="secteur" name="secteur" value="<?= htmlspecialchars($_POST['secteur'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
            </div>
            <button type="submit" class="btn">Ajouter l'entreprise</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2026 Stagiel</p>
    <div class="footer-links">
        <a href="legal_mentions.php">Mentions légales</a>
    </div>
</footer>

<button id="scrollToTop" class="scroll-top">↑</button>

<script src="../js/script.js" defer></script>
</body>
</html>
