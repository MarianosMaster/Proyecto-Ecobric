USE ecobric_db;

-- 1. Insertar roles básicos
INSERT INTO roles (nombre) VALUES ('admin'), ('cliente');

-- 2. Insertar usuarios de prueba (Las contraseñas aquí son un hash de "password" para pruebas con password_hash en PHP)
INSERT INTO usuarios (rol_id, nombre, email, contrasena, esta_verificado) VALUES
(1, 'Admin Ecobric', 'admin@ecobric.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(2, 'Juan Pérez', 'juan@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- 3. Insertar categorías ecológicas
INSERT INTO categorias (nombre, descripcion) VALUES
('Aislantes Ecológicos', 'Materiales para aislamiento térmico y acústico a base de materiales reciclados o naturales.'),
('Ladrillos y Bloques', 'Bloques de construcción de bajo impacto ambiental como el Bloque de Tierra Comprimida (BTC).'),
('Morteros y Cementos', 'Morteros transpirables y ecológicos sin químicos nocivos.');

-- 4. Insertar proveedores de materiales
INSERT INTO proveedores (nombre_empresa, nombre_contacto, email, telefono) VALUES
('EcoMaterials SL', 'Carlos Ruiz', 'contacto@ecomaterials.com', '600123456'),
('BioBuild Supplies', 'Ana Gómez', 'ventas@biobuild.com', '600654321');

-- 5. Insertar productos (Presta atención a es_calculable_volumen y rendimiento_por_m3 para tu futura calculadora)
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3) VALUES
(1, 'Panel de Corcho Natural', 'Aislante de corcho expandido 1x0.5m', 15.50, 100, FALSE, NULL),
(2, 'Bloque de Tierra Comprimida (BTC)', 'Ladrillos crudos prensados sin cocción', 1.20, 5000, TRUE, 400.00), -- 400 bloques aproximados para llenar 1 m3 de pared
(3, 'Saco de Mortero de Cal Hidráulica (25kg)', 'Ideal para agarre y revoco transpirable', 8.75, 200, TRUE, 50.00); -- Rendimiento de sacos por m3

-- 6. Insertar relación Producto-Proveedor (N:N)
INSERT INTO producto_proveedor (producto_id, proveedor_id, precio_suministro) VALUES
(1, 1, 10.00),
(2, 2, 0.80),
(3, 1, 5.00);

-- 7. Insertar el historial de inventario inicial
INSERT INTO movimientos_inventario (producto_id, tipo_movimiento, cantidad, notas) VALUES
(1, 'ENTRADA', 100, 'Inventario inicial'),
(2, 'ENTRADA', 5000, 'Inventario inicial'),
(3, 'ENTRADA', 200, 'Inventario inicial');

-- 8. Insertar un pedido de prueba de compra por un cliente
INSERT INTO pedidos (usuario_id, monto_total, estado) VALUES
(2, 120.00, 'PAGADO');

-- 9. Insertar el detalle del pedido (N:N) - Juan comprando 100 bloques BTC
INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES
(1, 2, 100, 1.20);
