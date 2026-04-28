<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres - Stageo</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
       <div class="logo"><a href="../index.php">Stageo</a></div>
        <nav class="menu-desktop">
            <a href="../index.php">Accueil</a>
            <a href="offres.php">Offres</a>
            <a href="application_form.php">Postuler</a>
        </nav>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
        <nav class="menu-mobile" id="menuMobile">
            <a href="../index.php">Accueil</a>
            <a href="offres.php">Offres</a>
            <a href="application_form.php">Postuler</a>
        </nav>
    </header>

    <main>
        <h1>Nos Offres de Stages & Alternances</h1>
        <p class="subtitle">Découvrez les opportunités disponibles actuellement</p>

        <?php
        // ====================== TON CODE DE PAGINATION ======================
        function validateInput($input){
            $input = trim($input);
            $input = htmlspecialchars($input, ENT_QUOTES, "UTF-8");
            // REGEX : interdit les caractères spéciaux dangereux
            if(preg_match("/^[a-zA-Z0-9\s\-]+$/u", $input)){
                die("Entrée invalide");
            }
            return $input;
        }

        $entreprises = [
            ['nom' => 'TechCorp', 'secteur' => 'Technologie', 'ville' => 'Paris'],
            ['nom' => 'HealthPlus', 'secteur' => 'Santé', 'ville' => 'Lyon'],
            ['nom' => 'EcoSolutions', 'secteur' => 'Environnement', 'ville' => 'Marseille'],
            ['nom' => 'FinServe', 'secteur' => 'Finance', 'ville' => 'Bordeaux'],
            ['nom' => 'EduWorld', 'secteur' => 'Éducation', 'ville' => 'Toulouse']
        ];

        // On complète à 50 entreprises pour tester la pagination
        $entreprises = array_pad($entreprises, 50, ['nom' => 'Entreprise Générique', 'secteur' => 'Divers', 'ville' => 'Inconnue']);

        $itemParPage = 9;
        $totalItems = count($entreprises);
        $totalPages = ceil($totalItems / $itemParPage);

        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalPages 
                ? (int)$_GET['page'] 
                : 1;

        $startIndex = ($page - 1) * $itemParPage;
        $currentItems = array_slice($entreprises, $startIndex, $itemParPage);
        ?>

        <!-- Affichage des entreprises avec ton code -->
        <div class="offers-grid">
            <?php foreach ($currentItems as $entreprise): ?>
                <div class="offer-card">
                    <h3><?= htmlspecialchars($entreprise['nom']) ?></h3>
                    <p class="company"><?= htmlspecialchars($entreprise['secteur']) ?></p>
                    <p class="details"><?= htmlspecialchars($entreprise['ville']) ?></p>
                    <a href="application_form.php?offer=Stage chez <?= urlencode($entreprise['nom']) ?>&company=<?= urlencode($entreprise['nom']) ?>" 
                       class="btn">Postuler maintenant</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn-pagination">← Précédent</a>
            <?php endif; ?>

            <span class="page-info">Page <?= $page ?> sur <?= $totalPages ?></span>

            <?php if($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn-pagination">Suivant →</a>
            <?php endif; ?>
        </div>

    </main>

    <footer>
        <p>&copy; 2026 Stageo</p>
        <div class="footer-links">
            <a href="legal_mentions.php">Mentions légales</a>
        </div>
    </footer>

    <button id="scrollToTop" class="scroll-top">↑</button>
    <script src="../js/script.js" defer></script>
</body>
</html>