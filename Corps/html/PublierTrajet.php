<?php
    require '../../Includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un trajet</title>
    
    <link rel="stylesheet" href="../styles/styles_PublierTrajet.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
</head>

<body>
    <header class="barre-navigation">
        <div class="nav-gauche">
            <a href="./Menu.php">
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
        <h1 class="titre-page">Publier un trajet</h1>

        <div class="conteneur-recherche">
            
            <div class="groupe-resultats-gauche">
                <div class="bloc-filtres">
                    <span class="texte-trier">Détails du trajet</span>
                    
                    <form class="formulaire-details" onsubmit="event.preventDefault();">
                        
                        <div class="filtre-heure show-always">
                            <label>Horaires :</label>
                            <div class="ligne-horaire">
                                <div class="colonne-input">
                                    <span>Départ</span>
                                    <input type="time" class="input-classique">
                                </div>
                                <div class="colonne-input">
                                    <span>Arrivée</span>
                                    <input type="time" class="input-classique">
                                </div>
                            </div>
                        </div>

                        <hr class="separateur">

                        <div class="filtre-heure show-always">
                            <label>Infos pratiques :</label>
                            <div class="ligne-horaire">
                                <div class="colonne-input">
                                    <span>Prix (€)</span>
                                    <input type="number" placeholder="0" class="input-classique">
                                </div>
                                <div class="colonne-input">
                                    <span>Places</span>
                                    <select class="input-classique">
                                        <option>1</option>
                                        <option>2</option>
                                        <option selected>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="groupe-cgu">
                            <input type="checkbox" id="cgu">
                            <label for="cgu">J'accepte les CGU</label>
                        </div>

                        <button class="bouton-publier-final">Confirmer la publication</button>
                    </form>
                </div>
            </div>
            <div class="bloc-filtres-carte">

                <div class="bloc-filtres-carte">
            <div class="bloc-carte-formulaire">
                <p class="instruction-selection">Définir l'itinéraire :</p>
                
                <div class="champs-carte">
                    <div class="champ-adresse depart">
                        <label>Départ</label>
                        <input type="text" id="input-depart" placeholder="Rechercher une adresse (ex: Paris)">
                    </div>

                    <div class="champ-adresse arrivee">
                        <label>Arrivée</label>
                        <input type="text" id="input-arrivee" placeholder="Rechercher une adresse (ex: Lyon)">
                    </div>
                </div>
                
                <div class="carte-placeholder">
                    <div id="map"></div>
                </div>

                <button class="bouton-valider">Valider le trajet sur la carte</button>
                </div>
             </div>
            </div>

        </div>
    </main>
    <script src="../javascript/javapubliertrajet.js"></script>
    <script src="../javascript/map.js"></script>
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