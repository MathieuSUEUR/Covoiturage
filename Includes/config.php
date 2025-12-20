<?php

// création de session
session_start();


// PDO 

$host = "mysql-lukassae.alwaysdata.net";
$dbname = "lukassae_bdd";
$username="lukassae";
$mdp="IUTAmiens1507";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $mdp);

} catch (PDOException $e){
    die("Erreur de connexion: " . $e->getMessage());
}


?>