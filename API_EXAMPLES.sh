#!/bin/bash

# 🚀 EJEMPLOS DE USO DE LA API 
# Ejecuta estos comandos en la terminal o usa Postman

# Cambiar a la carpeta del proyecto
cd /xampp/htdocs/venta-carros-pwa

# ==================== LOGIN ====================
echo "🔐 LOGIN"
curl -X POST http://localhost/venta-carros-pwa/api.php/login \
  -H "Content-Type: application/json" \
  -d '{
    "usuario": "admin",
    "password": "1234"
  }'

echo -e "\n\n"

# ==================== OBTENER AUTOS ====================
echo "📋 OBTENER TODOS LOS AUTOS"
curl -X GET http://localhost/venta-carros-pwa/api.php/autos \
  -H "Content-Type: application/json"

echo -e "\n\n"

# ==================== AGREGAR AUTO ====================
echo "➕ AGREGAR NUEVO AUTO"
curl -X POST http://localhost/venta-carros-pwa/api.php/autos \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Ferrari F8 Tributo 2024",
    "precio": 2500000,
    "foto": null
  }'

echo -e "\n\n"

# ==================== ACTUALIZAR AUTO ====================
echo "✏️ ACTUALIZAR AUTO (reemplaza ID_AQUI)"
curl -X PUT http://localhost/venta-carros-pwa/api.php/autos/1 \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Honda Civic EX 2023",
    "precio": 350000,
    "foto": null
  }'

echo -e "\n\n"

# ==================== ELIMINAR AUTO ====================
echo "🗑️ ELIMINAR AUTO (reemplaza ID_AQUI)"
curl -X DELETE http://localhost/venta-carros-pwa/api.php/autos/2 \
  -H "Content-Type: application/json"

echo -e "\n\n"

# ==================== EJEMPLOS JAVASCRIPT ====================
echo "
📝 EJEMPLOS PARA USAR EN JAVASCRIPT/CONSOLE

// LOGIN
fetch('/venta-carros-pwa/api.php/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ usuario: 'admin', password: '1234' })
})
.then(r => r.json())
.then(d => console.log(d))

// OBTENER AUTOS
fetch('/venta-carros-pwa/api.php/autos')
  .then(r => r.json())
  .then(d => console.log(d))

// AGREGAR AUTO
fetch('/venta-carros-pwa/api.php/autos', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    nombre: 'Lamborghini Revuelto 2024',
    precio: 5000000,
    foto: null
  })
})
.then(r => r.json())
.then(d => console.log(d))

// ACTUALIZAR AUTO
fetch('/venta-carros-pwa/api.php/autos/3', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    nombre: 'Toyota Supra A90 2024',
    precio: 800000,
    foto: null
  })
})
.then(r => r.json())
.then(d => console.log(d))

// ELIMINAR AUTO
fetch('/venta-carros-pwa/api.php/autos/3', {
  method: 'DELETE',
  headers: { 'Content-Type': 'application/json' }
})
.then(r => r.json())
.then(d => console.log(d))
"
