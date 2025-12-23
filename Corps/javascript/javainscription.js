//Gestion de la validation du formulaire
document.getElementById('formInscription').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const dateNaissance = document.getElementById('date-naissance').value;

            // Vérification Email
            if (!email.endsWith('@u-picardie.fr')) {
                e.preventDefault(); // Bloque l'envoi vers le PHP
                alert("L'adresse doit finir par @u-picardie.fr");
                return;
            }

            //vérification date
            const formdate = /^\d{2}\/\d{2}\/\d{4}$/;
            if (!formdate.test(dateNaissance)) {
                e.preventDefault();
                alert("Format de date incorrect (JJ/MM/AAAA attendu).");
                return;
            }
        });

//Gestion de l'affichage du mot de passe
document.addEventListener('DOMContentLoaded', function () {
  const imageVue = document.getElementById('imagevue');
  const passwordInput = document.getElementById('mot-de-passe');

  
  imageVue.addEventListener('click', function () {
    const type = passwordInput.type === 'password';
    passwordInput.type = type ? 'text' : 'password';
  });
});