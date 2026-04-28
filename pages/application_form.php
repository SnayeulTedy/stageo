<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $nom = trim($_POST['nom'] ?? '');
    if (empty($nom)) $errors[] = "Nom requis.";
    $prenom = trim($_POST['prenom'] ?? '');
    if (empty($prenom)) $errors[] = "Prénom requis.";
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email valide requis.";
    $telephone = trim($_POST['telephone'] ?? '');
    if (empty($telephone)) $errors[] = "Téléphone requis.";
    $message = trim($_POST['message'] ?? '');

    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $maxSize = 2 * 1024 * 1024; // 2MB

    // CV required
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] != UPLOAD_ERR_OK) {
        $errors[] = "CV requis.";
    } else {
        $cv = $_FILES['cv'];
        if ($cv['size'] > $maxSize) {
            $errors[] = "CV dépasse la taille maximale de 2MB.";
        }
        $fileType = mime_content_type($cv['tmp_name']);
        if ($fileType != 'application/pdf') {
            $errors[] = "Le CV n'est pas au format valide (PDF uniquement).";
        }
    }

    // Lettre optional
    $lettrePath = null;
    if (isset($_FILES['lettre']) && $_FILES['lettre']['error'] == UPLOAD_ERR_OK) {
        $lettre = $_FILES['lettre'];
        if ($lettre['size'] > $maxSize) {
            $errors[] = "Lettre dépasse la taille maximale de 2MB.";
        }
        $fileType = mime_content_type($lettre['tmp_name']);
        if ($fileType != 'application/pdf') {
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
            echo "<p>Candidature envoyée avec succès.</p>";
            echo "<a href='$cvPath' target='_blank'>Voir CV</a><br>";
            if ($lettrePath) echo "<a href='$lettrePath' target='_blank'>Voir Lettre</a>";
        } else {
            echo "<p>Erreur lors de l'upload du CV.</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler - Stageo</title>
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
        <h1 id="offer-title">Postuler à une offre</h1>
        <p id="offer-company" class="company-name"></p>

        <form method="post" action="" id="formCandidature" enctype="multipart/form-data" novalidate>
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
                <textarea id="message" name="message" rows="5" ></textarea>
                <span class="error-msg" id="error-message"></span>
            </div>

            <button type="submit" class="btn" name="apply">Envoyer ma candidature</button>
        </form>
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

