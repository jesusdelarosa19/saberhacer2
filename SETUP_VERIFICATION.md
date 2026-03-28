# ✅ VERIFICACIÓN Y SETUP DE LA BASE DE DATOS

## 🚀 Pasos Rápidos para Empezar

### 1️⃣ Inicia XAMPP
- Abre **XAMPP Control Panel**
- Click en **Start** para Apache y MySQL

### 2️⃣ Verifica que MySQL esté corriendo
- En XAMPP, MySQL debe mostrar status **"Running"** (línea verde)
- Si no, click en Start

### 3️⃣ Accede a tu PWA
- Abre en el navegador:
  ```
  http://localhost/venta-carros-pwa/
  ```

### 4️⃣ La base de datos se crea automáticamente
- Cuando cargas la app por primera vez:
  - ✅ Se crea la BD `venta_carros`
  - ✅ Se crean las tablas `autos` y `usuarios`
  - ✅ Se inserta usuario demo "admin" / "1234"
  - ✅ Se insertan 4 autos de ejemplo

## 🔍 Verificar que Funciona

### Opción 1: PhpMyAdmin (Visual)
```
http://localhost/phpmyadmin/
```
1. Haz click en **Bases de datos**
2. Deberías ver **venta_carros**
3. Abrela y verifica que tenga:
   - Tabla `autos` con 4 registros
   - Tabla `usuarios` con el usuario "admin"

### Opción 2: Desde la Consola del Navegador
1. Abre la app: `http://localhost/venta-carros-pwa/`
2. Presiona `F12` para abrir DevTools
3. Ve a la pestaña **Console**
4. Copia y pega esto:

```javascript
// Verificar que la API está conectada
fetch('/venta-carros-pwa/api.php/autos')
  .then(r => r.json())
  .then(data => {
    console.log('✅ API Conectada');
    console.log('Autos en BD:', data.autos.length);
    console.log(data);
  })
  .catch(e => console.log('❌ Error:', e))
```

### Opción 3: Login Test
1. En la app, inicia sesión con:
   - Usuario: `admin`
   - Contraseña: `1234`
2. Si funciona, la BD está operativa ✅

## 📝 Información Guardada en BD

Después de usar la app:

### Tabla `autos` contiene:
- ✅ ID (auto-incremento)
- ✅ Nombre del auto
- ✅ Precio
- ✅ Foto (si tomaste una)
- ✅ Fecha de creación

### Tabla `usuarios` contiene:
- ✅ Usuario (admin)
- ✅ Contraseña (hasheada con bcrypt)
- ✅ Email

## 🔐 Seguridad Implementada

✅ **Contraseñas:** Hasheadas con `password_hash()` (bcrypt)
✅ **SQL Injection:** Proteción con `real_escape_string()`
✅ **CORS:** Headers configurados para acceso seguro
✅ **Offline First:** LocalStorage como backup

## ⚙️ Configuración Técnica

### Base de Datos
- **Host:** localhost
- **Usuario:** root
- **Password:** (vacío - XAMPP default)
- **Puerto:** 3306 (default)
- **Nombre:** venta_carros

### Archivos de Backend
- `db_config.php` - Conexión y setup automático
- `api.php` - Endpoints REST JSON

### Archivos de Frontend
- `index.html` - App actualizada con API real
- `manifest.json` - Configuración PWA
- `sw.js` - Service Worker

## 🆘 Solucionar Problemas

### ❌ "Cannot connect to database"
```
👀 Verificar:
1. MySQL está running en XAMPP?
2. Usuario root existe?
3. Puerto 3306 activo?

✅ Solución:
- Reinicia XAMPP
- En XAMPP Control Panel, click "Restart" en MySQL
```

### ❌ "API not found" (Error 404)
```
📍 Verificar:
1. La URL es: /venta-carros-pwa/api.php
2. Apache está running?
3. Los archivos existen en la carpeta?

✅ Solución:
- Verifica: C:\xampp\htdocs\venta-carros-pwa\api.php existe?
- Recarga la página
```

### ❌ "Login fallls pero debería funcionar"
```
✅ Recuerda:
- Usuario: admin (minúsculas)
- Contraseña: 1234
- Si falla, hay fallback a datos locales
```

### ❌ "Los autos no se guardan en la BD"
```
📍 Verificar:
1. Estás online? (badge en header)
2. Chequea la consola (F12 > Console)
3. ¿Hay mensaje de error?

✅ Verificar manualmente:
- Abre PhpMyAdmin
- SELECT * FROM autos;
- Deberías ver los nuevos registros
```

## 📊 Ver Datos Guardados

### Opción 1: PhpMyAdmin
```
http://localhost/phpmyadmin/
- Selecciona venta_carros
- Click en "autos"
- Ves todos los registros
```

### Opción 2: Terminal/PowerShell
```powershell
# En Windows con XAMPP instalado:
mysql -u root venta_carros -e "SELECT * FROM autos;"
mysql -u root venta_carros -e "SELECT * FROM usuarios;"
```

### Opción 3: Consola JavaScript
```javascript
// Ver los autos guardados en BD
app.autos.forEach((auto, i) => {
  console.log(`${i+1}. ${auto.nombre} - $${auto.precio}`);
})
```

## 🎉 ¡Todo Listo!

Tu PWA ahora tiene:
- ✅ Base de datos MySQL real
- ✅ API REST en PHP
- ✅ Frontend conectado a la BD
- ✅ Sincronización automática de datos
- ✅ Seguridad en credenciales
- ✅ Funciona offline con sync

**Próximos pasos opcionales:**
1. Agregar más usuarios
2. Validaciones más estrictas
3. Agregar más campos a autos (marca, año, combustible, etc)
4. Implementar búsqueda y filtros
5. Subir fotos a servidor en lugar de base64

¿Necesitas algo más? 👨‍💻
