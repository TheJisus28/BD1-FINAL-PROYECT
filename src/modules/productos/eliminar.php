<?php
// Incluye el archivo de configuración de la base de datos
include '../../config/db.php';

$codigo = $_GET['codigo'] ?? null; // Obtiene el código de producto de la URL

// Si no se proporciona un código, muestra un error y termina
if (!$codigo) {
  echo "Código de producto no especificado.";
  exit;
}

// Inicia una transacción para asegurar que las operaciones se realicen de forma atómica
$conn->beginTransaction();

try {
  // 1. Obtener la cantidad de stock actual del producto antes de "eliminarlo" (desactivarlo)
  $stmt = $conn->prepare("SELECT Cantidad_stock FROM Productos WHERE Código_producto = ? AND Activo = TRUE");
  $stmt->execute([$codigo]);
  $producto = $stmt->fetch(PDO::FETCH_ASSOC); // Usar PDO::FETCH_ASSOC para acceder por nombre

  // Si el producto existe y tiene stock, registra un movimiento de salida por descontinuación
  if ($producto && (int)$producto['Cantidad_stock'] > 0) {
    $cantidad_a_registrar = (int)$producto['Cantidad_stock'];
    $mov = $conn->prepare("INSERT INTO Movimientos_Inventario (Código_producto, Fecha_movimiento, Tipo_movimiento, Cantidad) VALUES (?, CURDATE(), 'Salida por Descontinuación', ?)");
    $mov->execute([$codigo, $cantidad_a_registrar]);
  }

  // 2. Realizar el "soft delete": Actualizar la columna 'Activo' a FALSE
  // Esto marca el producto como inactivo en lugar de eliminarlo físicamente
  $update_stmt = $conn->prepare("UPDATE Productos SET Activo = FALSE WHERE Código_producto = ?");
  $update_stmt->execute([$codigo]);

  // Si todas las operaciones fueron exitosas, confirma la transacción
  $conn->commit();
  // Redirige al usuario de vuelta a la página principal de productos
  header('Location: index.php');
  exit;
} catch (PDOException $e) {
  // Si ocurre un error, revierte la transacción para deshacer cualquier cambio
  $conn->rollBack();
  // Muestra un mensaje de error detallado
  echo "Error al marcar el producto como inactivo: " . $e->getMessage();
  // En un entorno de producción, podrías logear el error en lugar de mostrarlo
  // error_log("Error de soft delete para producto " . $codigo . ": " . $e->getMessage());
  exit;
}
