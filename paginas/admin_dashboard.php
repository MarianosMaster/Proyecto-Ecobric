<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header("Location: ../index.php");
    exit();
}

// Lógica para exportar Excel se manejará en otro archivo API

// ----- Estadísticas Rápidas -----
// Total Usuarios
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol_id = 2");
$total_clientes = $stmt->fetchColumn();

// Stock Bajo (menos de 20 unidades)
$stmt = $pdo->query("SELECT COUNT(*) FROM productos WHERE stock < 20");
$stock_bajo = $stmt->fetchColumn();

// Gastos e Ingresos del mes actual
$mes_actual = date('m');
$anio_actual = date('Y');

// Total Ingresos (pedidos pagados del mes)
$stmt = $pdo->prepare("SELECT COALESCE(SUM(monto_total), 0) FROM pedidos WHERE estado = 'PAGADO' AND MONTH(creado_en) = ? AND YEAR(creado_en) = ?");
$stmt->execute([$mes_actual, $anio_actual]);
$ingresos_mes = $stmt->fetchColumn();

// Total Gastos (Entradas por compras a proveedores). Asumiremos que el monto gastado se guardará en notas temporalmente o en una nueva columna. 
// Para ser exactos, si simulamos compras, añadiremos la columna costo_total en movimientos_inventario o extraeremos de la relación producto_proveedor.
// Por ahora, aproximaremos el gasto a: SUM(cantidad * precio proveedor) si tuviéramos ese dato en movimientos.
// En un paso posterior ajustaremos la tabla si es necesario. Por ahora un placeholder de consulta realista:
// Buscamos todas las ENTRADAS (excepto el inicial que pusimos como 'Inventario inicial') y las valoramos (Placeholder).
$stmt = $pdo->prepare("SELECT COALESCE(SUM(cantidad * (SELECT precio_suministro FROM producto_proveedor pp WHERE pp.producto_id = mi.producto_id LIMIT 1)), 0) 
                        FROM movimientos_inventario mi 
                        WHERE tipo_movimiento = 'ENTRADA' AND notas != 'Inventario inicial' AND MONTH(fecha_movimiento) = ? AND YEAR(fecha_movimiento) = ?");
$stmt->execute([$mes_actual, $anio_actual]);
$gastos_mes = $stmt->fetchColumn();


$page_title = "Panel de Administración";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ecobric Admin</title>
    <!-- Fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos -->
    <link rel="stylesheet" href="../css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
    
    <!-- Navbar superior genérico -->
    <?php include '../includes/header.php'; ?>

    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h2>EcoAdmin</h2>
            </div>
            <ul class="admin-nav">
                <li><a href="admin_dashboard.php" class="active"><i class="fa-solid fa-chart-line"></i> Resumen</a></li>
                <li><a href="admin_compras_proveedor.php"><i class="fa-solid fa-truck-fast"></i> Pedidos a Proveedor</a></li>
                <li><a href="admin_productos.php"><i class="fa-solid fa-boxes-stacked"></i> Productos & Stock</a></li>
                <li><a href="admin_usuarios.php"><i class="fa-solid fa-users"></i> Usuarios</a></li>
                <li><a href="#" id="btn-export-excel-modal"><i class="fa-solid fa-file-excel"></i> Reporte Excel</a></li>
                <li><a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Volver a Tienda</a></li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>Panel de Resumen</h1>
                <div>
                    <span style="color: var(--text-muted);"><i class="fa-regular fa-calendar"></i> <?php echo date('d M Y'); ?></span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <a href="admin_usuarios.php" style="text-decoration: none; color: inherit;">
                    <div class="stat-card" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info">
                            <h3>Clientes Totales</h3>
                            <p><?php echo $total_clientes; ?></p>
                        </div>
                    </div>
                </a>
                
                <a href="admin_productos.php?filtro=bajo" style="text-decoration: none; color: inherit;">
                    <div class="stat-card warning" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="stat-info">
                            <h3>Stock Crítico (< 20)</h3>
                            <p><?php echo $stock_bajo; ?></p>
                        </div>
                    </div>
                </a>

                <a href="admin_finanzas.php" style="text-decoration: none; color: inherit;">
                    <div class="stat-card" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="stat-icon" style="background-color: #e3f2fd; color:#1565c0;"><i class="fa-solid fa-arrow-trend-up"></i></div>
                        <div class="stat-info">
                            <h3>Ingresos Venta (Mes)</h3>
                            <p><?php echo number_format($ingresos_mes, 2, ',', '.'); ?> €</p>
                        </div>
                    </div>
                </a>

                <a href="admin_finanzas.php" style="text-decoration: none; color: inherit;">
                    <div class="stat-card accent" style="border-top-color: #d32f2f; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                        <div class="stat-icon" style="background-color: #ffebee; color:#d32f2f;"><i class="fa-solid fa-arrow-trend-down"></i></div>
                        <div class="stat-info">
                            <h3>Gastos Proveedor (Mes)</h3>
                            <p><?php echo number_format($gastos_mes, 2, ',', '.'); ?> €</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Actividad Reciente o Resumen (Opcional) -->
            <div class="admin-panel-section">
                <div class="admin-panel-header">
                    <h2>Últimos Pedidos de Clientes</h2>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT p.id, u.nombre, p.creado_en, p.estado, p.monto_total 
                                             FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id 
                                             ORDER BY p.creado_en DESC LIMIT 5");
                        while ($row = $stmt->fetch()):
                            $badgeClass = ($row['estado'] == 'PAGADO') ? 'status-active' : (($row['estado'] == 'PENDIENTE') ? 'status-warning' : 'status-inactive');
                        ?>
                        <tr>
                            <td>#<?php echo str_pad($row['id'], 5, "0", STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['creado_en'])); ?></td>
                            <td><span class="status-badge <?php echo $badgeClass; ?>"><?php echo $row['estado']; ?></span></td>
                            <td><?php echo number_format($row['monto_total'], 2); ?> €</td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($stmt->rowCount() == 0): ?>
                            <tr><td colspan="5" style="text-align: center;">No hay pedidos recientes.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <?php include '../includes/admin_excel_modal.php'; ?>
</body>
</html>
