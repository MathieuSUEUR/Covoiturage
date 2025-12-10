document.addEventListener('DOMContentLoaded', function() {

    
    //GESTION DU MENU DÉROULANT "TRIER"
    
    const blocTrier = document.querySelector('.bloc-filtres');
    
    if (blocTrier) {
        blocTrier.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
                return; 
            }
            this.classList.toggle('actif');
        });
    }


    //GESTION DES PLACES 
    const cartes = document.querySelectorAll('.carte-trajet-resultat');

    cartes.forEach(carte => {
        const infoPlace = carte.querySelector('.info-place');
        const bouton = carte.querySelector('.bouton-reserver');

        if (infoPlace && bouton) {
            const texte = infoPlace.textContent;
            const match = texte.match(/(\d+)\/(\d+)/);

            if (match) {
                const placesPrises = parseInt(match[1]);
                const placesTotal = parseInt(match[2]);

                if (placesPrises >= placesTotal) {
                    bouton.textContent = "Complet";
                    bouton.style.backgroundColor = "#ccc"; 
                    bouton.style.cursor = "not-allowed";   
                    bouton.disabled = true;                
                }
            }
        }
    });

    /*gestion des boutons de reservations*/

    const boutonsReserver = document.querySelectorAll('.bouton-reserver');

    boutonsReserver.forEach(bouton => {
        bouton.addEventListener('click', function(e) {
            
            const monBouton = this;

            if (monBouton.classList.contains('est-reserve')) {
                
                const confirmationAnnulation = confirm("Êtes-vous sûr de vouloir annuler votre réservation ?");

                if (confirmationAnnulation) {
                    monBouton.textContent = "Annulation...";
                    monBouton.style.backgroundColor = "#e74c3c";
                 
                    monBouton.classList.remove('est-reserve');

                    setTimeout(function() {
                        monBouton.textContent = "Réserver";
                        monBouton.style.backgroundColor = ""; 
                    }, 1500);
                } else {
                    
                }

            } 
            
            else {
                
                const confirmationReservation = confirm("Êtes-vous sûr de vouloir réserver ce trajet ?");

                if (confirmationReservation) {

                    alert("Réservation confirmée !");
                    
                    monBouton.textContent = "Réservé ✓";
                    monBouton.style.backgroundColor = "#28a745";
                    
                   
                    monBouton.classList.add('est-reserve'); 

                } else {
                   
                    monBouton.textContent = "Annulation";
                    monBouton.style.backgroundColor = "#e74c3c"; 

                    setTimeout(function() {
                        monBouton.textContent = "Réserver";
                        monBouton.style.backgroundColor = ""; 
                    }, 1500);
                }
            }
        });
    });
});