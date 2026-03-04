<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    die("Acceso denegado");
}

$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Ruta absoluta al script de python
$python_script = realpath(__DIR__ . '/../scripts/export_gastos.py');

// Comando para ejecutar python. Asegurarse de que 'python' está en el PATH del servidor.
$command = escapeshellcmd("python \"$python_script\" $mes $anio");
$output = shell_exec($command);

// El script de python debería devolver la ruta del archivo generado
$file_path = trim($output);

if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);

    // Opcional: Eliminar el archivo después de descargarlo para no ocupar espacio
    unlink($file_path);
    exit;
} else {
    echo "Error al generar el archivo Excel. Revisa los logs del servidor o ejecuta el script manualmente. Salida: " . htmlspecialchars($output);
}
?>