CREATE OR REPLACE DATABASE ecobric_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecobric_db;

-- 1. Tabla de roles (Para distinguir entre administradores y clientes)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- 2. Tabla de usuarios (Preparado para login y registro con verificación por email)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    token_verificacion VARCHAR(255) DEFAULT NULL,
    esta_verificado BOOLEAN DEFAULT FALSE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- 3. Tabla de categorías de productos (Ej: Aislantes, Ladrillos, Morteros)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);

-- 4. Tabla de proveedores (Para gestión de inventario interno)
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(150) NOT NULL,
    nombre_contacto VARCHAR(100),
    email VARCHAR(150),
    telefono VARCHAR(20)
);

-- 5. Tabla de productos (Aquí incluimos campos especiales para la "calculadora de volúmenes")
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    es_calculable_volumen BOOLEAN DEFAULT FALSE,
    rendimiento_por_m3 DECIMAL(10,2) DEFAULT NULL COMMENT 'Rendimiento o unidades por m3, usado en la calculadora de volumen',
    url_imagen VARCHAR(255) DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- 6. Tabla N:N - Productos y Proveedores (Un producto puede tener varios proveedores y viceversa)
CREATE TABLE producto_proveedor (
    producto_id INT NOT NULL,
    proveedor_id INT NOT NULL,
    precio_suministro DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (producto_id, proveedor_id),
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE CASCADE
);

-- 7. Tabla de movimientos de inventario (Tracking de compras y ventas para el stock)
CREATE TABLE movimientos_inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo_movimiento ENUM('ENTRADA', 'SALIDA') NOT NULL,
    cantidad INT NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notas VARCHAR(255),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- 8. Tabla de Pedidos/Ventas (Cabecera de las ventas a clientes)
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    monto_total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) DEFAULT 'Tarjeta',
    estado ENUM('PENDIENTE', 'PAGADO', 'ENVIADO', 'CANCELADO') DEFAULT 'PENDIENTE',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 9. Tabla N:N - Detalles del Pedido (Items de la venta, relación entre Pedidos y Productos)
CREATE TABLE detalles_pedido (
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (pedido_id, producto_id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
