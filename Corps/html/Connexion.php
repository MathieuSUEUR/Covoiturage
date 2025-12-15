<?php
session_start(); // Important pour la connexion

require '../include/Pdo_SAE.php'; 

$message_erreur = "";

// On vérifie si le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];


    try {

        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?"); 
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();

        if ($user && $password == $user['mot_de_passe']) {

            // possible HASH du ID pour éviter les failles de sécurité

            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            

            header("Location: menu.php");
            exit();
        } else {
            $message_erreur = "Identifiant ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $message_erreur_debug = "Erreur BDD : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vitrine</title>
    <link rel="stylesheet" type="text/css" href="../styles/styles_connexion.css">
</head>
<script src="../javascript/javaconnexion.js"></script>
<body>

    <div class="Empilement">
        <h1>Connexion</h1>

        <form action="" method="POST">
            
            <div class="champ">
                <p>Identifiant: </p>
                <input type="text" id="identifiant" name="identifiant" placeholder="Entrez votre identifiant" required>
            </div>
            
            <div class="champ">
                <p>Mot de passe: </p>
                <div class="gestionmdp">
                    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    <img src="../images/vuemdp.jpg" alt="Afficher le mot de passe" id="imagevue" width="30" height="30" style="cursor:pointer;">
                </div>
            </div>
            
            <?php if (!empty($message_erreur)): ?>
                <p style="color: red; text-align: center;"><?php echo $message_erreur; ?></p>
            <?php endif; ?>

            <div class="buttons">
                <button type="submit">Se Connecter</button>
            </div>

        </form>
    </div>
</body>
</html>