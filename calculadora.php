<?php
require_once 'config/db.php';

// Obtener productos calculables
$stmt = $pdo->query("SELECT * FROM productos WHERE es_calculable_volumen = 1");
$productosCalculables = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header"
    style="background-color: var(--primary-light); padding: 4rem 0; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; margin-bottom: 0.5rem;"><i class="fa-solid fa-calculator"></i> Calculadora de
            Materiales</h1>
        <p>Calcula exactamente lo que necesitas o elige un proyecto preconfigurado.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">

        <!-- Pestañas -->
        <div
            style="display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;">
            <button class="btn btn-primary" id="btn-tab-volumen" onclick="switchTab('volumen')">Cálculo por
                Volumen</button>
            <button class="btn btn-outline" id="btn-tab-proyectos" onclick="switchTab('proyectos')">Proyectos Tipo (Kits
                Rápido)</button>
        </div>

        <!-- TAB 1: Calculadora de Volumen -->
        <div id="tab-volumen" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
            <!-- Formulario -->
            <div
                style="background: white; padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm);">
                <h3 style="margin-bottom: 1.5rem;">Dimensiones de tu obra</h3>

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div>
                        <label style="font-weight: 500; display: block; margin-bottom: 0.5rem;">Selecciona el material a
                            calcular</label>
                        <select id="calc-material"
                            style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            <option value="">-- Elige un material --</option>
                            <?php foreach ($productosCalculables as $prod): ?>
                                <option value="<?php echo $prod['id']; ?>"
                                    data-rendimiento="<?php echo $prod['rendimiento_por_m3']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                    data-precio="<?php echo $prod['precio']; ?>">
                                    <?php echo htmlspecialchars($prod['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                        <div>
                            <label>Alto (m)</label>
                            <input type="number" id="calc-alto" step="0.01" min="0" placeholder="Ej: 2.50"
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                        <div>
                            <label>Ancho (m)</label>
                            <input type="number" id="calc-ancho" step="0.01" min="0" placeholder="Ej: 4.00"
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                        <div>
                            <label>Prof. (m)</label>
                            <input type="number" id="calc-prof" step="0.01" min="0" placeholder="Ej: 0.15"
                                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                    </div>

                    <button onclick="calcularVolumen()" class="btn btn-accent"
                        style="width: 100%; margin-top: 1rem;">Calcular Necesidad</button>
                </div>
            </div>

            <!-- Resultados -->
            <div
                style="background: var(--bg-light); padding: 2rem; border-radius: var(--border-radius); border: 2px dashed var(--primary-color); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <h3 style="color: var(--primary-dark); margin-bottom: 1rem;">Resultado Estimado</h3>

                <div id="resultado-vacio">
                    <i class="fa-solid fa-ruler-combined"
                        style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <p style="color: var(--text-muted);">Introduce las dimensiones para ver el cálculo.</p>
                </div>

                <div id="resultado-lleno" style="display: none; width: 100%;">
                    <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">Volumen Total: <strong
                            id="res-volumen">0</strong> m³</div>
                    <div style="font-size: 1.1rem; margin-bottom: 1.5rem; color: var(--text-muted);">Material: <span
                            id="res-material-nombre">Ninguno</span></div>

                    <div
                        style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;">
                        <div style="font-size: 2.5rem; font-weight: bold; color: var(--primary-color);"
                            id="res-cantidad">0</div>
                        <div style="font-weight: 500;">Unidades (Aprox.)</div>
                    </div>

                    <div style="font-size: 1.3rem; font-weight: bold; margin-bottom: 1.5rem;">Presupuesto: <span
                            id="res-precio" style="color: var(--accent-color);">0,00</span> €</div>

                    <button class="btn btn-primary" onclick="addCalculadoAlCarrito()" style="width: 100%;"><i
                            class="fa-solid fa-cart-plus"></i> Añadir esta cantidad al carrito</button>
                </div>
            </div>
        </div>

        <!-- TAB 2: Proyectos Tipo -->
        <div id="tab-proyectos" style="display: none;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="color: var(--primary-dark);">Proyectos Ecológicos Listos para Comprar</h2>
                <p style="color: var(--text-muted);">Añade al carrito todos los materiales necesarios para tu proyecto
                    con un solo clic.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">

                <!-- Proyecto 1 -->
                <div
                    style="background: white; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); overflow: hidden; display: flex;">
                    <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=400&auto=format&fit=crop"
                        style="width: 200px; object-fit: cover;">
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-bottom: 0.5rem;">Tabique Interior Ecológico (3x2.5m)</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Construye un
                            tabique interior usando Bloques de Tierra Comprimida (BTC). Aislamiento acústico natural.
                        </p>

                        <div
                            style="background: var(--bg-light); padding: 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem;">
                            <strong>Incluye:</strong>
                            <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                                <li>250x Bloques BTC</li>
                                <li>2x Sacos Mortero Cal (25kg)</li>
                            </ul>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                            <span style="font-size: 1.5rem; font-weight: bold; color: var(--accent-color);">317,50
                                €</span>
                            <button class="btn btn-primary" onclick="addProyectoCarrito(1)"><i
                                    class="fa-solid fa-cart-arrow-down"></i> Añadir Kit</button>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 2 -->
                <div
                    style="background: white; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); overflow: hidden; display: flex;">
                    <img src="https://images.unsplash.com/photo-1621644781442-9fc7a08e16ea?w=400&auto=format&fit=crop"
                        style="width: 200px; object-fit: cover;">
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-bottom: 0.5rem;">Aislamiento Pared Habitación (12m²)</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Kit completo para
                            forrar una pared exterior por dentro y combatir el frío con Corcho Natural.</p>

                        <div
                            style="background: var(--bg-light); padding: 1rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem;">
                            <strong>Incluye:</strong>
                            <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                                <li>24x Paneles Corcho 1x0.5m</li>
                                <li>1x Saco Mortero Adhesivo</li>
                            </ul>
                        </div>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                            <span style="font-size: 1.5rem; font-weight: bold; color: var(--accent-color);">380,75
                                €</span>
                            <button class="btn btn-primary" onclick="addProyectoCarrito(2)"><i
                                    class="fa-solid fa-cart-arrow-down"></i> Añadir Kit</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
    // Lógica simple para cambiar de pestañas en la UI
    function switchTab(tab) {
        document.getElementById('tab-volumen').style.display = tab === 'volumen' ? 'grid' : 'none';
        document.getElementById('tab-proyectos').style.display = tab === 'proyectos' ? 'block' : 'none';

        document.getElementById('btn-tab-volumen').className = tab === 'volumen' ? 'btn btn-primary' : 'btn btn-outline';
        document.getElementById('btn-tab-proyectos').className = tab === 'proyectos' ? 'btn btn-primary' : 'btn btn-outline';
    }

    // Lógica de cálculo
    function calcularVolumen() {
        const selector = document.getElementById('calc-material');
        const opcion = selector.options[selector.selectedIndex];

        if (!opcion.value) {
            alert("Por favor, selecciona un material primero.");
            return;
        }

        const alto = parseFloat(document.getElementById('calc-alto').value) || 0;
        const ancho = parseFloat(document.getElementById('calc-ancho').value) || 0;
        const prof = parseFloat(document.getElementById('calc-prof').value) || 0;

        if (alto <= 0 || ancho <= 0 || prof <= 0) {
            alert("Por favor, introduce medidas válidas mayores a 0.");
            return;
        }

        const volumen = alto * ancho * prof;
        const rendimiento = parseFloat(opcion.getAttribute('data-rendimiento'));
        const precioUnitario = parseFloat(opcion.getAttribute('data-precio'));

        // Cálculo de unidades necesarias (redondeando hacia arriba porque no compras medio bloque)
        const unidadesNecesarias = Math.ceil(volumen * rendimiento);
        const precioTotal = unidadesNecesarias * precioUnitario;

        // UI Update
        document.getElementById('resultado-vacio').style.display = 'none';
        document.getElementById('resultado-lleno').style.display = 'block';

        document.getElementById('res-volumen').innerText = volumen.toFixed(3);
        document.getElementById('res-material-nombre').innerText = opcion.getAttribute('data-nombre');
        document.getElementById('res-cantidad').innerText = unidadesNecesarias;
        document.getElementById('res-precio').innerText = precioTotal.toFixed(2).replace('.', ',');
    }

    // Simuladores de Carrito (Para el proyecto real harías Fetch API a un script PHP)
    function addCalculadoAlCarrito() {
        alert("Material añadido al carrito exitosamente. (Este botón conectará con PHP vía AJAX)");
    }
    function addProyectoCarrito(id) {
        alert("Kit de proyecto añadido al carrito exitosamente. (Este botón conectará con PHP vía AJAX)");
    }
</script>

<?php include 'includes/footer.php'; ?>