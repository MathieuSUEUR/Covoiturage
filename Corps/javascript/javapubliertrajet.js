document.addEventListener('DOMContentLoaded', function() {
    
    var mapElement = document.getElementById('map');
    
    // Variables globales pour la carte
    var map = null;
    var controlTrajet = null;
    var amiens = [49.894067, 2.295753]; // Coordonnées par défaut

    if (mapElement) {
        // Initialisation de la carte
        map = L.map('map').setView(amiens, 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
    }

    // --- Fonction de Géocodage ---
    async function trouverCoordonnees(adresse) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresse)}`;
        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data && data.length > 0) {
                return L.latLng(data[0].lat, data[0].lon);
            } else {
                alert("Adresse introuvable : " + adresse);
                return null;
            }
        } catch (error) {
            console.error("Erreur API :", error);
            return null;
        }
    }

    // --- Gestion du bouton "Valider le trajet sur la carte" ---
    const btnValiderCarte = document.querySelector('.bouton-valider');
    const inputDepart = document.getElementById('input-depart');
    const inputArrivee = document.getElementById('input-arrivee');

    // Réactiver le bouton Carte si on change le texte
    function reactiverBoutonCarte() {
        if (btnValiderCarte && btnValiderCarte.disabled) {
            btnValiderCarte.disabled = false;
            btnValiderCarte.textContent = "Valider le trajet sur la carte";
            btnValiderCarte.style.backgroundColor = ""; // Revient à l'orange CSS
            btnValiderCarte.style.cursor = "pointer";
        }
        verifierFormulaireComplet(); // Vérifie aussi le gros bouton publier
    }

    if (inputDepart && inputArrivee) {
        inputDepart.addEventListener('input', reactiverBoutonCarte);
        inputArrivee.addEventListener('input', reactiverBoutonCarte);
    }

    if (btnValiderCarte) {
        btnValiderCarte.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const departTxt = inputDepart.value;
            const arriveeTxt = inputArrivee.value;

            if (departTxt === "" || arriveeTxt === "") {
                alert("Merci de remplir les champs Départ et Arrivée.");
                return;
            }

            // Animation chargement
            btnValiderCarte.disabled = true;
            btnValiderCarte.textContent = "Recherche en cours...";
            btnValiderCarte.style.backgroundColor = "#ccc";
            btnValiderCarte.style.cursor = "wait";

            const coordDepart = await trouverCoordonnees(departTxt);
            const coordArrivee = await trouverCoordonnees(arriveeTxt);

            if (coordDepart && coordArrivee) {
                if (controlTrajet) map.removeControl(controlTrajet);

                controlTrajet = L.Routing.control({
                    waypoints: [coordDepart, coordArrivee],
                    routeWhileDragging: false,
                    language: 'fr',
                    show: false,              // Ne pas ouvrir le panneau d'instructions
                    addWaypoints: false,      // Empêche d'ajouter des points en cliquant sur la ligne
                    draggableWaypoints: false,// Empêche de déplacer les points de départ/arrivée à la souris
                    fitSelectedRoutes: true,
                    createMarker: function(i, wp) {
                        return L.marker(wp.latLng).bindPopup(i === 0 ? "Départ" : "Arrivée");
                    }
                }).addTo(map);

                // Succès carte
                btnValiderCarte.textContent = "Trajet affiché ✓";
                btnValiderCarte.style.backgroundColor = "#4caf50"; // Vert
                btnValiderCarte.style.cursor = "default";
                
                // On revérifie le formulaire global (car les champs sont remplis)
                verifierFormulaireComplet();

            } else {
                // Erreur carte
                reactiverBoutonCarte();
                alert("Impossible de calculer le trajet. Vérifiez les adresses.");
            }
        });
    }



    const btnPublier = document.querySelector('.bouton-publier-final');
    const checkboxCGU = document.getElementById('cgu');
    
    // On sélectionne tous les inputs qui comptent pour la validation
    // (Ceux du formulaire de gauche + ceux de la carte)
    const inputsFormulaire = document.querySelectorAll('.bloc-filtres input, .bloc-filtres select');
    
    // Fonction qui vérifie si TOUT est rempli
    function verifierFormulaireComplet() {
        if (!btnPublier) return;

        // 1. Vérifier si la checkbox CGU est cochée
        let cguOk = checkboxCGU && checkboxCGU.checked;

        // 2. Vérifier si les champs Départ/Arrivée ne sont pas vides
        let trajetOk = inputDepart.value.trim() !== "" && inputArrivee.value.trim() !== "";

        // 3. Vérifier les autres champs (Date, Heure, Prix...)
        let detailsOk = true;
        inputsFormulaire.forEach(input => {
            // Si c'est un input texte, number, date ou time, il ne doit pas être vide
            if ((input.type === 'text' || input.type === 'number' || input.type === 'date' || input.type === 'time') && input.value === "") {
                detailsOk = false;
            }
        });

        // Si le bouton est déjà en état "Publié" (vert), on ne le grise pas, on le laisse actif pour annuler
        if (btnPublier.classList.contains('est-publie')) {
            btnPublier.disabled = false;
            btnPublier.style.cursor = "pointer";
            return; 
        }

        // --- APPLICATION DE L'ÉTAT DU BOUTON ---
        if (cguOk && trajetOk && detailsOk) {
            // Tout est bon : Bouton Orange Actif
            btnPublier.disabled = false;
            btnPublier.style.backgroundColor = ""; // Revient à la couleur CSS (Orange)
            btnPublier.style.cursor = "pointer";
            btnPublier.title = ""; // Enlève l'infobulle
        } else {
            // Manque des infos : Bouton Gris Inactif
            btnPublier.disabled = true;
            btnPublier.style.backgroundColor = "#ccc";
            btnPublier.style.cursor = "not-allowed";
            btnPublier.title = "Veuillez remplir tous les champs et accepter les CGU";
        }
    }

    // --- Écouteurs d'événements pour la vérification en temps réel ---
    
    // 1. Écoute sur la Checkbox
    if (checkboxCGU) {
        checkboxCGU.addEventListener('change', verifierFormulaireComplet);
    }

    // 2. Écoute sur tous les inputs de gauche
    inputsFormulaire.forEach(input => {
        input.addEventListener('input', verifierFormulaireComplet);
        input.addEventListener('change', verifierFormulaireComplet);
    });

    // Lancer la vérification au chargement de la page (pour griser le bouton direct)
    verifierFormulaireComplet();


    // --- CLIC SUR LE BOUTON PUBLIER (Logique de Confirmation / Annulation) ---
    if (btnPublier) {
        btnPublier.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche l'envoi réel du formulaire

            // CAS 1 : Annulation (Le bouton est déjà vert/publié)
            if (btnPublier.classList.contains('est-publie')) {
                
                const confirmationAnnul = confirm("Êtes-vous sûr de vouloir annuler cette publication ?");
                
                if (confirmationAnnul) {
                    // Animation d'annulation
                    btnPublier.textContent = "Annulation en cours...";
                    btnPublier.style.backgroundColor = "#e74c3c"; // Rouge
                    
                    setTimeout(() => {
                        // Retour à l'état initial
                        btnPublier.classList.remove('est-publie');
                        btnPublier.textContent = "Confirmer la publication";
                        
                        // On relance la vérification pour remettre la bonne couleur (Orange ou Gris selon les champs)
                        verifierFormulaireComplet(); 
                    }, 1500);
                }

            } 
            // CAS 2 : Publication (Le bouton est Orange)
            else {
                
                const confirmationPubli = confirm("Êtes-vous sûr de vouloir publier cette annonce ?");

                if (confirmationPubli) {
                    // Animation de succès
                    btnPublier.textContent = "Annonce publiée ! ✓";
                    btnPublier.style.backgroundColor = "#4caf50"; // Vert
                    btnPublier.classList.add('est-publie');
                    
                    // Optionnel : Désactiver les champs pour ne plus les modifier ?
                    // Pour l'instant on laisse modifiable, mais le bouton restera vert.
                }
            }
        });
    }

});