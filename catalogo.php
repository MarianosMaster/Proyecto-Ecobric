<?php
require_once 'config/db.php';

// Obtener categorías para el filtro
$stmtCategorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre ASC");
$categorias = $stmtCategorias->fetchAll();

// Obtener productos (con filtro opcional)
$whereClause = "";
$params = [];
$categoriaSeleccionada = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;

if ($categoriaSeleccionada > 0) {
    $whereClause = "WHERE p.categoria_id = ?";
    $params[] = $categoriaSeleccionada;
}

$stmtProductos = $pdo->prepare("SELECT p.*, c.nombre as categoria_nombre FROM productos p JOIN categorias c ON p.categoria_id = c.id $whereClause ORDER BY p.nombre ASC");
$stmtProductos->execute($params);
$productos = $stmtProductos->fetchAll();

include 'includes/header.php';
?>

<div class="page-header"
    style="background-color: var(--primary-dark); padding: 4rem 0; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; margin-bottom: 0.5rem;">Catálogo de Productos</h1>
        <p>Encuentra los mejores materiales ecológicos para tu proyecto.</p>
    </div>
</div>

<section class="section-padding bg-light">
    <div class="container" style="display: flex; gap: 3rem;">

        <!-- Sidebar Filtros -->
        <aside style="width: 250px; flex-shrink: 0;">
            <div
                style="background: white; padding: 1.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); position: sticky; top: 100px;">
                <h3
                    style="font-size: 1.2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                    Categorías</h3>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 0.5rem;">
                        <a href="catalogo.php"
                            style="color: <?php echo $categoriaSeleccionada == 0 ? 'var(--primary-color)' : 'var(--text-dark)'; ?>; font-weight: <?php echo $categoriaSeleccionada == 0 ? 'bold' : 'normal'; ?>;"><i
                                class="fa-solid fa-angle-right"></i> Todos los productos</a>
                    </li>
                    <?php foreach ($categorias as $cat): ?>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="catalogo.php?categoria=<?php echo $cat['id']; ?>"
                                style="color: <?php echo $categoriaSeleccionada == $cat['id'] ? 'var(--primary-color)' : 'var(--text-dark)'; ?>; font-weight: <?php echo $categoriaSeleccionada == $cat['id'] ? 'bold' : 'normal'; ?>;">
                                <i class="fa-solid fa-angle-right"></i>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Main Content (Lista de productos) -->
        <div style="flex-grow: 1;">
            <?php if (count($productos) > 0): ?>
                <div class="product-grid">
                    <?php foreach ($productos as $producto): ?>
                        <div class="product-card">
                            <?php if ($producto['es_calculable_volumen']): ?>
                                <span class="product-badge"><i class="fa-solid fa-calculator"></i> Calculable</span>
                            <?php endif; ?>
                            <img src="<?php echo $producto['url_imagen'] ?? 'https://images.unsplash.com/photo-1621644781442-9fc7a08e16ea?w=500&auto=format&fit=crop'; ?>"
                                alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image">
                            <div class="product-info">
                                <span class="product-category">
                                    <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
                                </span>
                                <h3 class="product-title">
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </h3>
                                <div class="product-price">
                                    <?php echo number_format($producto['precio'], 2, ',', '.'); ?> €
                                </div>
                                <div style="display:flex; gap:0.5rem; margin-top: 1rem;">
                                    <a href="producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-outline"
                                        style="flex:1; padding: 0.5rem; text-align: center;">Ver Detalle</a>
                                    <button class="btn btn-primary add-to-cart-btn" data-id="<?php echo $producto['id']; ?>"
                                        style="padding: 0.5rem;" title="Añadir al carrito">
                                        <i class="fa-solid fa-cart-plus"></i> <span style="font-size: 0.9rem;">Añadir</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div
                    style="text-align: center; padding: 4rem; background: white; border-radius: var(--border-radius); box-shadow: var(--shadow-sm);">
                    <i class="fa-solid fa-box-open" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-muted);">No se encontraron productos en esta categoría.</h3>
                    <a href="catalogo.php" class="btn btn-primary" style="margin-top: 1rem;">Ver todos los productos</a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>