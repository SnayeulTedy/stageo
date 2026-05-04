<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres - Stagiel</title>
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
        <h1>Nos Offres de Stages & Alternances</h1>
        <p class="subtitle">Découvrez les opportunités disponibles actuellement</p>
<br>
        <?php
        require_once '../config.php';

        $currentItems = [];
        $errorMessage = '';
        $itemsPerPage = 9;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $itemsPerPage;
        $totalItems = 0;
        $totalPages = 1;

        try {
            $countStmt = $pdo->query('SELECT COUNT(*) FROM offres');
            $totalItems = (int) $countStmt->fetchColumn();
            $totalPages = max(1, ceil($totalItems / $itemsPerPage));
            if ($page > $totalPages) {
                $page = $totalPages;
                $offset = ($page - 1) * $itemsPerPage;
            }

            $stmt = $pdo->prepare(
                'SELECT o.id, o.titre, o.type, o.duree, o.niveau, o.description,
                        e.nom AS entreprise_nom, e.secteur, e.ville
                 FROM offres o
                 LEFT JOIN entreprises e ON o.entreprise_id = e.id
                 ORDER BY o.date_publication DESC
                 LIMIT :limit OFFSET :offset'
            );
            $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $currentItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errorMessage = 'Erreur de base de données : ' . htmlspecialchars($e->getMessage());
        }
        ?>

        <?php if (!empty($errorMessage)): ?>
            <p style="color:#d32f2f; text-align:center; margin-top:2rem;"><?= $errorMessage ?></p>
        <?php elseif (empty($currentItems)): ?>
            <p class="subtitle">Aucune offre disponible pour le moment. Revenez bientôt !</p>
        <?php endif; ?>

        <div class="offers-grid">
            <?php foreach ($currentItems as $offre): ?>
                <div class="offer-card">
                    <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                    <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?: 'Entreprise non renseignée') ?></p>
                    <p class="details"><?= htmlspecialchars($offre['secteur'] . ' • ' . $offre['ville']) ?></p>
                    <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                    <p><strong><?= htmlspecialchars($offre['type']) ?></strong> - <?= htmlspecialchars($offre['duree']) ?> - <?= htmlspecialchars($offre['niveau']) ?></p><br>
                    <a href="application_form.php?offer_id=<?= (int)$offre['id'] ?>&offer_title=<?= urlencode($offre['titre']) ?>" class="btn">Postuler maintenant</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn-pagination">← Précédent</a>
            <?php endif; ?>

            <span class="page-info">Page <?= $page ?> sur <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn-pagination">Suivant →</a>
            <?php endif; ?>
        </div>

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