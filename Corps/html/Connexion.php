<?php
    require '../../Includes/config.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $identifiant = $_POST['identifiant'];
        $mdp = $_POST['password'];

        if(empty($identifiant) || empty($mdp)){
        } else {
            $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE email = :email");
            $stmt->bindParam(':email', $identifiant);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mdp, $user['mdp'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['identifiant'] = $user['email'];
                header("Location: Menu.php");
                exit();
            } else {
                echo "<script>alert('Identifiant ou mot de passe incorrect.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vitrine</title>
    <link rel="stylesheet" type="text/css" href="../styles/styles_connexion.css">
</head>
<!--<script src="../javascript/javaconnexion.js"></script>!-->
<body>
    <div class="Empilement">
        <h1>Connexion</h1>

    <form method="POST" action="">
    <div class="champ">
        <p>Identifiant: </p>
        <input type="email" id="identifiant" name="identifiant" placeholder="Entrez votre identifiant">
    </div>
    <div class="champ">
        <p>Mot de passe: </p>
        <div class="gestionmdp">
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
            <img src="../images/vuemdp.jpg" alt="Afficher le mot de passe" id="imagevue" width="30" height="30">
        </div>
    </div>

        <div class="buttons">
            <a href="Menu.php">
                <button>Se Connecter</button>
            </a>
        </div>
        </form>
    </div>
</body>
</html>