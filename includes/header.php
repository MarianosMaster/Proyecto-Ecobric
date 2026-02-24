<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Calcular número de items en carrito (simulación por ahora)
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecobric - Materiales Ecológicos</title>
    <!-- Fuentes de Google: Una rústica para títulos, otra moderna limpia para textos -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header class="main-header">
        <div class="container header-container">
            <a href="index.php" class="logo">
                <!-- Placeholder de logo. ¡Cámbialo cuando tengas el tuyo! -->
                <i class="fa-solid fa-leaf"></i> Ecobric
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                    <li><a href="calculadora.php">Calculadora <span class="badge">Nuevo</span></a></li>
                    <li><a href="nosotros.php">Nosotros</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="perfil.php" class="btn-icon"><i class="fa-solid fa-user"></i></a>
                    <a href="logout.php" class="btn-icon"><i class="fa-solid fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="login.php" class="btn-icon" title="Iniciar Sesión"><i class="fa-solid fa-user"></i></a>
                <?php endif; ?>
                <a href="carrito.php" class="btn-icon cart-icon">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count">
                            <?php echo $cart_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
            <!-- Botón menú móvil -->
            <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>
        </div>
    </header>
    <main class="main-content">