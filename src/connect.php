<?php

try {
    $dbName = 'netflix';
    $host = 'localhost';
    $utilisateur = 'root';
    $motDePasse = 'root';
    $port = '8889';
    $dns = 'mysql:host=' . $host . ';dbname=' . $dbName . ';port=' . $port;
    $db = new PDO($dns, $utilisateur, $motDePasse);
} catch (Exception $e) {
    die('Erreur:' . $e);
}
