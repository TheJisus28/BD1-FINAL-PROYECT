-- Sistema de Gestión de Inventario Farmacéutico
-- UNIVERSIDAD DE CORDOBA
-- INTEGRANTES: ANDRES BURGOS Y JESUS CARRASCAL

-- ===============================
-- 0. BASE DE DATOS
-- ===============================
CREATE DATABASE IF NOT EXISTS InventarioFarmaceutico;
USE InventarioFarmaceutico;

-- ===============================
-- 1. TABLA: Usuarios
-- ===============================
CREATE TABLE Usuarios (
    ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre_usuario VARCHAR(50) NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Rol_usuario ENUM('Administrador', 'Auxiliar', 'Auditor') NOT NULL
);

-- ===============================
-- 2. TABLA: Productos
-- ===============================
CREATE TABLE Productos (
    Código_producto VARCHAR(20) PRIMARY KEY,
    Nombre_medicamento VARCHAR(100) NOT NULL,
    Fecha_vencimiento DATE NOT NULL,
    Cantidad_stock INT DEFAULT 0, -- Se movió DEFAULT aquí
    Categoría VARCHAR(50),
    Principio_activo VARCHAR(100),
    -- Columna para Soft Delete
    Activo BOOLEAN DEFAULT TRUE, -- TRUE = producto activo, FALSE = producto "eliminado" (inactivo)
    CONSTRAINT chk_cantidad_stock CHECK (Cantidad_stock >= 0) -- La restricción CHECK se definió al final
);

-- ===============================
-- 3. TABLA: Movimientos_Inventario
-- ===============================
CREATE TABLE Movimientos_Inventario (
    ID_movimiento INT PRIMARY KEY AUTO_INCREMENT,
    Código_producto VARCHAR(20),
    Fecha_movimiento DATE NOT NULL,
    Tipo_movimiento ENUM('Entrada', 'Salida', 'Ajuste', 'Salida por Descontinuación') NOT NULL,
    Cantidad INT CHECK (Cantidad > 0),
    FOREIGN KEY (Código_producto) REFERENCES Productos(Código_producto)
);

-- ===============================
-- 4. TABLA: Reportes
-- ===============================
CREATE TABLE Reportes (
    ID_reporte INT PRIMARY KEY AUTO_INCREMENT,
    Tipo_reporte VARCHAR(50) NOT NULL,
    Fecha_generacion DATE NOT NULL,
    Contenido_reporte VARCHAR(255) NOT NULL
);
