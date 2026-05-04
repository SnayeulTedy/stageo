<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stagiel - Stages & Alternances</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <div class="logo"><a href="index.php">Stagiel</a></div>
        <nav class="menu-desktop">
            <a href="index.php">Accueil</a>
            <a href="pages/offres.php">Offres</a>
            <a href="pages/application_form.php">Postuler</a>
            <a href="pages/entreprise.php">Entreprises</a>
            <a href="pages/candidature.php">Candidatures</a>
        </nav>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
        <nav class="menu-mobile" id="menuMobile">
            <a href="index.php">Accueil</a>
            <a href="pages/offres.php">Offres</a>
            <a href="pages/application_form.php">Postuler</a>
            <a href="pages/entreprise.php">Entreprises</a>
            <a href="pages/candidature.php">Candidatures</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Donnez un coup d'accélérateur à votre avenir</h1>
            <p>Stagiel réunit des offres de stage et d'alternance sélectionnées pour vous permettre de trouver une mission motivante, près de chez vous ou à distance.</p>
            <a href="pages/offres.php" class="btn">Voir les offres disponibles</a>
        </section>

        <section>
            <h2 style="text-align:center; margin: 3rem 0 2rem;">Pourquoi choisir Stagiel ?</h2>
            <div class="features-grid">
                <div class="features">
                    <h3>Des offres pertinentes</h3>
                    <p>Des annonces claires et mises à jour, adaptées à votre niveau et à vos objectifs.</p>
                </div>
                <div class="features">
                    <h3>Un accompagnement concret</h3>
                    <p>Conseils de candidature, bonnes pratiques de CV et suivi personnalisé pour chaque étudiant.</p>
                </div>
                <div class="features">
                    <h3>Des entreprises engagées</h3>
                    <p>Des recruteurs sérieux, des centres de formation et des PME qui veulent vous faire grandir.</p>
                </div>
            </div>
        </section>

        <section>
            <h2 style="text-align:center; margin: 3rem 0 2rem;">Comment ça marche ?</h2>
            <div class="features-grid">
                <div class="features">
                    <h3>1. Explorez les offres</h3>
                    <p>Filtrez par secteur, localisation et type de mission pour trouver ce qui vous correspond.</p>
                </div>
                <div class="features">
                    <h3>2. Préparez votre candidature</h3>
                    <p>Rédigez un CV efficace et une lettre de motivation convaincante pour capter l'attention des recruteurs.</p>
                </div>
                <div class="features">
                    <h3>3. Postulez rapidement</h3>
                    <p>Envoyez votre dossier en quelques clics et suivez l'avancement de vos candidatures.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Stagiel</p>
        <div class="footer-links">
            <a href="pages/legal_mentions.php">Mentions légales</a>
        </div>
    </footer>

    <button id="scrollToTop" class="scroll-top">↑</button>

    <script src="js/script.js" defer></script>
</body>
</html>