<?php
// Informations de connexion à la base de données
$host = 'localhost'; // L'hôte de la base de données, généralement 'localhost' si elle est hébergée localement
$dbname = 'premiumshop'; // Le nom de votre base de données
$username = 'root'; // Le nom d'utilisateur pour accéder à la base de données
$password = ''; // Le mot de passe pour accéder à la base de données

try {
    // Création d'une nouvelle instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Définir l'attribut PDO pour générer des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optionnel : Définir le mode de récupération par défaut
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Si la connexion est réussie, vous pouvez éventuellement afficher un message ou effectuer d'autres opérations
    // echo "Connexion réussie à la base de données.";

} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher un message d'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
