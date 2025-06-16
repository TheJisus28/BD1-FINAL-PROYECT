<?php
// Incluye el archivo de configuración de la base de datos
include '../../config/db.php';
// Incluye la cabecera de la plantilla
include '../../templates/header.php';

// Selecciona todos los productos que están activos
// La cláusula WHERE Activo = TRUE asegura que solo se muestren los productos no "eliminados"
$stmt = $conn->query("SELECT * FROM Productos WHERE Activo = TRUE");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex justify-between items-center">
  <h1 class="text-2xl font-bold">Productos</h1>
  <a href="crear.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Agregar Producto</a>
</div>

<table class="min-w-full bg-white border">
  <thead>
    <tr>
      <th class="border px-4 py-2">Código</th>
      <th class="border px-4 py-2">Nombre</th>
      <th class="border px-4 py-2">Vencimiento</th>
      <th class="border px-4 py-2">Stock</th>
      <th class="border px-4 py-2">Categoría</th>
      <th class="border px-4 py-2">Principio Activo</th>
      <th class="border px-4 py-2">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $producto): ?>
      <tr>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Código_producto']) ?></td>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Nombre_medicamento']) ?></td>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Fecha_vencimiento']) ?></td>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Cantidad_stock']) ?></td>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Categoría']) ?></td>
        <td class="border px-4 py-2"><?= htmlspecialchars($producto['Principio_activo']) ?></td>
        <td class="border px-4 py-2 space-x-2">
          <a href="editar.php?codigo=<?= $producto['Código_producto'] ?>" class="text-blue-600 hover:underline">Editar</a>
          <!-- El enlace de eliminar ahora activará el soft delete -->
          <a href="eliminar.php?codigo=<?= $producto['Código_producto'] ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Estás seguro de marcar este producto como inactivo? No se eliminará físicamente, pero desaparecerá de la lista activa.')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
// Incluye el pie de página de la plantilla
include '../../templates/footer.php';
?>