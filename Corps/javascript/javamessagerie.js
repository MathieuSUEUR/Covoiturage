document.getElementById('toggle-search').addEventListener('click', function() {
    const input = document.getElementById('search-input');
    // Ajoute ou enlève la classe 'active'
    input.classList.toggle('active');
        
    // Si le champ apparaît, on met le focus dedans automatiquement
    if (input.classList.contains('active')) {
        input.focus();
    }
});

const donneesConversations = {
        1: {
            nom: "Marie Dupont",
            messages: [
                { type: "recu", texte: "Bonjour ! Est-ce que le covoiturage pour demain matin est toujours d'actualité ?", heure: "10:30" },
                { type: "envoye", texte: "Oui bien sûr ! Départ prévu à 8h00 devant la gare.", heure: "10:35" },
                { type: "recu", texte: "Super, merci ! Je serai là à 7h55 👍", heure: "14:32" },
                { type: "envoye", texte: "Parfait, à demain !", heure: "14:33" }
            ]
        },
        2: {
            nom: "Jean Martin",
            messages: [
                { type: "recu", texte: "Salut, tu penses partir à quelle heure ?", heure: "12:10" },
                { type: "recu", texte: "Ok pour demain matin", heure: "12:15" }
            ]
        },
        3: {
            nom: "Sophie Bernard",
            messages: [
                { type: "envoye", texte: "On se retrouve où ?", heure: "Hier" },
                { type: "recu", texte: "À quelle heure ?", heure: "Hier" }
            ]
        },
        4: {
            nom: "Lucas Petit",
            messages: [
                { type: "recu", texte: "C'est bon pour moi.", heure: "Lun" },
                { type: "envoye", texte: "Parfait, à bientôt", heure: "Lun" }
            ]
        }
    };

    // 2. La fonction qui change tout
    function changerConversation(id) {
        // A. Récupérer les données de la conversation cliquée
        const data = donneesConversations[id];
        if (!data) return; // Sécurité si l'id n'existe pas

        // B. Mettre à jour le nom en haut
        document.getElementById('chat-titre-nom').innerText = data.nom;

        // C. Vider la zone de messages actuelle
        const container = document.getElementById('chat-container-messages');
        container.innerHTML = '<div class="separateur-date"><span>Aujourd\'hui</span></div>';

        // D. Créer les nouveaux messages
        data.messages.forEach(msg => {
            // Création du HTML pour un message
            const divMessage = document.createElement('div');
            divMessage.className = `message ${msg.type}`;
            
            // On ajoute l'avatar seulement si c'est un message reçu
            let avatarHtml = msg.type === 'recu' ? '<div class="avatar-message-placeholder"></div>' : '';

            divMessage.innerHTML = `
                ${avatarHtml}
                <div class="bulle-message">
                    <p>${msg.texte}</p>
                    <span class="heure-msg">${msg.heure}</span>
                </div>
            `;
            
            container.appendChild(divMessage);
        });

        // E. Gérer la classe "actif" (le surlignage orange à gauche)
        // On enlève "actif" de partout
        document.querySelectorAll('.element-conversation').forEach(el => el.classList.remove('actif'));
        
        // On l'ajoute sur l'élément cliqué (astuce : on cherche celui qui a le onclick correspondant)
        // Note : Dans un vrai projet on utiliserait des event listeners plus propres, mais ici ça marche direct
        const elementClique = document.querySelector(`.element-conversation[onclick="changerConversation(${id})"]`);
        if(elementClique) {
            elementClique.classList.add('actif');
            // Optionnel : Si c'était "non-lu", on l'enlève car on vient de lire
            elementClique.classList.remove('non-lu');
            // On cache la pastille rouge si elle existe
            const pastille = elementClique.querySelector('.compteur-non-lu');
            if(pastille) pastille.style.display = 'none';
        }

        // F. Scroll automatique tout en bas pour voir les derniers messages
        container.scrollTop = container.scrollHeight;
    }