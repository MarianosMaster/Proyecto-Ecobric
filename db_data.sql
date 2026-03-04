-- ---------------------------------------------------------
-- 0. Insertar Roles
-- ---------------------------------------------------------
INSERT IGNORE INTO roles (id, nombre) VALUES
(1, 'Admin'),
(2, 'Cliente');

-- ---------------------------------------------------------
-- 1. Insertar Proveedores (Sin duplicados)
-- ---------------------------------------------------------
INSERT INTO proveedores (nombre_empresa, nombre_contacto, email, telefono) VALUES
('Black+Decker', 'Soporte EU', 'soporte@blackanddecker.eu', '+34911223344'),
('FirstGreen', 'Ventas FirstGreen', 'ventas@firstgreen.es', '+34911225566'),
('Graphenstone', 'Departamento Ventas Graphenstone', 'ventas@graphenstone.com', '+34911223355'),
('KEIM', 'Ventas KEIM Iberia', 'info@keim.com', '+34911224455'),
('Oropal', 'Sales Oropal', 'ventas@oropal.es', '+34911225577'),
('Finsa', 'Departamento Ventas Finsa', 'ventas@finsa.com', '+34911226688');

-- ---------------------------------------------------------
-- 2. Insertar Categorías Unificadas
-- ---------------------------------------------------------
INSERT INTO categorias (nombre, descripcion) VALUES
('Herramientas', 'Herramientas de bricolaje y eléctricas'),
('Merchandising Sostenible', 'Productos ecológicos y sostenibles'),
('Pinturas Naturales', 'Pinturas ecológicas y revestimientos naturales'),
('Maderas y Tableros', 'Maderas certificadas, tableros y soluciones para mobiliario');

-- ---------------------------------------------------------
-- 3. Insertar Productos (+10% beneficio mantenido)
-- ---------------------------------------------------------
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES

-- Black+Decker
((SELECT id FROM categorias WHERE nombre='Herramientas'), 'Taladro atornillador 12V REVDD12C-QW', 'Taladro atornillador compacto con batería 12V de alta eficiencia', ROUND(52.37 * 1.10, 2), 20, FALSE, NULL, 'https://cdn.misterworker.com/712313-large_default/reviva-12v-cordless-drill-driver-with-integrated-15ah-battery-10-ma-revdd12c-qw.jpg'),
((SELECT id FROM categorias WHERE nombre='Herramientas'), 'Taladro percutor 12V REVHD12C-QW', 'Taladro percutor inalámbrico 12V, baterías y cargador incluidos', ROUND(44.59 * 1.10, 2), 20, FALSE, NULL, 'https://cdn.misterworker.com/712330-large_default/reviva-12v-cordless-hammer-drill-with-integrated-15ah-battery-revhd12c-qw.jpg'),
((SELECT id FROM categorias WHERE nombre='Herramientas'), 'Sierra de calar 12V REVJ12C-QW', 'Sierra de calar inalámbrica 12V REVIVA con batería integrada', ROUND(27.50 * 1.10, 2), 15, FALSE, NULL, 'https://cdn.misterworker.com/712347-large_default/reviva-12v-cordless-jigsaw-with-integrated-15ah-battery-revj12c-qw.jpg'),

-- FirstGreen
((SELECT id FROM categorias WHERE nombre='Merchandising Sostenible'), 'Radio de emergencia solar RescueWave', 'Radio de emergencia solar con linterna LED y powerbank sostenible', ROUND(24.31 * 1.10, 2), 50, FALSE, NULL, 'https://m.media-amazon.com/images/I/71oGfB+4FQL._AC_SL1500_.jpg'),

-- Graphenstone
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Graphenstone Ecosphere Premium 15L', 'Pintura interior transpirable a base de cal con grafeno', ROUND(94.99 * 1.10, 2), 40, TRUE, 2.50, 'https://www.bioconstruccion.eco/1000-large_default/graphenstone-ecosphere-premium-pintura-natural-con-grafeno-interior.jpg'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Graphenstone Biosphere Premium 15L', 'Pintura exterior ecológica en base cal con grafeno', ROUND(102.85 * 1.10, 2), 35, TRUE, 2.00, 'https://stockpinturas.com/cdn/shop/products/biospher-premium_1400x.jpg?v=1602148100'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Graphenstone AmbientPro+ Premium 15L', 'Pintura fotocatalítica natural con grafeno', ROUND(111.65 * 1.10, 2), 30, TRUE, 2.50, 'https://stockpinturas.com/cdn/shop/products/ambientpro-premium_1400x.jpg?v=1602148101'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Graphenstone GrafClean Premium 15L', 'Pintura ecológica premium con grafeno', ROUND(189.17 * 1.10, 2), 10, TRUE, 1.80, 'https://stockpinturas.com/cdn/shop/files/grafclean-premium.jpg?v=1697223680'),

-- KEIM (Incluidas todas las variaciones de tus bloques)
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'KEIM Soldalit-Grob 5kg', 'Sol-silicato para base y fondo de fachadas', ROUND(74.78 * 1.10, 2), 20, TRUE, 4.00, 'https://tiendabioconstruccion.com/wp-content/uploads/2021/05/soldalit-grob-5kg.jpg'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'KEIM Soldalit-Grob', 'Pintura sol-silicato de fondo para fachadas', ROUND(16.50 * 1.10, 2), 20, TRUE, 4.00, 'https://tiendabioconstruccion.com/wp-content/uploads/2021/05/soldalit-grob.jpg'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'KEIM Soldalit-Fixativ', 'Imprimación/diluyente sol-silicato KEIM', ROUND(15.74 * 1.10, 2), 50, TRUE, 5.00, 'https://tiendabioconstruccion.com/wp-content/uploads/2021/05/soldalit-fixativ.jpg'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'KEIM Soldalit-Coolit 2.5kg', 'Pintura hidrófuga sol-silicato KEIM', ROUND(53.05 * 1.10, 2), 25, TRUE, 3.50, 'https://pinturascolorsan.com/12390-large_default/keim-soldalit-coolit.jpg'),

-- Oropal
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Oropal Orokril 156 15L', 'Pintura monocolor mate Oropal', ROUND(59.95 * 1.10, 2), 40, TRUE, 2.00, 'https://chafiras.com/26430-large_default/orokril-156-blanco.jpg'),
((SELECT id FROM categorias WHERE nombre='Pinturas Naturales'), 'Oropal Esmalte Oroxite 750ml', 'Esmalte Oropal base agua 750ml', ROUND(19.24 * 1.10, 2), 100, TRUE, 8.00, 'https://chafiras.com/49527-large_default/esmalte-oroxite-blanco-brillo.jpg'),

-- Finsa
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan EZ 2440x1220x19mm', 'Tablero técnico sostenible SuperPan EZ', ROUND(30.14 * 1.10, 2), 60, TRUE, 12.00, 'https://www.esteba.com/media/catalog/product/cache/1/image/1000x/040ec09b1e35df139433887a97daa66f/S/u/Superpan_Aglomerado.jpg'),
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan 19mm 2440x1220', 'Tablero melamina blanca SuperPan 19mm', ROUND(46.14 * 1.10, 2), 50, TRUE, 12.00, 'https://brico-maderas.com/wp-content/uploads/2020/05/tablero-melamina-blanco-superpan-finsa.jpg'),
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan 30mm 2850x2100', 'Tablero melamina blanca SuperPan 30mm', ROUND(134.66 * 1.10, 2), 30, TRUE, 8.00, 'https://brico-maderas.com/wp-content/uploads/2020/05/tablero-melamina-blanco-superpan-finsa.jpg'),
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan Haya Natural 2440x1220x10mm', 'Tablero melamina Haya Natural 10mm', ROUND(55.26 * 1.10, 2), 40, TRUE, 14.00, 'https://brico-maderas.com/wp-content/uploads/2020/05/tablero-melamina-haya-superpan-finsa.jpg'),
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan Haya Natural 2440x1220x16mm', 'Tablero melamina Haya Natural 16mm', ROUND(60.70 * 1.10, 2), 35, TRUE, 13.00, 'https://brico-maderas.com/wp-content/uploads/2020/05/tablero-melamina-haya-superpan-finsa.jpg'),
((SELECT id FROM categorias WHERE nombre='Maderas y Tableros'), 'Finsa SuperPan Haya Natural 2440x1220x30mm', 'Tablero melamina Haya Natural 30mm', ROUND(91.38 * 1.10, 2), 30, TRUE, 8.00, 'https://brico-maderas.com/wp-content/uploads/2020/05/tablero-melamina-haya-superpan-finsa.jpg');

-- ---------------------------------------------------------
-- 4. Relacionar TODOS los Productos con Proveedores
-- ---------------------------------------------------------
-- He ajustado las reglas de coincidencia para que abarquen todos los productos de tu lista sin dejar ninguno suelto.
INSERT INTO producto_proveedor (producto_id, proveedor_id, precio_suministro)
SELECT p.id, pr.id, ROUND(p.precio * 0.80, 2)
FROM productos p
JOIN proveedores pr ON
  (pr.nombre_empresa='Black+Decker' AND (p.nombre LIKE '%Taladro%' OR p.nombre LIKE '%Sierra%'))
  OR (pr.nombre_empresa='FirstGreen' AND p.nombre LIKE '%Radio de emergencia%')
  OR (pr.nombre_empresa='Graphenstone' AND p.nombre LIKE 'Graphenstone%')
  OR (pr.nombre_empresa='KEIM' AND p.nombre LIKE 'KEIM%')
  OR (pr.nombre_empresa='Oropal' AND p.nombre LIKE 'Oropal%')
  OR (pr.nombre_empresa='Finsa' AND p.nombre LIKE 'Finsa%');