<?php
// Mockup rápido de carrito y funcionalidad EcoScore
include 'includes/header.php';
?>

<div class="page-header" style="background-color: var(--text-dark); padding: 4rem 0; color: white; text-align: center;">
    <div class="container">
        <h1 style="color: white; margin-bottom: 0.5rem;"><i class="fa-solid fa-shopping-cart"></i> Mi Carrito</h1>
        <p>Revisa tus materiales antes de proceder al pago.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">

            <!-- Lista de Materiales (Mockup) -->
            <div>
                <h3 style="margin-bottom: 1.5rem;">Productos en el Carrito (Prueba)</h3>

                <table
                    style="width: 100%; border-collapse: collapse; background: white; box-shadow: var(--shadow-sm); border-radius: var(--border-radius); overflow: hidden;">
                    <thead style="background-color: var(--primary-light); color: white;">
                        <tr>
                            <th style="padding: 1rem; text-align: left;">Producto</th>
                            <th style="padding: 1rem; text-align: center;">Cantidad</th>
                            <th style="padding: 1rem; text-align: right;">Precio Unit.</th>
                            <th style="padding: 1rem; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <img src="https://images.unsplash.com/photo-1621644781442-9fc7a08e16ea?w=100&auto=format&fit=crop"
                                    style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                                Bloque de Tierra Comprimida (BTC)
                            </td>
                            <td style="padding: 1rem; text-align: center;">100</td>
                            <td style="padding: 1rem; text-align: right;">1,20 €</td>
                            <td style="padding: 1rem; text-align: right; font-weight: bold;">120,00 €</td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=100&auto=format&fit=crop"
                                    style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover;">
                                Saco de Mortero Cal Hidráulica
                            </td>
                            <td style="padding: 1rem; text-align: center;">5</td>
                            <td style="padding: 1rem; text-align: right;">8,75 €</td>
                            <td style="padding: 1rem; text-align: right; font-weight: bold;">43,75 €</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Resumen y ECO SCORE (Novedad) -->
            <div>
                <div
                    style="background: white; padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                    <h3
                        style="margin-bottom: 1.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">
                        Resumen de Compra</h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="color: var(--text-muted);">Subtotal</span>
                        <span style="font-weight: bold;">163,75 €</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="color: var(--text-muted);">Envío (Provincia Madrid)</span>
                        <span style="font-weight: bold;">25,00 €</span>
                    </div>

                    <div
                        style="display: flex; justify-content: space-between; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--text-dark); font-size: 1.3rem; font-weight: bold; color: var(--primary-dark);">
                        <span>Total</span>
                        <span>188,75 €</span>
                    </div>

                    <a href="#" class="btn btn-primary"
                        style="width: 100%; margin-top: 2rem; text-align: center;">Finalizar Pedido</a>
                </div>

                <!-- WIDGET ECO-SCORE -->
                <div
                    style="background: linear-gradient(135deg, var(--primary-light), var(--primary-color)); padding: 2rem; border-radius: var(--border-radius); color: white; box-shadow: var(--shadow-md);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <i class="fa-solid fa-tree" style="font-size: 2.5rem;"></i>
                        <h3 style="color: white; margin: 0;">Tu Impacto Positivo</h3>
                    </div>
                    <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">Al elegir construir con nuestros materiales
                        ecológicos en lugar de materiales convencionales (cemento Portland / ladrillo cocido), estás
                        ayudando al planeta:</p>

                    <div
                        style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 1.5rem; text-align: center;">
                        <div style="font-size: 3rem; font-weight: bold; line-height: 1;">18.5</div>
                        <div style="font-size: 0.9rem; margin-top: 0.5rem; font-weight: 500;">Kg de CO2 Ahorrados</div>
                    </div>

                    <p style="text-align: center; font-size: 0.8rem; margin-top: 1rem; opacity: 0.8;">Cálculo estimado
                        basado en el ciclo de vida del producto.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>