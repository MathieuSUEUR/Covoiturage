<?php
    require '../../Includes/config.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom= htmlspecialchars($_POST['nom']);
        $prenom= htmlspecialchars($_POST['prenom']);
        $email= htmlspecialchars($_POST['email']);
        $telephone= htmlspecialchars($_POST['telephone']);
        $mdp= password_hash($_POST['mot-de-passe'], PASSWORD_BCRYPT);
        $civilite= $_POST['civilite'];
        $date_naissance= $_POST['date-naissance'];

        if(empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($mdp) || empty($civilite) || empty($date_naissance)){
            echo "<script>alert('Veuillez remplir tous les champs.');</script>";
        } else {
            
            $stmt = $pdo->prepare("INSERT INTO Utilisateurs (nom, prenom, email, telephone, mdp, civilite, date_naissance) VALUES (:nom, :prenom, :email, :telephone, :mdp, :civilite, :date_naissance)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':mdp', $mdp);
            $stmt->bindParam(':civilite', $civilite);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->execute();

            if($stmt){
                header("Location: Menu.php");
                exit();
            } else {
                echo "<script>alert('Erreur lors de l'inscription.');</script>";
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
            
            <form class="formulaire">
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
                        <span class="icone-oeil-placeholder">👁️</span>
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
</body>
</html>