<html>
    <body>
        <?php
            $nombase = "sae";
            $adresse_base = "localhost"; 
            $port = "3306";
            $username = "root";
            $password = "";

            try {
                // Création de la connexion PDO
                $pdo = new PDO("mysql:host=$adresse_base;port=$port;dbname=$nombase;charset=utf8", $username, $password);

            

                //echo "Connexion réussie à la base $nombase";
            } catch (Throwable $e) {
                die($e->getMessage());
            }
        ?>
    </body>
</html>
