<?php
require_once '../config.php';

$candidatures = [];
$errors = [];

try {
    $stmt = $pdo->query(
        'SELECT c.id, c.nom, c.prenom, c.email, c.telephone, c.message, c.statut, c.date_candidature,
                o.titre AS offre_titre, e.nom AS entreprise_nom
         FROM candidatures c
         LEFT JOIN offres o ON c.offre_id = o.id
         LEFT JOIN entreprises e ON o.entreprise_id = e.id
         ORDER BY c.date_candidature DESC'
    );
    $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Impossible de charger les candidatures : ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatures - Stagiel</title>
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
    <h1>Candidatures reçues</h1>
    <p class="subtitle">Consultez toutes les candidatures enregistrées dans votre base.</p>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p style="color: #d32f2f; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (empty($candidatures)): ?>
        <p class="subtitle">Aucune candidature n'a encore été reçue.</p>
    <?php else: ?>
        <div class="offers-grid">
            <?php foreach ($candidatures as $candidature): ?>
                <div class="offer-card">
                    <h3><?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></h3>
                    <p class="company">Offre : <?= htmlspecialchars($candidature['offre_titre'] ?: 'Non renseignée') ?></p>
                    <p class="details">Entreprise : <?= htmlspecialchars($candidature['entreprise_nom'] ?: 'Non renseignée') ?></p>
                    <p>Email : <?= htmlspecialchars($candidature['email']) ?></p>
                    <p>Téléphone : <?= htmlspecialchars($candidature['telephone'] ?: 'Non renseigné') ?></p>
                    <p>Statut : <strong><?= htmlspecialchars($candidature['statut']) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($candidature['message'] ?: 'Aucun message.')) ?></p>
                    <p style="font-size:0.9rem; color:#666;">Reçue le <?= htmlspecialchars($candidature['date_candidature']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
