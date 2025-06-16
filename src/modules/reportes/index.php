<?php
// Incluye el archivo de configuración de la base de datos
include_once '../../config/db.php';
// Incluye la cabecera de la plantilla
include_once '../../templates/header.php';

// Variable para almacenar el tipo de reporte seleccionado
$report_type = $_GET['type'] ?? ($_POST['report_type'] ?? null);

// Variables para filtros de fecha
$start_date = $_GET['start_date'] ?? ($_POST['start_date'] ?? null);
$end_date = $_GET['end_date'] ?? ($_POST['end_date'] ?? null);
// Variable para filtro de código de producto
$product_code = $_GET['product_code'] ?? ($_POST['product_code'] ?? null);
?>

<h1 class="text-2xl font-bold mb-6">Módulo de Reportes</h1>

<div class="bg-white p-6 rounded-lg shadow-md mb-8">
  <h2 class="text-xl font-semibold mb-4">Seleccionar Tipo de Reporte</h2>
  <form method="GET" action="index.php" class="space-y-4">
    <div>
      <label for="report_type" class="block text-sm font-medium text-gray-700">Tipo de Reporte:</label>
      <select id="report_type" name="type" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
        <option value="">-- Seleccione un reporte --</option>
        <option value="stock_actual" <?= $report_type === 'stock_actual' ? 'selected' : '' ?>>Stock Actual de Productos</option>
        <option value="movimientos_rango" <?= $report_type === 'movimientos_rango' ? 'selected' : '' ?>>Movimientos de Inventario por Rango de Fechas</option>
        <option value="productos_vencimiento" <?= $report_type === 'productos_vencimiento' ? 'selected' : '' ?>>Productos Próximos a Vencer</option>
        <option value="productos_inactivos" <?= $report_type === 'productos_inactivos' ? 'selected' : '' ?>>Productos Inactivos/Descontinuados</option>
        <option value="movimientos_producto" <?= $report_type === 'movimientos_producto' ? 'selected' : '' ?>>Movimientos de un Producto Específico</option>
      </select>
    </div>
  </form>

  <?php if ($report_type): ?>
    <div id="report-filters" class="mt-6 p-4 border border-gray-200 rounded-md bg-gray-50">
      <h3 class="text-lg font-medium mb-3">Filtros para el Reporte de <span class="capitalize"><?= str_replace('_', ' ', $report_type) ?></span></h3>
      <form method="GET" action="index.php" class="space-y-4">
        <input type="hidden" name="type" value="<?= htmlspecialchars($report_type) ?>">

        <?php if ($report_type === 'movimientos_rango' || $report_type === 'productos_vencimiento'): ?>
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio:</label>
            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date ?? date('Y-m-01')) ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Fin:</label>
            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date ?? date('Y-m-t')) ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
          </div>
        <?php endif; ?>

        <?php if ($report_type === 'productos_vencimiento'): ?>
          <div>
            <label for="vencimiento_meses" class="block text-sm font-medium text-gray-700">Próximos a vencer en (meses):</label>
            <input type="number" id="vencimiento_meses" name="vencimiento_meses" value="<?= htmlspecialchars($_GET['vencimiento_meses'] ?? 6) ?>" min="1" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
          </div>
        <?php endif; ?>

        <?php if ($report_type === 'movimientos_producto'): ?>
          <div>
            <label for="product_code" class="block text-sm font-medium text-gray-700">Código de Producto:</label>
            <input type="text" id="product_code" name="product_code" value="<?= htmlspecialchars($product_code ?? '') ?>" placeholder="Ej: PROD001" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
          </div>
        <?php endif; ?>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Generar Reporte</button>
      </form>
    </div>
  <?php endif; ?>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
  <h2 class="text-xl font-semibold mb-4">Resultados del Reporte</h2>

  <?php
  try {
    switch ($report_type) {
      case 'stock_actual':
        echo '<h3 class="text-lg font-medium mb-3">Reporte de Stock Actual de Productos</h3>';
        $stmt = $conn->query("SELECT Código_producto, Nombre_medicamento, Cantidad_stock, Categoría, Principio_activo FROM Productos WHERE Activo = TRUE ORDER BY Nombre_medicamento ASC");
        $productos_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($productos_stock) > 0) {
          echo '<table class="min-w-full bg-white border">';
          echo '<thead><tr>';
          echo '<th class="border px-4 py-2">Código</th>';
          echo '<th class="border px-4 py-2">Nombre</th>';
          echo '<th class="border px-4 py-2">Stock Actual</th>';
          echo '<th class="border px-4 py-2">Categoría</th>';
          echo '<th class="border px-4 py-2">Principio Activo</th>';
          echo '</tr></thead><tbody>';

          foreach ($productos_stock as $producto) {
            echo '<tr>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($producto['Código_producto']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($producto['Nombre_medicamento']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($producto['Cantidad_stock']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($producto['Categoría']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($producto['Principio_activo']) . '</td>';
            echo '</tr>';
          }
          echo '</tbody></table>';
        } else {
          echo '<p class="text-gray-600">No hay productos activos en stock para este reporte.</p>';
        }
        break;

      case 'movimientos_rango':
        echo '<h3 class="text-lg font-medium mb-3">Movimientos de Inventario entre ' . htmlspecialchars($start_date) . ' y ' . htmlspecialchars($end_date) . '</h3>';

        if ($start_date && $end_date) {
          $stmt = $conn->prepare("SELECT mi.Fecha_movimiento, p.Nombre_medicamento, mi.Tipo_movimiento, mi.Cantidad, p.Código_producto
                                            FROM Movimientos_Inventario mi
                                            JOIN Productos p ON mi.Código_producto = p.Código_producto
                                            WHERE mi.Fecha_movimiento BETWEEN ? AND ?
                                            ORDER BY mi.Fecha_movimiento DESC, p.Nombre_medicamento ASC");
          $stmt->execute([$start_date, $end_date]);
          $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($movimientos) > 0) {
            echo '<table class="min-w-full bg-white border">';
            echo '<thead><tr>';
            echo '<th class="border px-4 py-2">Fecha</th>';
            echo '<th class="border px-4 py-2">Código Producto</th>';
            echo '<th class="border px-4 py-2">Nombre Producto</th>';
            echo '<th class="border px-4 py-2">Tipo Movimiento</th>';
            echo '<th class="border px-4 py-2">Cantidad</th>';
            echo '</tr></thead><tbody>';

            foreach ($movimientos as $mov) {
              echo '<tr>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Fecha_movimiento']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Código_producto']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Nombre_medicamento']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Tipo_movimiento']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Cantidad']) . '</td>';
              echo '</tr>';
            }
            echo '</tbody></table>';
          } else {
            echo '<p class="text-gray-600">No hay movimientos registrados en el rango de fechas seleccionado.</p>';
          }
        } else {
          echo '<p class="text-red-600">Por favor, seleccione un rango de fechas para generar este reporte.</p>';
        }
        break;

      case 'productos_vencimiento':
        $vencimiento_meses = (int)($_GET['vencimiento_meses'] ?? 6);
        echo '<h3 class="text-lg font-medium mb-3">Productos Próximos a Vencer (en los próximos ' . htmlspecialchars($vencimiento_meses) . ' meses)</h3>';

        // Calcula la fecha límite (hoy + X meses)
        $fecha_limite = date('Y-m-d', strtotime("+" . $vencimiento_meses . " months"));
        $today = date('Y-m-d');

        $stmt = $conn->prepare("SELECT Código_producto, Nombre_medicamento, Fecha_vencimiento, Cantidad_stock
                                        FROM Productos
                                        WHERE Activo = TRUE
                                        AND Fecha_vencimiento BETWEEN ? AND ?
                                        ORDER BY Fecha_vencimiento ASC");
        $stmt->execute([$today, $fecha_limite]);
        $productos_vencimiento = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($productos_vencimiento) > 0) {
          echo '<table class="min-w-full bg-white border">';
          echo '<thead><tr>';
          echo '<th class="border px-4 py-2">Código</th>';
          echo '<th class="border px-4 py-2">Nombre</th>';
          echo '<th class="border px-4 py-2">Fecha Vencimiento</th>';
          echo '<th class="border px-4 py-2">Stock</th>';
          echo '</tr></thead><tbody>';

          foreach ($productos_vencimiento as $prod) {
            echo '<tr>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Código_producto']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Nombre_medicamento']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Fecha_vencimiento']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Cantidad_stock']) . '</td>';
            echo '</tr>';
          }
          echo '</tbody></table>';
        } else {
          echo '<p class="text-gray-600">No hay productos activos próximos a vencer en el período seleccionado.</p>';
        }
        break;

      case 'productos_inactivos':
        echo '<h3 class="text-lg font-medium mb-3">Productos Inactivos/Descontinuados</h3>';
        $stmt = $conn->query("SELECT Código_producto, Nombre_medicamento, Fecha_vencimiento, Cantidad_stock, Categoría, Principio_activo
                                        FROM Productos
                                        WHERE Activo = FALSE
                                        ORDER BY Nombre_medicamento ASC");
        $productos_inactivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($productos_inactivos) > 0) {
          echo '<table class="min-w-full bg-white border">';
          echo '<thead><tr>';
          echo '<th class="border px-4 py-2">Código</th>';
          echo '<th class="border px-4 py-2">Nombre</th>';
          echo '<th class="border px-4 py-2">Vencimiento</th>';
          echo '<th class="border px-4 py-2">Stock</th>';
          echo '<th class="border px-4 py-2">Categoría</th>';
          echo '<th class="border px-4 py-2">Principio Activo</th>';
          echo '</tr></thead><tbody>';

          foreach ($productos_inactivos as $prod) {
            echo '<tr>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Código_producto']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Nombre_medicamento']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Fecha_vencimiento']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Cantidad_stock']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Categoría']) . '</td>';
            echo '<td class="border px-4 py-2">' . htmlspecialchars($prod['Principio_activo']) . '</td>';
            echo '</tr>';
          }
          echo '</tbody></table>';
        } else {
          echo '<p class="text-gray-600">No hay productos marcados como inactivos.</p>';
        }
        break;

      case 'movimientos_producto':
        echo '<h3 class="text-lg font-medium mb-3">Movimientos para Producto: ' . htmlspecialchars($product_code ?? 'N/A') . '</h3>';

        if ($product_code) {
          $stmt = $conn->prepare("SELECT mi.Fecha_movimiento, mi.Tipo_movimiento, mi.Cantidad, p.Nombre_medicamento
                                            FROM Movimientos_Inventario mi
                                            JOIN Productos p ON mi.Código_producto = p.Código_producto
                                            WHERE mi.Código_producto = ?
                                            ORDER BY mi.Fecha_movimiento DESC");
          $stmt->execute([$product_code]);
          $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($movimientos) > 0) {
            echo '<table class="min-w-full bg-white border">';
            echo '<thead><tr>';
            echo '<th class="border px-4 py-2">Fecha</th>';
            echo '<th class="border px-4 py-2">Tipo Movimiento</th>';
            echo '<th class="border px-4 py-2">Cantidad</th>';
            echo '<th class="border px-4 py-2">Nombre Producto</th>';
            echo '</tr></thead><tbody>';

            foreach ($movimientos as $mov) {
              echo '<tr>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Fecha_movimiento']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Tipo_movimiento']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Cantidad']) . '</td>';
              echo '<td class="border px-4 py-2">' . htmlspecialchars($mov['Nombre_medicamento']) . '</td>';
              echo '</tr>';
            }
            echo '</tbody></table>';
          } else {
            echo '<p class="text-gray-600">No hay movimientos registrados para el producto ' . htmlspecialchars($product_code) . '.</p>';
          }
        } else {
          echo '<p class="text-red-600">Por favor, ingrese un Código de Producto para generar este reporte.</p>';
        }
        break;

      default:
        if ($report_type) { // Si se seleccionó un tipo pero no se maneja
          echo '<p class="text-gray-600">Seleccione un tipo de reporte para empezar.</p>';
        } else { // Si no se ha seleccionado ningún tipo de reporte
          echo '<p class="text-gray-600">Por favor, seleccione un tipo de reporte del menú desplegable de arriba.</p>';
        }
        break;
    }
  } catch (PDOException $e) {
    echo '<p class="text-red-600">Error al generar el reporte: ' . $e->getMessage() . '</p>';
  }
  ?>
</div>

<?php
// Incluye el pie de página de la plantilla
include_once '../../templates/footer.php';
?>