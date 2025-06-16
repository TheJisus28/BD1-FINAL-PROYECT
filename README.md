# 🏥 Sistema de Gestión de Inventario Farmacéutico

Este es un sistema web básico para la gestión de inventario de productos farmacéuticos, desarrollado en PHP con MySQL (o un gestor de base de datos compatible) y una interfaz de usuario simple con Tailwind CSS.

---

## 📋 Tabla de Contenidos

1.  [Acerca del Proyecto](#-acerca-del-proyecto)
2.  [Características](#-características)
3.  [Requisitos](#-requisitos)
4.  [Configuración del Proyecto](#-configuración-del-proyecto)
    - [Clonar el Repositorio](#clonar-el-repositorio)
    - [Configuración de la Base de Datos](#configuración-de-la-base-de-datos)
    - [Configuración de PHP](#configuración-de-php)
5.  [Uso de la Aplicación](#-uso-de-la-aplicación)
6.  [Estructura del Proyecto](#-estructura-del-proyecto)
7.  [Tecnologías Utilizadas](#-tecnologías-utilizadas)
8.  [Integrantes](#-integrantes)
9.  [Licencia](#-licencia)

---

## 💡 Acerca del Proyecto

Este proyecto fue desarrollado como parte de un trabajo universitario para la Universidad de Córdoba. Su objetivo principal es proporcionar una herramienta sencilla para la administración de productos en un inventario farmacéutico, permitiendo el registro, consulta, edición y "soft delete" de productos, así como el seguimiento de movimientos de inventario y la generación de reportes básicos.

---

## ✨ Características

- **Gestión de Productos (CRUD):**
  - Agregar nuevos productos al inventario.
  - Visualizar la lista de productos activos.
  - Editar la información de productos existentes.
  - "Soft Delete" de productos: Los productos no se eliminan físicamente, sino que se marcan como inactivos, manteniendo la integridad de los datos históricos (ej. movimientos).
- **Gestión de Movimientos de Inventario:**
  - Registro automático de movimientos de "Entrada" al crear productos.
  - Registro automático de movimientos de "Entrada" o "Salida" al actualizar la cantidad de stock de un producto.
  - Registro automático de "Salida por Descontinuación" al marcar un producto como inactivo.
- **Módulo de Reportes:**
  - Reporte de Stock Actual de Productos activos.
  - Reporte de Movimientos de Inventario por Rango de Fechas.
  - Reporte de Productos Próximos a Vencer.
  - Reporte de Productos Inactivos/Descontinuados (para auditoría o restauración).
  - Reporte de Movimientos de un Producto Específico.
- **Base de Datos:** Estructura optimizada para la gestión de inventario, usuarios, movimientos y reportes.

---

## 🛠️ Requisitos

Antes de comenzar, asegúrate de tener instalado lo siguiente:

- **Servidor Web:** Apache, Nginx o el servidor web incorporado de PHP.
- **PHP:** Versión 7.4 o superior (idealmente PHP 8.x).
- **Extensión PDO para MySQL:** Habilitada en tu configuración de PHP.
- **Sistema de Gestión de Bases de Datos:** MySQL o MariaDB.

---

## ⚙️ Configuración del Proyecto

Sigue estos pasos para poner en marcha el proyecto en tu máquina local.

### Clonar el Repositorio

Primero, clona el repositorio a tu máquina local:

```bash
git clone [https://github.com/TheJisus28/BD1-FINAL-PROYECT.git](https://github.com/TheJisus28/BD1-FINAL-PROYECT.git)
cd BD1-FINAL-PROYECT
```

### Configuración de la Base de Datos

1.  **Crea la base de datos:**
    Abre tu cliente MySQL (phpMyAdmin, MySQL Workbench, DBeaver, o línea de comandos) y ejecuta el script SQL `database/init.sql` para crear la base de datos y las tablas necesarias.

    ```sql
    -- Contenido de database/init.sql
    CREATE DATABASE IF NOT EXISTS InventarioFarmaceutico;
    USE InventarioFarmaceutico;

    -- ... (resto de tus CREATE TABLE statements) ...
    ```

    Asegúrate de que la tabla `Productos` tenga la columna `Activo BOOLEAN DEFAULT TRUE;`.

2.  **Configura la conexión a la base de datos:**
    Abre el archivo `src/config/db.php` y actualiza las credenciales de la base de datos si es necesario:

    ```php
    <?php
    $host = 'localhost'; // O la IP de tu servidor de BD
    $db   = 'InventarioFarmaceutico';
    $user = 'root';      // Tu usuario de BD
    $pass = '';          // Tu contraseña de BD
    $charset = 'utf8mb4';
    ?>
    ```

### Configuración de PHP

Puedes usar el servidor web incorporado de PHP para desarrollo:

1.  Abre tu terminal.

2.  Navega hasta la carpeta raíz del proyecto (`PROYECTOFINAL`):

    ```bash
    cd /ruta/a/tu/proyecto/PROYECTOFINAL
    ```

3.  Inicia el servidor PHP:

    ```bash
    php -S localhost:8000
    ```

---

## 🚀 Uso de la Aplicación

Una vez que el servidor esté corriendo, abre tu navegador web y visita:

```
http://localhost:8000
```

- **Para Productos:** Navega a `http://localhost:8000/src/modules/productos/index.php` para ver la lista, agregar, editar o "eliminar" productos.
- **Para Reportes:** Navega a `http://localhost:8000/src/modules/reportes/index.php` para generar los diferentes tipos de informes.

---

## 📂 Estructura del Proyecto

```
PROYECTOFINAL/
├── database/
│   └── init.sql            # Script SQL para la creación de la base de datos y tablas
├── src/
│   ├── config/
│   │   └── db.php          # Configuración de la conexión a la base de datos
│   ├── modules/            # Módulos principales de la aplicación
│   │   ├── movimientos/
│   │   │   └── index.php   # Módulo de movimientos (puede expandirse)
│   │   ├── productos/
│   │   │   ├── index.php   # Lista de productos (activos)
│   │   │   ├── crear.php   # Formulario y lógica para agregar producto
│   │   │   ├── editar.php  # Formulario y lógica para editar producto
│   │   │   └── eliminar.php # Lógica para "soft delete" de producto
│   │   ├── reportes/
│   │   │   └── index.php   # Selector y generador de reportes
│   │   └── usuarios/
│   │       ├── crear.php   # Formulario y lógica para agregar usuario
│   │       ├── editar.php  # Formulario y lógica para editar usuario
│   │       ├── eliminar.php # Lógica para eliminar usuario
│   │       └── index.php   # Lista de usuarios
│   └── templates/          # Plantillas reusables (header, footer, etc.)
│       ├── footer.php
│       └── header.php
├── index.php               # Archivo de entrada principal de la aplicación
└── README.md               # Este archivo
```

---

## 💻 Tecnologías Utilizadas

- **PHP:** Lenguaje de programación backend.
- **MySQL/MariaDB:** Sistema de gestión de bases de datos.
- **PDO:** Extensión de PHP para interactuar con la base de datos de forma segura.
- **HTML5:** Estructura de las páginas web.
- **Tailwind CSS:** Framework CSS para el diseño rápido de la interfaz.

---

## 👥 Integrantes

- **Andrés Burgos**
- **Jesús Carrascal**

---

## 📜 Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).

---
