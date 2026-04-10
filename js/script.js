document.addEventListener('DOMContentLoaded', () => {

    const burger = document.getElementById('burger');
    const menuMobile = document.getElementById('menuMobile');

    if (burger && menuMobile) {
        burger.addEventListener('click', () => {
            burger.classList.toggle('active');
            menuMobile.classList.toggle('active');
        });

        document.querySelectorAll('.menu-mobile a').forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('active');
                menuMobile.classList.remove('active');
            });
        });
    }

    const scrollBtn = document.getElementById('scrollToTop');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            scrollBtn.classList.toggle('visible', window.scrollY > 400);
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const form = document.getElementById('formCandidature');
    
    if (form) {
        // Récupération des paramètres depuis l'URL de offres.html
        const urlParams = new URLSearchParams(window.location.search);
        const offerTitle = urlParams.get('offer') || "une offre";
        const companyName = urlParams.get('company') || "";

        document.getElementById('offer-title').textContent = `Postuler : ${offerTitle}`;
        
        const companyEl = document.getElementById('offer-company');
        if (companyName) {
            companyEl.textContent = `Entreprise : ${companyName}`;
        }

        // Éléments du formulaire
        const nomInput = document.getElementById('nom');
        const prenomInput = document.getElementById('prenom');
        const emailInput = document.getElementById('email');
        const telInput = document.getElementById('telephone');
        const cvInput = document.getElementById('cv');

        // Nom en majuscules
        if (nomInput) {
            nomInput.addEventListener('input', () => {
                nomInput.value = nomInput.value.toUpperCase();
            });
        }

        // Validation CV
        const allowedExtensions = ['pdf', 'doc', 'docx', 'odt', 'rtf', 'jpg', 'png'];
        const maxSize = 2 * 1024 * 1024; // 2 Mo

        if (cvInput) {
            cvInput.addEventListener('change', () => {
                const errorCv = document.getElementById('error-cv');
                errorCv.textContent = '';

                const file = cvInput.files[0];
                if (!file) return;

                const ext = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(ext)) {
                    errorCv.textContent = "Format non autorisé. Formats acceptés : PDF, DOC, DOCX, ODT, RTF, JPG, PNG";
                    cvInput.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    errorCv.textContent = "Le fichier dépasse la taille maximale de 2 Mo";
                    cvInput.value = '';
                }
            });
        }

        // Soumission du formulaire
        form.addEventListener('submit', (e) => {
            let isValid = true;
            document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');

            if (!nomInput.value.trim()) {
                document.getElementById('error-nom').textContent = "Le nom est obligatoire";
                isValid = false;
            }
            if (!prenomInput.value.trim()) {
                document.getElementById('error-prenom').textContent = "Le prénom est obligatoire";
                isValid = false;
            }
            if (!emailInput.value.trim() || !emailInput.checkValidity()) {
                document.getElementById('error-email').textContent = "Veuillez entrer un email valide";
                isValid = false;
            }
            if (!telInput.value.trim()) {
                document.getElementById('error-telephone').textContent = "Le téléphone est obligatoire";
                isValid = false;
            }
            if (!cvInput.files[0]) {
                document.getElementById('error-cv').textContent = "Le CV est obligatoire";
                isValid = false;
            }
            if (!isValid) {
                e.preventDefault();
                alert("Veuillez remplir tous les champs obligatoires avant d'envoyer votre candidature.");
            } else {
                console.log("Candidature envoyée avec succès !");
                // e.preventDefault(); // Décommente pour tester sans rechargement
            }
        });
    }
});