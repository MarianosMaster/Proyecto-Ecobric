<?php
// config/db.php
$host = 'localhost';
$user = 'root';        // Tu usuario de XAMPP (suele ser root)
$password = '';        // Tu contraseña de XAMPP (suele estar vacía por defecto)
$dbname = 'ecobric_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    // Configurar PDO para reportar todos los errores (excepciones)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Configurar el modo de fetch predeterminado a array asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>