<?php
include '../../config/db.php';
include '../../templates/header.php';
?>

<h1 class="text-2xl font-bold mb-4">Movimientos de Inventario</h1>

<table class="min-w-full bg-white border border-gray-300">
  <thead>
    <tr class="bg-gray-100">
      <th class="py-2 px-4 border-b">ID Movimiento</th>
      <th class="py-2 px-4 border-b">Código de Producto</th>
      <th class="py-2 px-4 border-b">Nombre del Producto</th>
      <th class="py-2 px-4 border-b">Fecha</th>
      <th class="py-2 px-4 border-b">Tipo</th>
      <th class="py-2 px-4 border-b">Cantidad</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT m.ID_movimiento, m.Código_producto, p.Nombre_medicamento, m.Fecha_movimiento, m.Tipo_movimiento, m.Cantidad
            FROM Movimientos_Inventario m
            LEFT JOIN Productos p ON m.Código_producto = p.Código_producto
            ORDER BY m.ID_movimiento DESC";
    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
      echo "<td class='py-2 px-4 border-b'>{$row['ID_movimiento']}</td>";
      echo "<td class='py-2 px-4 border-b'>{$row['Código_producto']}</td>";
      echo "<td class='py-2 px-4 border-b'>{$row['Nombre_medicamento']}</td>";
      echo "<td class='py-2 px-4 border-b'>{$row['Fecha_movimiento']}</td>";
      echo "<td class='py-2 px-4 border-b'>{$row['Tipo_movimiento']}</td>";
      echo "<td class='py-2 px-4 border-b'>{$row['Cantidad']}</td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

<?php include '../../templates/footer.php'; ?>