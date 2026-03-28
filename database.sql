-- ============================================
-- BASE DE DATOS - VENTA DE CARROS PWA
-- ============================================
-- Script para crear la base de datos y tablas manualmente
-- Copia y pega esto en PhpMyAdmin > SQL

-- 1. Crear base de datos
CREATE DATABASE IF NOT EXISTS venta_carros;
USE venta_carros;

-- 2. Tabla de AUTOBUSES/AUTOS
CREATE TABLE IF NOT EXISTS autos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  precio INT NOT NULL,
  foto LONGTEXT,
  sincronizado BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Tabla de USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Insertar usuario DEMO
-- Contraseña: 1234 (hash bcrypt)
INSERT IGNORE INTO usuarios (id, usuario, password, email) 
VALUES (1, 'admin', '$2y$10$YFhCY2RVbrzJCKNvFg3WxOhUVYBnZ1v2rQhW0G3KqDc5XrYqMqKWy', 'admin@carros.com');

-- 5. Insertar AUTOS DE DEMO
INSERT IGNORE INTO autos (id, nombre, precio, sincronizado) VALUES
(1, 'Honda Civic 2023', 320000, 1),
(2, 'Mazda CX-5 2023', 450000, 1),
(3, 'Toyota Corolla 2024', 280000, 1),
(4, 'Nissan Sentra 2023', 290000, 1);

-- 6. Verificar datos
SELECT '=== AUTOS ===' as '';
SELECT * FROM autos;

SELECT '=== USUARIOS ===' as '';
SELECT * FROM usuarios;

-- ============================================
-- QUERYS ÚTILES PARA DESARROLLO
-- ============================================

-- Ver todos los autos
-- SELECT * FROM autos ORDER BY created_at DESC;

-- Ver autos sin sincronizar (offline)
-- SELECT * FROM autos WHERE sincronizado = 0;

-- Contar autos totales
-- SELECT COUNT(*) as total_autos FROM autos;

-- Ver autos más caros
-- SELECT * FROM autos ORDER BY precio DESC LIMIT 5;

-- Buscar auto por nombre
-- SELECT * FROM autos WHERE nombre LIKE '%Honda%';

-- Eliminar todos los autos
-- DELETE FROM autos;

-- Resetear ID auto-incremento
-- ALTER TABLE autos AUTO_INCREMENT = 1;

-- Eliminar tabla completa
-- DROP TABLE IF EXISTS autos;

-- Ver estructura de tabla
-- DESCRIBE autos;

-- Ver información de tabla
-- SHOW CREATE TABLE autos\G
