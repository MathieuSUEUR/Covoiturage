<?php
require __DIR__ . '/../../Includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['test_id'])) {
    $_SESSION['id_user'] = (int)$_GET['test_id'];
}

if (!isset($_SESSION['id_user'])) {
    $_SESSION['id_user'] = 1; 
}

$monId = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="../styles/styles_Messageries.css">
</head>
<body>
    
    <header class="barre-navigation">
        <div class="nav-gauche">
            <a href="./Menu.php"><div class="logo-navigation-placeholder"></div></a>
            <span class="site-nom">nom</span>
        </div>
        <div class="nav-centre">
            <a href="./Trajets.php"><button class="bouton-nav">Rechercher</button></a>
            <a href="./PublierTrajet.php"><button class="bouton-nav">Publier</button></a>
        </div>
        <div class="nav-droite">
            <div class="profil-avatar-placeholder">
                <a href="./Profiles.php">
                    <img src="../images/default_avatar.png" alt="Profil" style="width:100%; height:100%; object-fit:cover;">
                </a>
            </div>
        </div>
    </header> 

    <main class="contenu-principal">
        <h1 class="titre-page">Messagerie</h1>
        
        <div class="conteneur-messagerie">
            <section class="panneau-conversations">
                <div class="entete-conversations">
                    <h2 class="titre-conversations">Conversations</h2>
                    </div>
                
                <div class="liste-conversations">
                    <?php
                    // Requête SQL pour la liste de gauche
                    $sql = "
                        SELECT u.id_user, u.nom, u.prenom, u.photo_de_profil, m.message, m.date_envoi, m.est_lu, m.id_expediteur
                        FROM Utilisateurs u
                        JOIN messages m ON (
                            (m.id_expediteur = u.id_user AND m.id_destinataire = :monId) OR
                            (m.id_destinataire = u.id_user AND m.id_expediteur = :monId)
                        )
                        WHERE m.id_message = (
                            SELECT MAX(id_message) FROM messages m2 
                            WHERE (m2.id_expediteur = u.id_user AND m2.id_destinataire = :monId)
                            OR (m2.id_destinataire = u.id_user AND m2.id_expediteur = :monId)
                        )
                        ORDER BY m.date_envoi DESC
                    ";

                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['monId' => $monId]);
                        $conversations = $stmt->fetchAll();
                    } catch (PDOException $e) {
                        $conversations = [];
                        // En cas d'erreur SQL, on affiche rien pour ne pas casser la page
                    }

                    foreach($conversations as $conv): 
                        $classNonLu = ($conv['est_lu'] == 0 && $conv['id_expediteur'] != $monId) ? 'non-lu' : '';
                        $heure = date('H:i', strtotime($conv['date_envoi']));
                        
                        $imgSrc = '';
                        if (!empty($conv['photo_de_profil'])) {
                            $imgSrc = 'data:image/jpeg;base64,' . base64_encode($conv['photo_de_profil']);
                        }
                    ?>
                        <div class="element-conversation <?= $classNonLu ?>" 
                             onclick="changerConversation(<?= $conv['id_user'] ?>)"
                             data-id="<?= $conv['id_user'] ?>"
                             data-nom="<?= htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']) ?>">
                            
                            <div class="avatar-contact-placeholder">
                                <?php if($imgSrc): ?>
                                    <img src="<?= $imgSrc ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                                <?php endif; ?>
                            </div>
                            
                            <div class="info-contact">
                                <p class="nom-contact"><?= htmlspecialchars($conv['prenom'] . ' ' . $conv['nom']) ?></p>
                                <p class="dernier-message"><?= htmlspecialchars(substr($conv['message'], 0, 30)) ?>...</p>
                            </div>
                            
                            <div class="infos-droite">
                                <span class="heure-message"><?= $heure ?></span>
                                <?php if($classNonLu): ?> <span class="compteur-non-lu">!</span> <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="panneau-chat">
                <div class="entete-chat">
                    <div class="info-interlocuteur">
                        <div class="avatar-chat-placeholder"></div>
                        <div>
                            <h2 class="nom-interlocuteur" id="chat-titre-nom">Sélectionnez une conversation</h2>
                        </div>
                    </div>
                </div>
                
                <div class="zone-messages" id="chat-container-messages">
                    <div class="separateur-date"><span>Messagerie</span></div>
                </div>
                
                <div class="zone-saisie">
                    <input type="text" placeholder="Écrire un message...">
                    <button class="bouton-envoyer">➤</button>
                </div>
            </section>
        </div>
    </main>

    <!--FOOTER-->
    <footer class="pied-de-page">
        <div class="conteneur-footer">
            <div class="logo2">
                <div class="placeholder-reseau">
                    <img src="../images/twitter.jpg" alt="Twitter" class="image-reseau">
                </div>
                <div class="placeholder-reseau">
                    <img src="../images/instagram.png" alt="Instagram" class="image-reseau">
                </div>
            </div>
            <a href="../html/NousContacter.html" class="texte-contact">Contact</a>
        </div>
    </footer>
    
    <script src="../javascript/javamessagerie.js?v=2"></script>
</body>
</html>