# 🚀 Seeker API

Este proyecto es una API REST para gestionar parkings, construida con **Laravel 12**, **Docker**, **MySQL** y **Nginx**. Está lista para desarrollo local con un entorno dockerizado.

---

## ⚙️ Requisitos

- Docker + Docker Compose
- Git

> No necesitás instalar PHP, Composer ni MySQL: todo corre en contenedores.

---

## 📦 Instalación paso a paso

### 1. Clonar el proyecto

```bash
git clone git@github.com:alvaromirandamita/seeker.git
cd seeker
```

### 2. Levantar los servicios con Docker

```bash
docker compose up -d --build
```

Esto construye y levanta:
- PHP 8.3 (Laravel 12)
- MySQL 8
- Nginx (puerto 8000)
- Red dockerizada

---

### ⚡ Aclaración para pruebas rápidas

✅ El archivo `.env` ya está incluido dentro del proyecto Laravel (`parkings-api/`).  
**No hace falta ejecutar:**

```bash
cp .env.example .env
```

---

### 3. Ingresar al contenedor Laravel

```bash
docker exec -it parkings-app sh
```

> Esto te da acceso al entorno Laravel dentro del contenedor.

---

### 4. Dentro del contenedor de Laravel


#### 1. Instalar dependencias PHP

```bash
composer install
```

#### 2. Generar clave de aplicación

```bash
php artisan key:generate
```

#### 3. Migrar la base de datos y sembrar datos de prueba

```bash
php artisan migrate:fresh --seed
```

Esto creará las tablas e insertará 5 parkings de ejemplo en Buenos Aires.

---

## 📮 Endpoints disponibles

Una vez iniciada la API, podés probar:

| Método | Endpoint                        | Descripción                     |
|--------|----------------------------------|----------------------------------|
| POST   | /api/parkings                    | Crear nuevo parking              |
| GET    | /api/parkings/{id}               | Obtener por ID                   |
| GET    | /api/parkings/nearest?lat=..&lng=.. | Parking más cercano              |
| GET    | /api/parkings                    | Listar todos los parkings        |

> Base URL por defecto: [http://localhost:8000](http://localhost:8000)

---

## 📚 Documentación API con Swagger UI

La API incluye documentación interactiva generada con **Swagger UI**, que permite explorar y probar los endpoints directamente desde el navegador.

- **URL de la documentación:**  
  [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

- **Qué ofrece Swagger UI:**  
  - Visualización de todos los endpoints disponibles.  
  - Detalles de parámetros, request y responses.  
  - Posibilidad de probar los endpoints con datos reales desde la interfaz.  
  - Información sobre códigos de respuesta y modelos de datos.

> ⚠️ Asegurate de tener el contenedor corriendo y la documentación generada correctamente (por ejemplo, con `php artisan l5-swagger:generate`) para que Swagger UI funcione sin problemas.

---

## 📝 Log de parkings lejanos

Cuando se consulta por el parking más cercano (`/api/parkings/nearest`), si la distancia supera los 500 metros, la búsqueda se registra automáticamente en:

```bash
seeker/parkings-api/storage/logs/parkings.log
```


---


## 🧪 Correr tests con Pest

Dentro del contenedor de Laravel:

```bash
./vendor/bin/pest
```

---

## 📁 Estructura del proyecto

```
seeker/
├── docker/               # Configuración de Docker (nginx, php)
├── parkings-api/         # Código fuente Laravel
├── docker-compose.yml    # Servicios definidos
├── README.md             # Este archivo
```

---

## 🛑 Notas

- El `.gitignore` ya está configurado.
- La base de datos es persistente vía volumen Docker.
- Podés conectarte desde herramientas como DBeaver.

---

Desarrollado por [@alvaromirandamita](https://github.com/alvaromirandamita)