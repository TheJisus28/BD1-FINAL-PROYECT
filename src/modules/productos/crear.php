<?php
// Incluye el archivo de configuración de la base de datos
include '../../config/db.php';
// Incluye la cabecera de la plantilla
include '../../templates/header.php';

// Procesa el formulario cuando se envía (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtiene los datos del formulario
  $codigo = $_POST['codigo'];
  $nombre = $_POST['nombre'];
  $vencimiento = $_POST['vencimiento'];
  $stock = (int)$_POST['stock']; // Convierte a entero
  $categoria = $_POST['categoria'];
  $principio = $_POST['principio'];

  // Inicia una transacción para asegurar que ambas inserciones sean atómicas
  $conn->beginTransaction();

  try {
    // Inserta el nuevo producto en la tabla Productos
    // La columna 'Activo' tomará su valor por defecto TRUE definido en la DB
    $stmt = $conn->prepare("INSERT INTO Productos (Código_producto, Nombre_medicamento, Fecha_vencimiento, Cantidad_stock, Categoría, Principio_activo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nombre, $vencimiento, $stock, $categoria, $principio]);

    // Si el stock inicial es mayor que 0, inserta un movimiento de entrada
    if ($stock > 0) {
      $mov = $conn->prepare("INSERT INTO Movimientos_Inventario (Código_producto, Fecha_movimiento, Tipo_movimiento, Cantidad) VALUES (?, CURDATE(), 'Entrada', ?)");
      $mov->execute([$codigo, $stock]);
    }

    // Si ambas operaciones fueron exitosas, confirma la transacción
    $conn->commit();
    // Redirige al usuario de vuelta a la página principal de productos
    header('Location: index.php');
    exit; // Termina la ejecución del script

  } catch (PDOException $e) {
    // Si ocurre un error, revierte la transacción para deshacer cualquier cambio
    $conn->rollBack();
    echo "Error al guardar el producto: " . $e->getMessage();
    // En un entorno de producción, podrías logear el error en lugar de mostrarlo
    // error_log("Error al crear producto " . $codigo . ": " . $e->getMessage());
    exit;
  }
}
?>

<h1 class="text-2xl font-bold mb-6">Agregar Producto</h1>

<form method="POST" class="space-y-4">
  <div>
    <label class="block">Código del Producto:</label>
    <input type="text" name="codigo" required class="w-full border p-2">
  </div>
  <div>
    <label class="block">Nombre del Medicamento:</label>
    <input type="text" name="nombre" required class="w-full border p-2">
  </div>
  <div>
    <label class="block">Fecha de Vencimiento:</label>
    <input type="date" name="vencimiento" required class="w-full border p-2">
  </div>
  <div>
    <label class="block">Cantidad en Stock:</label>
    <input type="number" name="stock" min="0" required class="w-full border p-2">
  </div>
  <div>
    <label class="block">Categoría:</label>
    <input type="text" name="categoria" class="w-full border p-2">
  </div>
  <div>
    <label class="block">Principio Activo:</label>
    <input type="text" name="principio" class="w-full border p-2">
  </div>
  <div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
    <a href="index.php" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
  </div>
</form>

<?php
// Incluye el pie de página de la plantilla
include '../../templates/footer.php';
?>