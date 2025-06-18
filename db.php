<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "orkut";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // define o modo de erro do PDO como exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conectado com sucesso";
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}
?>