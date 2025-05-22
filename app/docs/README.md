# Documentación del Proyecto

Este documento contiene todos los diagramas, gráficos y archivos de documentación del proyecto organizados por categorías.

## Archivos de Postman

Para facilitar las pruebas de la API, se proporcionan los siguientes archivos de Postman:

- **Collection de Postman**: Contiene todas las peticiones necesarias para probar los endpoints de la API.
  - [Descargar Collection](postman/PrexChallenge.postman_collection.json)

- **Environment de Postman**: Contiene las variables de entorno necesarias para las pruebas.
  - [Environment Local](postman/Prex%20Challenge%20(local).postman_environment.json)
  - [Environment Producción](postman/Prex%20Challenge%20(production).postman_environment.json)

### Instrucciones de uso

1. Descarga los archivos de Collection y Environment
2. Importa ambos archivos en Postman
3. Selecciona el environment "Prex Challenge" en Postman
4. Ejecuta primero el endpoint de login para obtener el token de autenticación
5. El resto de endpoints usarán automáticamente el token obtenido

## Diagrama de Entidad-Relación

Este diagrama muestra la estructura de la base de datos y las relaciones entre entidades.

![Diagrama de Entidad-Relación](diagrams/entity_relationship/er_diagram.png)

## Diagramas de Secuencia

Estos diagramas muestran la interacción entre componentes del sistema para diferentes operaciones.

### Autenticación

![Diagrama de Secuencia de Login](diagrams/sequence/login_sequence.png)

### Búsqueda de GIFs

![Diagrama de Secuencia de Búsqueda de GIFs](diagrams/sequence/search_gifs_sequence.png)

### Obtener GIF por ID

![Diagrama de Secuencia para Obtener GIF por ID](diagrams/sequence/get_gif_by_id_sequence.png)

### Guardar GIF Favorito

![Diagrama de Secuencia para Guardar Favorito](diagrams/sequence/store_favorite_sequence.png)

## Diagramas de Casos de Uso

Estos diagramas ilustran las funcionalidades del sistema desde la perspectiva del usuario.

### Sistema Completo

![Caso de Uso del Sistema Completo](diagrams/use_cases/UC0_complete_system_use_case.png)

### Autenticación

![Caso de Uso de Autenticación](diagrams/use_cases/UC1_authentication_use_case.png)

### Búsqueda de GIFs

![Caso de Uso de Búsqueda de GIFs](diagrams/use_cases/UC2_search_gifs_use_case.png)

### Ver Detalles de GIF

![Caso de Uso para Ver Detalles de GIF](diagrams/use_cases/UC3_view_gif_details_use_case.png)

### Guardar GIF Favorito

![Caso de Uso para Guardar Favorito](diagrams/use_cases/UC4_save_favorite_use_case.png)
