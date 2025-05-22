![Prex AR Logo](https://raw.githubusercontent.com/andresmza/prex-challenge/main/prex.svg)

# API de GIFs - Prex Challenge

Este proyecto es una API RESTful para buscar y gestionar GIFs favoritos utilizando la API de Giphy. Permite a los usuarios autenticarse, buscar GIFs, ver detalles de GIFs específicos y guardar sus GIFs favoritos con alias personalizados.

## Características

- Autenticación de usuarios mediante Laravel Passport
- Integración con la API de Giphy
- Búsqueda de GIFs por términos
- Visualización de detalles de GIFs específicos
- Gestión de GIFs favoritos con alias personalizados
- Registro detallado de todas las acciones de los usuarios
- Documentación completa con diagramas de secuencia y casos de uso

## Nota importante  

- En el endpoint de guardar favoritos no se envía el `user_id` porque la API ya conoce el usuario autenticado y no es necesario este dato
- El campo `gif_id` se cambió de numérico a alfanumérico porque Giphy maneja IDs alfanuméricos


## Documentación gráfica del proyecto

- [Ver Documentación Gráfica](app/docs/README.md)

## Documentación para pruebas con Postman

En la documentación del proyecto se incluyen los archivos de environment y collection de Postman para facilitar las pruebas de la API:


- [Descargar Collection de Postman](app/docs/postman/PrexChallenge.postman_collection.json)
- [Descargar Environment Local](app/docs/postman/Prex%20Challenge%20(local).postman_environment.json)
- [Descargar Environment Producción](app/docs/postman/Prex%20Challenge%20(production).postman_environment.json)

## Despliegue en producción

El sistema se encuentra desplegado y disponible en la siguiente URL:

- [https://prex.andresmza.com.ar](https://prex.andresmza.com.ar)

## Credenciales de prueba

Puedes utilizar las siguientes credenciales para probar el sistema:

```
Usuario: prex_challenge@prexcard.com.ar
Contraseña: Mendoza2025
```

# Instalación local

## Requisitos

- Docker y Docker Compose
- Git
- API Key de Giphy


## Configuración inicial

### 1. Clonar el repositorio

```bash
git clone https://github.com/andresmza/prex-challenge.git
cd prex-challenge
```

### 2. Configurar el archivo .env

```bash
cp .env.example .env
```

Edita el archivo `.env` si necesitas personalizar alguna configuración.

### 3. Configurar credenciales de base de datos y API key de Giphy

Edita el archivo `.env` y configura las siguientes variables:

```
DB_HOST=prex_challenge_db
DB_PORT=3306
DB_DATABASE=prex_challenge
DB_USERNAME=root
DB_PASSWORD=password

GIPHY_API_KEY=tu_api_key_de_giphy
```

Puedes obtener una API key de Giphy registrándote en [https://developers.giphy.com/](https://developers.giphy.com/)

### 4. Iniciar los contenedores Docker

```bash
docker-compose up -d
```

Este comando construirá y levantará los contenedores necesarios para el proyecto.

### 5. Acceder al contenedor de la aplicación

```bash
docker exec -it prex_challenge_app bash
```

> **Nota importante**: Los siguientes comandos deben ejecutarse **dentro del contenedor** después de acceder a él con el comando anterior.

### 6. Instalar dependencias de Composer

```bash
composer install
```

### 7. Generar clave de aplicación

```bash
php artisan key:generate
```

### 8. Ejecutar migraciones

```bash
php artisan migrate
```

### 9. Ejecutar seeders

```bash
php artisan db:seed
```

### 10. Instalar Passport

```bash
php artisan passport:install
```

### 11. Dar permisos a storage y bootstrap/cache

```bash
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

### 12. Crear enlace simbólico para el almacenamiento

```bash
php artisan storage:link
```

> Para salir del contenedor, simplemente escribe `exit`.

## Acceso a la aplicación

Una vez completados los pasos anteriores, puedes acceder a la aplicación en:

- http://localhost


## Ejecutar pruebas

El proyecto está configurado para ejecutar pruebas unitarias y de integración.

### Ejecutar pruebas con el entorno de testing

Dentro del contenedor:
```bash
php artisan test
```

## Comandos útiles

### Ver logs de la aplicación

```bash
docker logs prex_challenge_app
```

### Detener los contenedores

```bash
docker compose down
```

### Detener y eliminar volúmenes (borra la base de datos)

```bash
docker compose down -v
```

