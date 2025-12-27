<?php
require __DIR__ . '/../../Includes/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id_user'])) {
    $_SESSION['id_user'] = 1; 
}

$monId = $_SESSION['id_user'];

if (isset($_GET['test_id']) && !empty($_GET['test_id'])) {
    $profilId = (int)$_GET['test_id']; 
} else {
    $profilId = $monId;
}

$sql = "SELECT * FROM Utilisateurs WHERE id_user = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profilId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

$age = floor((time() - strtotime($user['date_naissance'])) / 31536000);


$imgSrc = "../images/default_avatar.png";
if (!empty($user['photo_de_profil'])) {
    $imgSrc = 'data:image/jpeg;base64,' . base64_encode($user['photo_de_profil']);
}

if(!isset($user['voiture'])){
    $user['voiture'] = "Non renseigné";
}

$sqlAvis = "SELECT AVG(note) as moyenne, COUNT(*) as total_avis FROM Avis WHERE id_user = ?";
$stmtAvis = $pdo->prepare($sqlAvis);
$stmtAvis->execute([$profilId]);
$infoAvis = $stmtAvis->fetch(PDO::FETCH_ASSOC);

if(!$infoAvis['moyenne']){
    $note = $infoAvis['moyenne'] = 0;
}else{
    $note = round($infoAvis['moyenne'], 1);
}

$nbAvis = $infoAvis['total_avis'];

$noteArrondie = round($note);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../styles/styles_Profiles.css">
</head>
<body>
    <!--HEADER-->
    <header class="barre-navigation">
        <div class="nav-gauche">
            <a href="./Menu.php">
            <div class="logo-navigation-placeholder"></div>
            </a>
            <span class="site-nom">Covoit'UPJV</span>
        </div>
         
        <div class="nav-centre">
            <a href="./Trajets.php">
                <button class="bouton-nav" > Rechercher </button>
            </a>
            <a href="./PublierTrajet.php">
                <button class="bouton-nav" > Publier </button>
            </a>
        </div>
        
        <div class="nav-droite">
            <div class="profil-avatar-placeholder">
                <a href="./Profiles.php">
                    <img src="<?php echo $imgSrc; ?>" alt="image profil" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                </a>
            </div>
        </div>
    </header> 

    <main class="contenu-principal">
        <h1 class="titre-page">Profil</h1>

        <div class="fiche-profil">
            <div class="entete-profil">
                <div class="avatar-grand-placeholder">
                    <img src="<?php echo $imgSrc; ?>" alt="Photo de profil" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                </div>
                <div class="details-base">
                    <h2 class="nom-utilisateur"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h2>
                    <p class="age-utilisateur">Age : <?php echo $age ?></p>
                    <div class="evaluation">
                        <span class="etoiles">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $noteArrondie) {
                                    echo '<span class="etoile-pleine">★</span>';
                                } else {
                                    echo '<span class="etoile-vide">★</span>';
                                }
                            }
                            ?>
                        </span> 
                        <span class="nombre-avis"><?php echo $note; ?>/5 (<?php echo $nbAvis; ?> avis)</span>
                    </div>
                </div>
            </div>
            
            <section class="section-infos">
                <div class="ligne-info">
                    <span class="etiquette">Établissement:</span>
                    <span class="valeur"><?php echo htmlspecialchars($user['etablissement']) ?></span>
                </div>
                <div class="ligne-info">
                    <span class="etiquette">N° tel:</span>
                    <span class="valeur"><?php echo htmlspecialchars($user['telephone']) ?></span>
                </div>
                <div class="ligne-info voiture">
                    <span class="etiquette">voiture:</span>
                    <span class="valeur"><?php echo htmlspecialchars($user['voiture']) ?></span>
                </div>
            </section>
            
            <a href="EditionProfile.php" class="bouton-editer">Éditer le profil</a>
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
            <a href="../html/NousContacter.php" class="texte-contact">Contact</a>
        </div>
    </footer>
</body>
</html>