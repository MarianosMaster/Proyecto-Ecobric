<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Procesar Actualización de Contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Verificar actual
    $stmt = $pdo->prepare("SELECT contrasena FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($current_pass, $user['contrasena'])) {
        if ($new_pass === $confirm_pass && strlen($new_pass) >= 6) {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
            if ($update->execute([$hash, $user_id])) {
                $success = "Contraseña actualizada exitosamente.";
            } else {
                $error = "Error al actualizar la base de datos.";
            }
        } else {
            $error = "La nueva contraseña no coincide o es demasiado corta.";
        }
    } else {
        $error = "La contraseña actual es incorrecta.";
    }
}

// Obtener datos de usuario actuales
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$userData = $stmt->fetch();

// Cargar pedidos (Usando creado_en y monto_total)
$stmtPedidos = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY creado_en DESC");
$stmtPedidos->execute([$user_id]);
$misPedidos = $stmtPedidos->fetchAll();

include '../includes/header.php';
?>

<div class="page-header" style="background-color: var(--primary-light); padding: 3rem 0; color: white;">
    <div class="container">
        <h1><i class="fa-solid fa-user-circle"></i> Mi Perfil</h1>
        <p>Gestiona tu cuenta y revisa tus pedidos.</p>
    </div>
</div>

<section class="section-padding" style="background-color: var(--bg-light); min-height: 60vh;">
    <div class="container">

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">

            <!-- Datos Personales y Contraseña -->
            <div>
                <div
                    style="background: white; padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                    <h3
                        style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Mis Datos Completos</h3>
                    <p><strong>Nombre:</strong>
                        <?php echo htmlspecialchars($userData['nombre']); ?>
                    </p>
                    <p><strong>Email:</strong>
                        <?php echo htmlspecialchars($userData['email']); ?>
                    </p>
                    <p><strong>Estado:</strong> <span style="color: var(--primary-color); font-weight: bold;">Verificado
                            <i class="fa-solid fa-check-circle"></i></span></p>
                </div>

                <div
                    style="background: white; padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm);">
                    <h3
                        style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        Cambiar Contraseña</h3>

                    <?php if ($success): ?>
                        <div
                            style="padding: 1rem; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div
                            style="padding: 1rem; background-color: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 1rem;">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="perfil.php" style="display: flex; flex-direction: column; gap: 1rem;">
                        <input type="hidden" name="update_password" value="1">
                        <div>
                            <label style="font-weight: 500;">Contraseña Actual</label>
                            <input type="password" name="current_password" required
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px; outline: none;">
                        </div>
                        <div>
                            <label style="font-weight: 500;">Nueva Contraseña</label>
                            <input type="password" name="new_password" required minlength="6"
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px; outline: none;">
                        </div>
                        <div>
                            <label style="font-weight: 500;">Confirmar Nueva</label>
                            <input type="password" name="confirm_password" required minlength="6"
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px; outline: none;">
                        </div>
                        <button type="submit" class="btn btn-outline"
                            style="align-self: flex-start; margin-top: 0.5rem;">Actualizar</button>
                    </form>
                </div>
            </div>

            <!-- Historial de Pedidos -->
            <div>
                <div
                    style="background: white; padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm);">
                    <h3
                        style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        <i class="fa-solid fa-box-open"></i> Historial de Pedidos
                    </h3>

                    <?php if (count($misPedidos) > 0): ?>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr
                                        style="background-color: var(--bg-light); border-bottom: 1px solid var(--border-color);">
                                        <th style="padding: 1rem; text-align: left;">ID Pedido</th>
                                        <th style="padding: 1rem; text-align: left;">Fecha</th>
                                        <th style="padding: 1rem; text-align: right;">Total</th>
                                        <th style="padding: 1rem; text-align: center;">Método</th>
                                        <th style="padding: 1rem; text-align: center;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($misPedidos as $ped): ?>
                                        <tr style="border-bottom: 1px solid var(--border-color);">
                                            <td style="padding: 1rem;"><strong>#
                                                    <?php echo str_pad($ped['id'], 5, '0', STR_PAD_LEFT); ?>
                                                </strong></td>
                                            <td style="padding: 1rem; color: var(--text-muted);">
                                                <?php echo date('d/m/Y H:i', strtotime($ped['creado_en'])); ?>
                                            </td>
                                            <td style="padding: 1rem; text-align: right; font-weight: bold;">
                                                <?php echo number_format($ped['monto_total'], 2, ',', '.'); ?> €
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <?php echo htmlspecialchars($ped['metodo_pago']); ?>
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <span
                                                    style="background-color: #e8f5e9; color: #2e7d32; padding: 0.3rem 0.6rem; border-radius: 12px; font-size: 0.85rem; font-weight: bold;">
                                                    <?php echo htmlspecialchars($ped['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem 0; color: var(--text-muted);">
                            <i class="fa-solid fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; color: #ccc;"></i>
                            <p>Aún no has realizado ninguna compra con Ecobric.</p>
                            <a href="catalogo.php" class="btn btn-primary" style="margin-top: 1rem;">Ir al Catálogo</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>