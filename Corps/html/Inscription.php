<?php
    require '../../Includes/config.php'; 


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $mdp = $_POST['mot-de-passe']; 
        $civilite = isset($_POST['civilite']) ? $_POST['civilite'] : '';       
        $date_naissance = $_POST['date-naissance'];

        if(empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($mdp) || empty($civilite) || empty($date_naissance)){
        } else {

            $verif_email = $pdo->prepare("SELECT COUNT(*) FROM Utilisateurs WHERE email = :email");
            $verif_email->bindParam(':email', $email);
            $verif_email->execute();

            if($verif_email->fetchColumn() > 0){
                exit();
            } else {

                // Vérification du mot de passe
                if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $mdp)) {
                    exit();
                } else {
                    

                    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
                    
                    $date_form = DateTime::createFromFormat('d/m/Y', $date_naissance);
                    $date_bd = null;

                    if($date_form->format('d/m/Y') === $date_naissance){
                        $date_bd = $date_form->format('Y-m-d');

                        // Début de l'insertion SQL
                        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, email, telephone, mdp, civilite, date_naissance) VALUES (:nom, :prenom, :email, :telephone, :mdp, :civilite, :date_bd)");
                        $stmt->bindValue(':nom', htmlspecialchars($nom));
                        $stmt->bindValue(':prenom', htmlspecialchars($prenom));
                        $stmt->bindValue(':email', htmlspecialchars($email));
                        $stmt->bindValue(':telephone', htmlspecialchars($telephone));
                        $stmt->bindValue(':mdp', $mdp_hash);
                        $stmt->bindValue(':civilite', $civilite);
                        $stmt->bindValue(':date_bd', $date_bd);

                        if($stmt->execute()){
                            header("Location: Menu.php");
                            exit();
                        } else {
                            echo "<script>alert('Erreur lors de l\'inscription.');</script>";
                        }
                        
                    } else {
                        exit();
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/styles_inscription.css">
</head>
<body>
    <div class="contenant-inscription">
        <main class="formulaire-panneau">
            <h1 class="titre-inscription">Inscription</h1>
            
            <form class="formulaire" id="formInscription" method="POST" action="">
                <div class="groupe-champ">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                
                <div class="groupe-champ">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                
                <div class="groupe-champ">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="groupe-champ telephone">
                    <label for="telephone">Tél.:</label>
                    <div class="champ-telephone">
                        <span class="prefixe">+33</span>
                        <input type="tel" id="telephone" name="telephone" required>
                    </div>
                </div>
                
                <div class="groupe-champ">
                    <label for="mot-de-passe">Mot de passe:</label>
                    <div class="champ-mot-de-passe">
                        <input type="password" id="mot-de-passe" name="mot-de-passe" required>
                        <span class="icone-oeil-placeholder" id="imagevue">👁️</span>
                        <span class="icone-aide-placeholder">❓</span> 
                    </div>
                </div>

                <div class="groupe-champ">
                    <label>Civilité:</label>
                    <div class="groupe-radio">
                        <input type="radio" id="femme" name="civilite" value="Femme">
                        <label for="femme">Femme</label>
                        <input type="radio" id="homme" name="civilite" value="Homme">
                        <label for="homme">Homme</label>
                    </div>
                </div>

                <div class="groupe-champ">
                    <label for="date-naissance">Date de naissance:</label>
                    <input type="text" id="date-naissance" name="date-naissance" placeholder="../../...." required>
                </div>
                
                <button type="submit" class="bouton-soumettre">S'inscrire</button>
            </form>
        </main>
    </div>
    <script src="../javascript/javainscription.js"></script>
</body>
</html>