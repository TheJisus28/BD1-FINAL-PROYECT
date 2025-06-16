<?php include '../../config/db.php'; ?>
<?php include '../../templates/header.php'; ?>

<a href="crear.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Nuevo Usuario</a>

<table class="min-w-full bg-white shadow-md mt-6 rounded">
  <thead class="bg-gray-200">
    <tr>
      <th class="py-2 px-4">ID</th>
      <th class="py-2 px-4">Usuario</th>
      <th class="py-2 px-4">Rol</th>
      <th class="py-2 px-4">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $stmt = $conn->query("SELECT ID_usuario, Nombre_usuario, Rol_usuario FROM Usuarios");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr class='border-b'>
              <td class='py-2 px-4'>{$row['ID_usuario']}</td>
              <td class='py-2 px-4'>{$row['Nombre_usuario']}</td>
              <td class='py-2 px-4'>{$row['Rol_usuario']}</td>
              <td class='py-2 px-4'>
                <a href='editar.php?id={$row['ID_usuario']}' class='text-blue-600 hover:underline mr-3'>Editar</a>
                <a href='eliminar.php?id={$row['ID_usuario']}' class='text-red-600 hover:underline'>Eliminar</a>
              </td>
            </tr>";
    }
    ?>
  </tbody>
</table>

<?php include '../../templates/footer.php'; ?>