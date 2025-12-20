<?php
    require '../../Includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les sites UPJV</title>
    <link rel="stylesheet" href="../styles/styles_SitesUPJV.css">
</head>
<body>
    <!--HEADER-->
    <header class="barre-navigation">
        <div class="nav-gauche">
            <a href="./Menu.html">
            <div class="logo-navigation-placeholder"></div>
            </a>
            <span class="site-nom">nom</span>
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
                    <img src="" alt="image profil">
                </a>
            </div>
        </div>
    </header> 

    <main class="contenu-principal">
        <h1 class="titre-page">Les sites UPJV</h1>
        
        <div class="liste-sites">
            <div class="carte-site">
                <h2 class="nom-site">IUT amiens</h2>
                <p class="adresse-site">Adresse: Avenue des Facultés, Le Bailly, 80025 Amiens</p>
            </div>
            
            <div class="carte-site">
                <h2 class="nom-site">Faculté de médecine</h2>
                <p class="adresse-site">Adresse: Rue du Campus, 80000 Amiens</p>
            </div>
            
            <div class="carte-site">
                <h2 class="nom-site">Campus scientifique</h2>
                <p class="adresse-site">Adresse: 33 rue Saint-Leu, 80039 Amiens.</p>
            </div>
            
            <div class="carte-site">
                <h2 class="nom-site">Campus saint-charles</h2>
                <p class="adresse-site">Adresse: 3 rue des Louvels, 80036 Amiens</p>
            </div>

            <div class="carte-site">
                <h2 class="nom-site">Campus cathédrale</h2>
                <p class="adresse-site">Adresse: 10 placette Lafleur, 80027 Amiens</p>
            </div>
            
            <div class="carte-site">
                <h2 class="nom-site">Campus citadelle</h2>
                <p class="adresse-site">Adresse: 10 rue des Français libres, 80080 Amiens</p>
            </div>
            
            <div class="carte-site">
                <h2 class="nom-site">Faculté d'arts</h2>
                <p class="adresse-site">Adresse: [Adresse non fournie, à ajouter]</p>
            </div>
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
</body>
</html>