document.addEventListener('DOMContentLoaded', function() {
    var mapElement = document.getElementById('map');

    if (mapElement) {
        
        // --- 1. INITIALISATION DE LA CARTE ---
        var amiens = [49.894067, 2.295753];
        var map = L.map('map').setView(amiens, 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var controlTrajet = null;

        // --- 2. FONCTION GÉOCODAGE ---
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

        // --- 3. ÉCOUTE DU BOUTON ET DES CHAMPS ---
        const btnValider = document.querySelector('.bouton-valider');
        const inputDepart = document.getElementById('input-depart');
        const inputArrivee = document.getElementById('input-arrivee');
        
        // Fonction pour réactiver le bouton si on change le texte
        function reactiverBouton() {
            if (btnValider.disabled) {
                btnValider.disabled = false;
                btnValider.textContent = "Valider";
                btnValider.style.backgroundColor = ""; // Revient à la couleur orange (CSS)
                btnValider.style.cursor = "pointer";
            }
        }

        // On écoute les changements dans les champs texte
        if (inputDepart && inputArrivee) {
            inputDepart.addEventListener('input', reactiverBouton);
            inputArrivee.addEventListener('input', reactiverBouton);
        }

        if (btnValider) {
            btnValider.addEventListener('click', async function() {
                
                const departTxt = inputDepart.value;
                const arriveeTxt = inputArrivee.value;

                if (departTxt === "" || arriveeTxt === "") {
                    alert("Merci de remplir les champs Départ et Arrivée.");
                    return;
                }

                // A. ON DÉSACTIVE LE BOUTON PENDANT LA RECHERCHE
                btnValider.disabled = true; // Empêche de recliquer
                btnValider.textContent = "Recherche en cours...";
                btnValider.style.backgroundColor = "#ccc"; // Gris
                btnValider.style.cursor = "wait"; // Sablier

                // Recherche des coordonnées
                const coordDepart = await trouverCoordonnees(departTxt);
                const coordArrivee = await trouverCoordonnees(arriveeTxt);

                if (coordDepart && coordArrivee) {
                    
                    // Suppression de l'ancien trajet
                    if (controlTrajet) {
                        map.removeControl(controlTrajet);
                    }

                    // Création du nouveau trajet
                    controlTrajet = L.Routing.control({
                        waypoints: [coordDepart, coordArrivee],
                        routeWhileDragging: false,
                        language: 'fr',
                        show: false,
                        createMarker: function(i, wp) {
                            return L.marker(wp.latLng).bindPopup(i === 0 ? "Départ" : "Arrivée");
                        }
                    }).addTo(map);

                    // B. SUCCÈS : ON LAISSE DÉSACTIVÉ MAIS ON CHANGE LE MESSAGE
                    btnValider.textContent = "Trajet affiché ✓";
                    btnValider.style.backgroundColor = "#28a745"; // Vert
                    btnValider.style.cursor = "default";
                    // Note : On laisse btnValider.disabled = true;

                } else {
                    // C. ERREUR : ON RÉACTIVE POUR QU'IL PUISSE CORRIGER
                    reactiverBouton();
                    alert("Impossible de calculer le trajet. Vérifiez les adresses.");
                }
            });
        }
    }
});