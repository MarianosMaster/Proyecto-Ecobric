-- db_data_extended.sql
USE ecobric_db;

SET FOREIGN_KEY_CHECKS = 0;
-- Limpiamos datos anteriores si los hubiera
DELETE FROM productos;
DELETE FROM categorias;
DELETE FROM usuarios;
DELETE FROM roles;
SET FOREIGN_KEY_CHECKS = 1;

-- Insertar Roles Básico
INSERT INTO roles (id, nombre) VALUES 
(1, 'Administrador'),
(2, 'Cliente');

-- Insertar Categorías Reales
INSERT INTO categorias (nombre, descripcion) VALUES
('Bloques de Tierra Comprimida (BTC)', 'Bloques ecológicos fabricados con tierra cruda prensada. Ideales para muros de carga y cerramientos con alta inercia térmica.'),
('Morteros y Revocos', 'Mezclas naturales a base de cal hidráulica, arcilla y áridos para unión de bloques y revestimientos.'),
('Aislantes Naturales', 'Materiales de aislamiento térmico y acústico 100% naturales como corcho, cáñamo y fibra de madera.'),
('Pinturas Ecológicas', 'Pinturas al silicato o a la cal, transpirables y sin compuestos orgánicos volátiles (COV).'),
('Maderas Certificadas', 'Maderas provenientes de bosques gestionados de forma sostenible con certificación FSC/PEFC.');

-- Obtener IDs de categorías para insertar productos
-- (Asumimos IDs secuenciales 1 a 5 si la tabla estaba vacía o reiniciada, usaremos subconsultas por seguridad)

-- BTC
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES
((SELECT id FROM categorias WHERE nombre LIKE '%BTC%' LIMIT 1), 'Bloque BTC Estándar (29x14x9 cm)', 'Bloque de tierra cruda estabilizada al 5% con cal. Alta resistencia a compresión y excelente inercia térmica.', 1.15, 5000, 1, 246, 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%BTC%' LIMIT 1), 'Bloque BTC Visto (29x14x9 cm)', 'Bloque de tierra cruda con acabado fino para dejar visto. No requiere revoco.', 1.45, 3000, 1, 246, 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%BTC%' LIMIT 1), 'Medio Bloque BTC (14x14x9 cm)', 'Pieza especial para remates y esquinas, evitando cortes en obra.', 0.85, 1000, 0, NULL, 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=500&auto=format&fit=crop');

-- Morteros
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES
((SELECT id FROM categorias WHERE nombre LIKE '%Morteros%' LIMIT 1), 'Mortero de Cal Hidráulica NHL 3.5 (25kg)', 'Saco de mortero base cal natural para agarre de bloques y muros transpirables.', 8.50, 500, 1, 15, 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Morteros%' LIMIT 1), 'Revoco Fino de Arcilla Blanca (20kg)', 'Terminación interior natural que regula la humedad ambiental. Acabado blanco mate.', 18.00, 300, 0, NULL, 'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Morteros%' LIMIT 1), 'Mortero Aislante de Corcho y Cal (15kg)', 'Mortero aligerado con triturado de corcho para corrección higrotérmica en rehabilitación.', 24.50, 200, 1, 8, 'https://images.unsplash.com/photo-1504307651254-35680f356f12?w=500&auto=format&fit=crop');

-- Aislantes Naturales
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES
((SELECT id FROM categorias WHERE nombre LIKE '%Aislantes%' LIMIT 1), 'Panel de Corcho Natural Expandido (100x50x4 cm)', 'El mejor aislamiento natural. Resistente al fuego, imputrescible y 100% ecológico (aglomerado con su propia resina).', 12.80, 800, 1, 50, 'https://images.unsplash.com/photo-1621644781442-9fc7a08e16ea?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Aislantes%' LIMIT 1), 'Rollo Aislante de Cáñamo (10m x 0.6m x 5cm)', 'Manta aislante flexible de fibras de cáñamo. Excelente aislamiento acústico para tabiquería seca.', 45.00, 150, 0, NULL, 'https://images.unsplash.com/photo-1520195156372-986162391629?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Aislantes%' LIMIT 1), 'Tablero de Fibra de Madera Rígido (120x60x6 cm)', 'Aislamiento exterior SATE natural. Gran capacidad calorífica para evitar recalentamiento en verano.', 14.50, 600, 1, 23.1, 'https://images.unsplash.com/photo-1531834685032-c34bf0d84c77?w=500&auto=format&fit=crop');

-- Pinturas
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES
((SELECT id FROM categorias WHERE nombre LIKE '%Pinturas%' LIMIT 1), 'Pintura de Silicato Blanca (15L)', 'Pintura mineral que petrifica con el soporte. Totalmente transpirable, anti-moho. Cubre hasta 90m2.', 65.00, 100, 0, NULL, 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Pinturas%' LIMIT 1), 'Pintura de Cal Artesanal (10L)', 'Acabado tradicional rústico, propiedades biocidas naturales.', 35.00, 120, 0, NULL, 'https://images.unsplash.com/photo-1558227031-6b8f15d9da63?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Pinturas%' LIMIT 1), 'Pigmento Natural Tierra de Siena (500g)', 'Pigmento mineral 100% natural para teñir pinturas al silicato y revocos.', 12.50, 400, 0, NULL, 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=500&auto=format&fit=crop');

-- Maderas
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, es_calculable_volumen, rendimiento_por_m3, url_imagen) VALUES
((SELECT id FROM categorias WHERE nombre LIKE '%Maderas%' LIMIT 1), 'Viga Laminada Abeto GL24h (6x12 cm x 3m)', 'Madera laminada encolada certificada PEFC. Alta estabilidad dimensional para estructuras.', 28.50, 200, 0, NULL, 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=500&auto=format&fit=crop'),
((SELECT id FROM categorias WHERE nombre LIKE '%Maderas%' LIMIT 1), 'Lamas Machihembradas Pino Flandes (2.4m x 10cm)', 'Paquete de 1.2 m2 para frisos decorativos interiores. Origen bosques nórdicos sostenibles.', 18.90, 300, 0, NULL, 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=500&auto=format&fit=crop');

