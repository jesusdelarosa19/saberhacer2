<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

error_reporting(E_ALL);
ini_set('display_errors', 0);

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'venta_carros');

try {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    // Crear base de datos si no existe
    $sql_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if (!$conexion->query($sql_db)) {
        throw new Exception("Error creando base de datos: " . $conexion->error);
    }
    
    // Seleccionar la base de datos
    $conexion->select_db(DB_NAME);
    
    // Crear tabla de autos si no existe
    $sql_tabla = "CREATE TABLE IF NOT EXISTS autos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(255) NOT NULL,
        precio INT NOT NULL,
        foto LONGTEXT,
        sincronizado BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conexion->query($sql_tabla)) {
        throw new Exception("Error creando tabla: " . $conexion->error);
    }
    
    // Crear tabla de usuarios si no existe
    $sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conexion->query($sql_usuarios)) {
        throw new Exception("Error creando tabla usuarios: " . $conexion->error);
    }
    
    // Insertar usuario demo si no existe
    $hash_pass = password_hash('1234', PASSWORD_DEFAULT);
    $sql_check = "SELECT * FROM usuarios WHERE usuario = 'admin'";
    $result = $conexion->query($sql_check);
    
    if ($result->num_rows == 0) {
        $sql_insert = "INSERT INTO usuarios (usuario, password, email) VALUES ('admin', '$hash_pass', 'admin@carros.com')";
        $conexion->query($sql_insert);
    }
    
    // Insertar autos demo si la tabla está vacía
    $sql_count = "SELECT COUNT(*) as total FROM autos";
    $result = $conexion->query($sql_count);
    $row = $result->fetch_assoc();
    
    if ($row['total'] == 0) {
        $autos_demo = [
            ['Honda Civic 2023', 320000],
            ['Mazda CX-5 2023', 450000],
            ['Toyota Corolla 2024', 280000],
            ['Nissan Sentra 2023', 290000]
        ];
        
        foreach ($autos_demo as $auto) {
            $sql = "INSERT INTO autos (nombre, precio) VALUES ('{$auto[0]}', {$auto[1]})";
            $conexion->query($sql);
        }
    }
    
    $conexion->set_charset("utf8");
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>
