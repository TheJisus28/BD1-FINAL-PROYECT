<?php
// Incluye el archivo de configuración de la base de datos
include_once '../../config/db.php';
// Incluye la cabecera de la plantilla
include_once '../../templates/header.php';

// Verificar si hay un código de producto en GET
if (!isset($_GET['codigo'])) {
  echo "Código de producto no proporcionado.";
  exit;
}

$codigo = $_GET['codigo'];
$producto = null;

// Obtener datos actuales del producto
// No se filtra por 'Activo' aquí, ya que podrías querer editar un producto inactivo para reactivarlo
$stmt = $conn->prepare("SELECT * FROM Productos WHERE Código_producto = ?");
$stmt->execute([$codigo]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el producto no se encuentra, muestra un mensaje y termina
if (!$producto) {
  echo "Producto no encontrado.";
  exit;
}

// Procesa el formulario cuando se envía (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtiene los datos del formulario
  $nombre = $_POST['nombre'];
  $fecha = $_POST['fecha'];
  $cantidad = intval($_POST['cantidad']);
  $categoria = $_POST['categoria'];
  $principio = $_POST['principio'];

  // Obtiene la cantidad de stock anterior para calcular la diferencia
  $cantidadAnterior = intval($producto['Cantidad_stock']);
  $diferencia = $cantidad - $cantidadAnterior;

  // Actualiza los datos del producto en la base de datos
  $stmt = $conn->prepare("UPDATE Productos SET Nombre_medicamento = ?, Fecha_vencimiento = ?, Cantidad_stock = ?, Categoría = ?, Principio_activo = ? WHERE Código_producto = ?");
  $stmt->execute([$nombre, $fecha, $cantidad, $categoria, $principio, $codigo]);

  // Registra un movimiento de inventario si la cantidad de stock cambió
  if ($diferencia !== 0) {
    $tipo = $diferencia > 0 ? 'Entrada' : 'Salida'; // Determina si es entrada o salida
    $cantidadMovimiento = abs($diferencia); // La cantidad del movimiento siempre es positiva
    $fechaMovimiento = date('Y-m-d'); // Fecha actual del movimiento

    // Inserta el registro del movimiento en la tabla Movimientos_Inventario
    $stmt = $conn->prepare("INSERT INTO Movimientos_Inventario (Código_producto, Fecha_movimiento, Tipo_movimiento, Cantidad) VALUES (?, ?, ?, ?)");
    $stmt->execute([$codigo, $fechaMovimiento, $tipo, $cantidadMovimiento]);
  }

  // Redirige al usuario a la página principal de productos después de la actualización
  header("Location: index.php");
  exit; // Termina la ejecución del script
}
?>

<h1 class="text-2xl font-bold mb-4">Editar Producto</h1>
<form method="POST" class="space-y-4">
  <div>
    <label class="block font-medium">Código</label>
    <!-- El código del producto se muestra pero no se puede editar (disabled) -->
    <input type="text" name="codigo" value="<?= htmlspecialchars($producto['Código_producto']) ?>" disabled class="w-full border p-2 bg-gray-100">
  </div>
  <div>
    <label class="block font-medium">Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['Nombre_medicamento']) ?>" required class="w-full border p-2">
  </div>
  <div>
    <label class="block font-medium">Fecha de Vencimiento</label>
    <input type="date" name="fecha" value="<?= htmlspecialchars($producto['Fecha_vencimiento']) ?>" required class="w-full border p-2">
  </div>
  <div>
    <label class="block font-medium">Cantidad en Stock</label>
    <input type="number" name="cantidad" value="<?= htmlspecialchars($producto['Cantidad_stock']) ?>" required class="w-full border p-2">
  </div>
  <div>
    <label class="block font-medium">Categoría</label>
    <input type="text" name="categoria" value="<?= htmlspecialchars($producto['Categoría']) ?>" class="w-full border p-2">
  </div>
  <div>
    <label class="block font-medium">Principio Activo</label>
    <input type="text" name="principio" value="<?= htmlspecialchars($producto['Principio_activo']) ?>" class="w-full border p-2">
  </div>
  <div class="flex space-x-4">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar Cambios</button>
    <a href="index.php" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</a>
  </div>
</form>

<?php
// Incluye el pie de página de la plantilla
include_once '../../templates/footer.php';
?>