// javascript/javamessagerie.js

let conversationActiveId = null;
let dernierJson = ""; // On va stocker ici la dernière version des messages reçus

// 1. CHANGER DE CONVERSATION
function changerConversation(contactId) {
    console.log("Clic sur la conversation ID : " + contactId);
    conversationActiveId = contactId;
    dernierJson = ""; // On reset la mémoire
    
    // 1. Mise à jour visuelle
    document.querySelectorAll('.element-conversation').forEach(el => el.classList.remove('actif'));
    
    // On cible l'élément cliqué
    const elementClique = document.querySelector(`.element-conversation[data-id="${contactId}"]`);
    
    if(elementClique) {
        elementClique.classList.add('actif');
        elementClique.classList.remove('non-lu'); 
        
        // --- NOUVEAU : ON SUPPRIME LA NOTIFICATION ---
        const badge = elementClique.querySelector('.compteur-non-lu');
        if (badge) {
            badge.remove(); // On supprime le point d'exclamation rouge du HTML
        }
        // ---------------------------------------------

        // On met à jour le nom en haut
        const nom = elementClique.getAttribute('data-nom');
        const titre = document.getElementById('chat-titre-nom');
        if(titre) titre.innerText = nom;
    }

    // 2. On charge les messages
    chargerMessages();
}

// 2. CHARGER LES MESSAGES (VERSION INTELLIGENTE)
function chargerMessages() {
    if(!conversationActiveId) return;

    const urlAPI = 'api_messagerie.php'; 

    fetch(`${urlAPI}?action=get_messages&contact_id=${conversationActiveId}`)
        .then(response => response.json())
        .then(messages => {
            // --- C'EST ICI QUE LA MAGIE OPÈRE ---
            
            // 1. On transforme les données reçues en texte simple
            const nouveauJson = JSON.stringify(messages);

            // 2. On compare avec ce qu'on a déjà en mémoire
            // Si c'est strictement identique, ON S'ARRÊTE LÀ.
            // Pas de mise à jour du HTML = Pas de clignotement.
            if (nouveauJson === dernierJson) {
                return; 
            }

            // 3. Si on est ici, c'est qu'il y a du nouveau !
            // On met à jour la mémoire
            dernierJson = nouveauJson;

            // Et on met à jour l'écran
            const container = document.getElementById('chat-container-messages');
            if(!container) return;

            container.innerHTML = '<div class="separateur-date"><span>Discussion</span></div>';

            messages.forEach(msg => {
                const div = document.createElement('div');
                div.className = `message ${msg.type}`;
                div.innerHTML = `
                    <div class="bulle-message">
                        <p>${msg.texte}</p>
                        <span class="heure-msg">${msg.heure}</span>
                    </div>
                `;
                container.appendChild(div);
            });

            // Scroll en bas uniquement s'il y a du changement
            container.scrollTop = container.scrollHeight;
        })
        .catch(error => console.error('Erreur JS :', error));
}

// 3. ENVOYER UN MESSAGE
document.addEventListener('DOMContentLoaded', () => {
    const btnEnvoyer = document.querySelector('.bouton-envoyer');
    const inputSaisie = document.querySelector('.zone-saisie input');

    if(btnEnvoyer) {
        btnEnvoyer.addEventListener('click', envoyerMessage);
    }
    if(inputSaisie) {
        inputSaisie.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') envoyerMessage();
        });
    }
});

function envoyerMessage() {
    const input = document.querySelector('.zone-saisie input');
    const message = input.value.trim();

    if (!message || !conversationActiveId) return;

    fetch('api_messagerie.php?action=send_message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            contact_id: conversationActiveId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            // Astuce : on vide la mémoire pour forcer le rafraîchissement immédiat
            dernierJson = ""; 
            chargerMessages();
        }
    });
}

// Rafraîchissement automatique (continue de tourner en fond, mais ne touche plus au HTML inutilement)
setInterval(() => {
    if(conversationActiveId) chargerMessages();
}, 3000);