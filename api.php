<?php
require_once 'db_config.php';

// Obtener el método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = str_replace('/venta-carros-pwa/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$parametros = explode('/', trim($ruta, '/'));

try {
    // RUTAS DE AUTENTICACIÓN
    if ($parametros[0] === 'api' && $parametros[1] === 'login' && $metodo === 'POST') {
        $datos = json_decode(file_get_contents('php://input'), true);
        $usuario = $conexion->real_escape_string($datos['usuario'] ?? '');
        $password = $datos['password'] ?? '';
        
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $result = $conexion->query($sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Login exitoso',
                    'usuario' => $user['usuario'],
                    'id' => $user['id']
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['exito' => false, 'error' => 'Contraseña incorrecta']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['exito' => false, 'error' => 'Usuario no encontrado']);
        }
        exit;
    }
    
    // REGISTRO DE NUEVO USUARIO
    if ($parametros[0] === 'api' && $parametros[1] === 'registro' && $metodo === 'POST') {
        $datos = json_decode(file_get_contents('php://input'), true);
        
        $usuario = $conexion->real_escape_string($datos['usuario'] ?? '');
        $email = $conexion->real_escape_string($datos['email'] ?? '');
        $password = $datos['password'] ?? '';
        
        // Validaciones
        if (empty($usuario) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['exito' => false, 'error' => 'Todos los campos son requeridos']);
            exit;
        }
        
        if (strlen($usuario) < 3) {
            http_response_code(400);
            echo json_encode(['exito' => false, 'error' => 'El usuario debe tener mínimo 3 caracteres']);
            exit;
        }
        
        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode(['exito' => false, 'error' => 'La contraseña debe tener mínimo 6 caracteres']);
            exit;
        }
        
        // Verificar si el usuario ya existe
        $sql_check = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $result = $conexion->query($sql_check);
        
        if ($result->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['exito' => false, 'error' => 'El usuario ya existe']);
            exit;
        }
        
        // Verificar si el email ya está registrado
        $sql_check_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $result_email = $conexion->query($sql_check_email);
        
        if ($result_email->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['exito' => false, 'error' => 'El email ya está registrado']);
            exit;
        }
        
        // Hashear contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (usuario, password, email) VALUES ('$usuario', '$hash', '$email')";
        
        if ($conexion->query($sql)) {
            echo json_encode([
                'exito' => true,
                'mensaje' => 'Cuenta creada exitosamente',
                'usuario' => $usuario,
                'id' => $conexion->insert_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['exito' => false, 'error' => $conexion->error]);
        }
        exit;
    }
    
    // RUTAS DE AUTOS
    if ($parametros[0] === 'api' && $parametros[1] === 'autos') {
        
        // GET - Obtener todos los autos
        if ($metodo === 'GET') {
            $sql = "SELECT * FROM autos ORDER BY created_at DESC";
            $result = $conexion->query($sql);
            $autos = [];
            
            while ($row = $result->fetch_assoc()) {
                $autos[] = $row;
            }
            
            echo json_encode([
                'exito' => true,
                'autos' => $autos,
                'total' => count($autos)
            ]);
            exit;
        }
        
        // POST - Agregar nuevo auto
        if ($metodo === 'POST') {
            $datos = json_decode(file_get_contents('php://input'), true);
            
            $nombre = $conexion->real_escape_string($datos['nombre'] ?? '');
            $precio = intval($datos['precio'] ?? 0);
            $foto = $conexion->real_escape_string($datos['foto'] ?? '');
            
            if (empty($nombre) || $precio <= 0) {
                http_response_code(400);
                echo json_encode(['exito' => false, 'error' => 'Datos inválidos']);
                exit;
            }
            
            $sql = "INSERT INTO autos (nombre, precio, foto) VALUES ('$nombre', $precio, '$foto')";
            
            if ($conexion->query($sql)) {
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Auto agregado exitosamente',
                    'id' => $conexion->insert_id,
                    'auto' => [
                        'id' => $conexion->insert_id,
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'foto' => $foto,
                        'sincronizado' => 1
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['exito' => false, 'error' => $conexion->error]);
            }
            exit;
        }
        
        // PUT - Actualizar auto
        if ($metodo === 'PUT') {
            $datos = json_decode(file_get_contents('php://input'), true);
            $id = intval($parametros[2] ?? 0);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['exito' => false, 'error' => 'ID inválido']);
                exit;
            }
            
            $nombre = $conexion->real_escape_string($datos['nombre'] ?? '');
            $precio = intval($datos['precio'] ?? 0);
            $foto = $conexion->real_escape_string($datos['foto'] ?? '');
            
            $sql = "UPDATE autos SET nombre = '$nombre', precio = $precio, foto = '$foto' WHERE id = $id";
            
            if ($conexion->query($sql)) {
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Auto actualizado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['exito' => false, 'error' => $conexion->error]);
            }
            exit;
        }
        
        // DELETE - Eliminar auto
        if ($metodo === 'DELETE') {
            $id = intval($parametros[2] ?? 0);
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['exito' => false, 'error' => 'ID inválido']);
                exit;
            }
            
            $sql = "DELETE FROM autos WHERE id = $id";
            
            if ($conexion->query($sql)) {
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Auto eliminado exitosamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['exito' => false, 'error' => $conexion->error]);
            }
            exit;
        }
    }
    
    // Ruta no encontrada
    http_response_code(404);
    echo json_encode(['exito' => false, 'error' => 'Ruta no encontrada']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['exito' => false, 'error' => $e->getMessage()]);
}
?>
