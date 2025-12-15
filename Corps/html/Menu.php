<?php
// 1. Démarrage de la session et Connexion à la base de données
session_start();

require '../include/Pdo_SAE.php';

// 2. Simulation de l'utilisateur connecté (Exemple : Alice, ID 1)
// Dans la réalité, cet ID viendrait de $_SESSION['id'] après le login
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = 1; 
}
$userId = $_SESSION['id'];

// Récupération des infos de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

// 3. Récupération du PROCHAIN TRAJET (Conducteur OU Passager confirmé)
$queryTrajet = "
    SELECT 
        t.*, 
        v.marque, 
        v.modele, 
        u.nom as nom_conducteur, 
        u.prenom as prenom_conducteur,
        u.id as id_conducteur_reel,
        -- On récupère aussi le statut de la réservation pour savoir si c'est 'CONFIRME'
        r.statut as statut_reservation
    FROM trajets t
    -- 1. On récupère les infos du véhicule associé au trajet
    LEFT JOIN vehicules v ON t.id_vehicule = v.id
    -- 2. On récupère les infos du conducteur du trajet
    LEFT JOIN utilisateurs u ON t.id_conducteur = u.id
    -- 3. ICI : On regarde la table 'reservations' pour voir si l'utilisateur connecté (:uid) est passager
    LEFT JOIN reservations r ON t.id = r.id_trajet AND r.id_passager = :uid
    WHERE 
        (
            t.id_conducteur = :uid                               -- CAS A : Je suis le conducteur
            OR 
            (r.id_passager = :uid AND r.statut = 'CONFIRME')     -- CAS B : Je suis passager confirmé (table reservations)
        )
        AND t.heure_depart >= NOW()        -- Le trajet n'est pas encore passé
        AND t.statut_trajet != 'ANNULE'    -- Le trajet n'est pas annulé
    ORDER BY t.heure_depart ASC            -- On prend le plus proche dans le temps
    LIMIT 1                                -- Un seul résultat
";

$stmtTrajet = $pdo->prepare($queryTrajet);
$stmtTrajet->execute(['uid' => $userId]);
$prochainTrajet = $stmtTrajet->fetch(PDO::FETCH_ASSOC);

// Calcul des places restantes (inchangé mais important pour l'affichage)
$placesPrises = 0;
if ($prochainTrajet) {
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE id_trajet = ? AND statut = 'CONFIRME'");
    $stmtCount->execute([$prochainTrajet['id']]);
    $placesPrises = $stmtCount->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal - <?= htmlspecialchars($user['prenom']) ?></title>
    <link rel="stylesheet" href="../styles/styles_Menu.css">
</head>
<body>
    <header class="barre-navigation">
        <div class="nav-gauche">
            <a href="./Menu.php">
            <div class="logo-navigation-placeholder"></div>
            </a>
            <span class="site-nom">BLABLANOM</span>
        </div>
        
        <div class="nav-centre">
            <button class="bouton-nav" onclick="window.location.href='./Trajets.html'"> Rechercher </button>
            <button class="bouton-nav" onclick="window.location.href='./PublierTrajet.html'"> Publier </button>
        </div>
        
        <div class="nav-droite">
            <div class="profil-avatar-placeholder">
                <a href="./Profiles.html">
                    <img src="../images/default_avatar.png" alt="Profil">
                </a>
            </div>
        </div>
    </header> 
 
    <main class="contenu-principal">
        <section class="section-presentation">
            <div class="presentation">
                Bienvenue sur notre plateforme de covoiturage pour les sites de l'UPJV à Amiens, la solution simple, économique et écologique pour vos déplacements du quotidien. Que vous soyez conducteur ou passager, 
                trouvez en quelques clics un trajet adapté à vos besoins et voyagez en toute confiance grâce à une communauté engagée et solidaire. 
                Ensemble, partageons nos routes et réduisons notre impact sur l’environnement.
            </div>
        </section>

        <section class="section-sites">
            <h2 class="titre-section">Les sites UPJV ></h2>
            <div class=" lessitesupjv">
                <a href="SitesUPJV.html" class="lien-sites-upjv">
                 <img src="../images/lessitesupjv.png" alt="les sites upjv" class="image-site-upjv">
                </a>
            </div>
        </section>
        
        <section class="section-prochain-trajet">
            <h2 class="titre-section">Prochain trajet :</h2>
            
            <?php if ($prochainTrajet): ?>
                <?php 
                    // Formatage des dates pour affichage (ex: 10:47)
                    $dateDepart = new DateTime($prochainTrajet['heure_depart']);
                    $dateArrivee = new DateTime($prochainTrajet['heure_arrivee_estimee']);
                ?>
                <div class="carte-trajet-reserve">
                    <div class="corps-carte">
                        
                        <div class="bloc-conducteur">
                            <a href="./Profiles.html?id=<?= $prochainTrajet['id_conducteur_reel'] ?>">
                                <div class="profil-mini-placeholder"></div>
                            </a>
                            <p class="detail-nom">
                                <?= htmlspecialchars($prochainTrajet['prenom_conducteur'] . ' ' . $prochainTrajet['nom_conducteur']) ?>
                            </p> 
                        </div>

                        <div class="bloc-trajet">
                            <div class="colonne-info">
                                <span class="ville-depart"><?= htmlspecialchars($prochainTrajet['point_depart']) ?></span>
                                <span class="detail-horaire"><?= $dateDepart->format('H:i') ?></span>
                                
                            </div>

                            <div class="colonne-fleches">
                                <span class="fleche">➞</span>
                                <span class="fleche">➞</span>
                            </div>

                            <div class="colonne-info">
                                <span class="ville-arrivee"><?= htmlspecialchars($prochainTrajet['point_arrivee']) ?></span>
                                <span class="detail-horaire"><?= $dateArrivee->format('H:i') ?></span>
                            </div>
                        </div>
                        
                        <span class="date-trajet" style="font-size:0.8em; color:#666;"><?= $dateDepart->format('d/m/Y') ?></span>

                        <a href="./Messageries.html?trajet=<?= $prochainTrajet['id'] ?>">
                            <div class="icone-message-placeholder">💬</div>
                        </a>
                    </div>

                    <div class="detail-infos-bas">
                        <div class="infos-pratiques">
                            <span class="info-place"><span class="icone-personne">👤</span> <?= $placesPrises ?>/<?= $prochainTrajet['places_totales'] ?></span>
                            <span class="info-voiture"><?= htmlspecialchars($prochainTrajet['marque'] . ' ' . $prochainTrajet['modele']) ?></span>
                            <span class="info-prix"><?= number_format($prochainTrajet['prix'], 2) ?>€</span>
                        </div>
                        <button class="bouton-annuler" onclick="alert('Fonctionnalité d\'annulation à implémenter')">Annuler</button>
                    </div>
                </div>

            <?php else: ?>
                <div class="carte-trajet-reserve" style="justify-content: center; align-items: center; padding: 20px;">
                    <p>Aucun trajet prévu prochainement.</p>
                </div>
            <?php endif; ?>

        </section>

        <section class="section-communication">
            <h2 class="titre-section">Pour communiquer avec les utilisateurs</h2>
            <a href="#" class="bouton-messagerie">Messagerie</a>
        </section>

    </main>
    
    <script src="../javascript/javamenu.js"></script>
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
</body>
</html>