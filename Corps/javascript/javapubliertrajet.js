document.addEventListener('DOMContentLoaded', function() {
    
    // Vérif map
    var mapElement = document.getElementById('map');
    if (!mapElement) return;

    // 1. Carte
    var amiens = [49.894067, 2.295753];
    var map = L.map('map').setView(amiens, 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var controlTrajet = null;

    // 2. Géocodage
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

    // 3. Ciblage (IDs de la page Recherche)
    const btnValider = document.querySelector('.bouton-valider');
    const inputDepart = document.getElementById('input-depart');
    const inputArrivee = document.getElementById('input-arrivee');

    if (btnValider) {
        btnValider.addEventListener('click', async function(e) {
            
            const departTxt = inputDepart.value;
            const arriveeTxt = inputArrivee.value;

            if (departTxt === "" || arriveeTxt === "") {
                alert("Veuillez remplir les champs Départ et Arrivée.");
                return;
            }

            btnValider.textContent = "Calcul en cours...";
            btnValider.disabled = true;

            const coordDepart = await trouverCoordonnees(departTxt);
            const coordArrivee = await trouverCoordonnees(arriveeTxt);

            if (coordDepart && coordArrivee) {
                if (controlTrajet) map.removeControl(controlTrajet);

                controlTrajet = L.Routing.control({
                    waypoints: [coordDepart, coordArrivee],
                    routeWhileDragging: false,
                    language: 'fr',
                    show: false,
                    createMarker: function(i, wp) {
                        return L.marker(wp.latLng).bindPopup(i === 0 ? "Départ" : "Arrivée");
                    }
                }).addTo(map);

                btnValider.textContent = "Trajet validé sur la carte !";
                btnValider.style.background = "#28a745";
                
                setTimeout(() => {
                    btnValider.disabled = false;
                    btnValider.textContent = "Valider le trajet";
                    btnValider.style.background = ""; // Retour couleur origine
                }, 2000);

            } else {
                btnValider.disabled = false;
                btnValider.textContent = "Valider le trajet";
            }
        });
    }
});