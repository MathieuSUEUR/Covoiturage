document.addEventListener('DOMContentLoaded', function() {

    let trajetEnCours = true; 

    const conteneurCarte = document.querySelector('.carte-trajet-reserve');
    const boutonAnnuler = document.querySelector('.bouton-annuler');

   
    function afficherAucunTrajet() {
        if (conteneurCarte) {
            
            conteneurCarte.innerHTML = '<p class="message-vide">Aucun trajet en cours</p>';
        }
    }

    function initialiserCarte() {
        if (!trajetEnCours) {
            afficherAucunTrajet();
        }
    }



    if (boutonAnnuler) {
        boutonAnnuler.addEventListener('click', function() {
            const confirmation = confirm("Êtes-vous sûr de vouloir annuler ce trajet ?");
            if (confirmation) {
                alert("Votre trajet a bien été annulé.");

                trajetEnCours = false;
                afficherAucunTrajet();
            }
            
        });
    }

    initialiserCarte();
});