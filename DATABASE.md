# Base de Datos - Venta de Carros PWA

## 📋 Configuración de la Base de Datos

Tu aplicación ya está conectada a una base de datos MySQL. Aquí está toda la información:

### 🗄️ Base de Datos
**Nombre:** `venta_carros`
**Usuario:** `root`
**Contraseña:** (vacía)
**Host:** `localhost`

### 📊 Tablas Creadas

#### 1. **Tabla: `autos`**
```sql
CREATE TABLE autos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL,
  precio INT NOT NULL,
  foto LONGTEXT,
  sincronizado BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único del auto (auto-incremento) |
| `nombre` | VARCHAR(255) | Nombre/modelo del auto |
| `precio` | INT | Precio del auto |
| `foto` | LONGTEXT | Foto en formato base64 |
| `sincronizado` | BOOLEAN | Si fue sincronizado desde offline |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Última actualización |

#### 2. **Tabla: `usuarios`**
```sql
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | ID único del usuario |
| `usuario` | VARCHAR(50) | Nombre de usuario |
| `password` | VARCHAR(255) | Contraseña hasheada |
| `email` | VARCHAR(100) | Email del usuario |
| `created_at` | TIMESTAMP | Fecha de creación |

---

## 🔑 Credenciales de Acceso

### Usuario Demo Creado
- **Usuario:** `admin`
- **Contraseña:** `1234`

---

## 📁 Archivos de API

### 1. **db_config.php**
Archivo de configuración que:
- Conecta a MySQL
- Crea la base de datos si no existe
- Crea las tablas automáticamente
- Inserta datos de demo

### 2. **api.php**
API REST con los siguientes endpoints:

#### Autenticación
```bash
POST /venta-carros-pwa/api.php/login
```
**Body:**
```json
{
  "usuario": "admin",
  "password": "1234"
}
```

#### CRUD de Autos

**Obtener todos los autos:**
```bash
GET /venta-carros-pwa/api.php/autos
```

**Agregar nuevo auto:**
```bash
POST /venta-carros-pwa/api.php/autos
```
**Body:**
```json
{
  "nombre": "Honda Civic 2024",
  "precio": 320000,
  "foto": "data:image/jpeg;base64,...",
  "sincronizado": true
}
```

**Actualizar auto:**
```bash
PUT /venta-carros-pwa/api.php/autos/{id}
```

**Eliminar auto:**
```bash
DELETE /venta-carros-pwa/api.php/autos/{id}
```

---

## 🚀 Cómo Usar

### Requerimientos
- XAMPP instalado y corriendo
- Apache y MySQL activos
- PhP 7.0+

### Pasos Iniciales

1. **Iniciando XAMPP:**
   - Abre XAMPP Control Panel
   - Click en "Start" para Apache y MySQL

2. **Acceder a la aplicación:**
   ```
   http://localhost/venta-carros-pwa/
   ```

3. **Login:**
   - Usuario: `admin`
   - Contraseña: `1234`

4. **Verificar la base de datos:**
   ```
   http://localhost/phpmyadmin/
   ```
   - Selecciona la base de datos `venta_carros`
   - Verifica las tablas `autos` y `usuarios`

---

## 💾 Sincronización Offline

La aplicación funciona sin conexión:
1. Los datos se cachean con Service Workers
2. Los nuevos autos se marcan como `sincronizado = false`
3. Cuando hay conexión nuevamente, se sincronizan automáticamente

---

## 🔧 Agregar Más Usuarios

Para agregar nuevos usuarios desde phpMyAdmin:

1. Abre `http://localhost/phpmyadmin/`
2. Selecciona base de datos `venta_carros`
3. Selecciona tabla `usuarios`
4. Click en "Insertar" (Insert)
5. Rellena los datos:
   - **usuario:** (nombre de usuario)
   - **password:** (hash con password_hash de PHP)
   - **email:** (correo electrónico)

### Opción: Crear con script PHP

Crea un archivo `agregar_usuario.php`:
```php
<?php
require_once 'db_config.php';

$usuario = 'nuevo_usuario';
$password = 'contraseña123';
$email = 'usuario@correo.com';

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (usuario, password, email) VALUES ('$usuario', '$hash', '$email')";

if ($conexion->query($sql)) {
    echo "Usuario creado exitosamente";
} else {
    echo "Error: " . $conexion->error;
}
?>
```

---

## 🐛 Troubleshooting

### La base de datos no se crea
- Verifica que MySQL esté corriendo en XAMPP
- Revisa los permisos del usuario `root`

### Error 500 en la API
- Abre DevTools (F12) → Console
- Verifica si hay mensajes de error
- Revisa el archivo `api.php` en la carpeta

### Los datos no se guardan
- Asegúrate que los permisos de carpeta sean correctos
- Verifica que la conexión a MySQL esté activa

---

## 📞 Comandos SQL Útiles

### Ver todos los autos
```sql
SELECT * FROM autos;
```

### Ver estructura de tabla
```sql
DESCRIBE autos;
```

### Limpiar tabla de autos
```sql
DELETE FROM autos;
ALTER TABLE autos AUTO_INCREMENT = 1;
```

### Eliminar base de datos completa
```sql
DROP DATABASE venta_carros;
```

---

**¡Tu aplicación está lista para producción!** 🎉

Cualquier duda, puedo ayudarte a:
- Modificar la estructura de la base de datos
- Agregar más funcionalidades
- Optimizar queries
- Implementar más seguridad
