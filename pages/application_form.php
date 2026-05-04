<?php
require_once '../config.php';

$offerId = null;
$offerTitle = 'Postuler à une offre';
$offerCompany = '';
$offerTitleParam = null;
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offerId = isset($_POST['offer_id']) ? (int) $_POST['offer_id'] : null;
    $offerTitleParam = isset($_POST['offer_title']) ? trim($_POST['offer_title']) : null;
} else {
    $offerId = isset($_GET['offer_id']) ? (int) $_GET['offer_id'] : null;
    $offerTitleParam = isset($_GET['offer_title']) ? trim($_GET['offer_title']) : null;
}

if ($offerId) {
    $stmt = $pdo->prepare(
        'SELECT o.titre, e.nom AS entreprise_nom
         FROM offres o
         LEFT JOIN entreprises e ON o.entreprise_id = e.id
         WHERE o.id = ?'
    );
    $stmt->execute([$offerId]);
    $offer = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($offer) {
        $offerTitle = 'Postuler : ' . htmlspecialchars($offer['titre']);
        $offerCompany = $offer['entreprise_nom'] ? htmlspecialchars($offer['entreprise_nom']) : '';
        $offerTitleParam = $offer['titre'];
    }
}

if (!empty($offerTitleParam)) {
    $offerTitle = 'Postuler : ' . htmlspecialchars($offerTitleParam);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    if (empty($nom)) {
        $errors[] = "Nom requis.";
    }

    $prenom = trim($_POST['prenom'] ?? '');
    if (empty($prenom)) {
        $errors[] = "Prénom requis.";
    }

    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email valide requis.";
    }

    $telephone = trim($_POST['telephone'] ?? '');
    if (empty($telephone)) {
        $errors[] = "Téléphone requis.";
    }

    $message = trim($_POST['message'] ?? '');

    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "CV requis.";
    } else {
        $cv = $_FILES['cv'];
        if ($cv['size'] > $maxSize) {
            $errors[] = "CV dépasse la taille maximale de 2MB.";
        }
        $fileType = mime_content_type($cv['tmp_name']);
        if ($fileType !== 'application/pdf') {
            $errors[] = "Le CV n'est pas au format valide (PDF uniquement).";
        }
    }

    $lettreName = null;
    if (isset($_FILES['lettre']) && $_FILES['lettre']['error'] === UPLOAD_ERR_OK) {
        $lettre = $_FILES['lettre'];
        if ($lettre['size'] > $maxSize) {
            $errors[] = "Lettre dépasse la taille maximale de 2MB.";
        }
        $fileType = mime_content_type($lettre['tmp_name']);
        if ($fileType !== 'application/pdf') {
            $errors[] = "La lettre n'est pas au format valide (PDF uniquement).";
        } else {
            $lettreName = uniqid('lettre_', true) . '.pdf';
            $lettrePath = $uploadDir . $lettreName;
            if (!move_uploaded_file($lettre['tmp_name'], $lettrePath)) {
                $errors[] = "Erreur lors de l'upload de la lettre.";
            }
        }
    }

    if (empty($errors)) {
        $cvName = uniqid('cv_', true) . '.pdf';
        $cvPath = $uploadDir . $cvName;
        if (move_uploaded_file($cv['tmp_name'], $cvPath)) {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO candidatures (offre_id, nom, prenom, email, telephone, message, cv_nom, lettre_nom)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $offerId,
                    $nom,
                    $prenom,
                    $email,
                    $telephone,
                    $message,
                    $cvName,
                    $lettreName
                ]);
                $successMessage = "Candidature envoyée avec succès.";
            } catch (PDOException $e) {
                $errors[] = 'Erreur base de données : ' . htmlspecialchars($e->getMessage());
            }
        } else {
            $errors[] = "Erreur lors de l'upload du CV.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler - Stagiel</title>
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
        <h1 id="offer-title"><?= htmlspecialchars($offerTitle) ?></h1>
        <?php if (!empty($offerCompany)): ?>
            <p id="offer-company" class="company-name">Entreprise : <?= $offerCompany ?></p>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <p style="color:#0b6623; text-align:center; margin-bottom:1.5rem;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p style="color:#d32f2f; text-align:center; margin-bottom:0.75rem;"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" id="formCandidature" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="offer_id" value="<?= htmlspecialchars($offerId) ?>">
            <input type="hidden" name="offer_title" value="<?= htmlspecialchars($offerTitleParam ?? $offerTitle) ?>">

            <div class="form-group">
                <label for="nom">Nom <span class="required">*</span></label>
                <input type="text" id="nom" name="nom" required>
                <span class="error-msg" id="error-nom"></span>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom <span class="required">*</span></label>
                <input type="text" id="prenom" name="prenom" required>
                <span class="error-msg" id="error-prenom"></span>
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required>
                <span class="error-msg" id="error-email"></span>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone <span class="required">*</span></label>
                <input type="tel" id="telephone" name="telephone" required>
                <span class="error-msg" id="error-telephone"></span>
            </div>
            
            <div class="form-group">
                <label for="cv">CV <span class="required">*</span></label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx,.odt,.rtf,.jpg,.png" required>
                <span class="error-msg" id="error-cv"></span>
            </div>

            <div class="form-group">
                <label for="lettre">Lettre de motivation <span class="optional">(fichier - optionnel)</span></label>
                <input type="file" id="lettre" name="lettre" accept=".pdf,.doc,.docx">
                <span class="error-msg" id="error-lettre"></span>
            </div>      
            
            <div class="form-group">
                <label for="message">Message au recruteur <span class="optional">(optionnel)</span></label>
                <textarea id="message" name="message" rows="5"></textarea>
                <span class="error-msg" id="error-message"></span>
            </div>

            <button type="submit" class="btn" name="apply">Envoyer ma candidature</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2026 Stagiel</p>
        <div class="footer-links">
            <a href="legal_mentions.php">Mentions légales</a>
        </div>
    </footer>

    <button id="scrollToTop" class="scroll-top">↑</button>

    <script src="../js/script.js"></script>
</body>
</html>
