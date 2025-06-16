<?php include '../../config/db.php'; ?>
<?php include '../../templates/header.php'; ?>

<?php
if (!isset($_GET['id'])) {
  die('ID de usuario no especificado');
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Usuarios WHERE ID_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
  die('Usuario no encontrado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $rol = $_POST['rol'];

  if (!empty($_POST['contrasena'])) {
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE Usuarios SET Nombre_usuario = ?, Contraseña = ?, Rol_usuario = ? WHERE ID_usuario = ?");
    $stmt->execute([$nombre, $contrasena, $rol, $id]);
  } else {
    $stmt = $conn->prepare("UPDATE Usuarios SET Nombre_usuario = ?, Rol_usuario = ? WHERE ID_usuario = ?");
    $stmt->execute([$nombre, $rol, $id]);
  }

  header("Location: index.php");
}
?>

<h2 class="text-xl font-semibold mb-4">Editar Usuario</h2>
<form method="POST" class="space-y-4">
  <div>
    <label class="block mb-1">Nombre de Usuario</label>
    <input type="text" name="nombre" required value="<?= htmlspecialchars($usuario['Nombre_usuario']) ?>" class="w-full border px-3 py-2 rounded">
  </div>
  <div>
    <label class="block mb-1">Contraseña (dejar en blanco para no cambiar)</label>
    <input type="password" name="contrasena" class="w-full border px-3 py-2 rounded">
  </div>
  <div>
    <label class="block mb-1">Rol</label>
    <select name="rol" required class="w-full border px-3 py-2 rounded">
      <option value="Administrador" <?= $usuario['Rol_usuario'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
      <option value="Auxiliar" <?= $usuario['Rol_usuario'] == 'Auxiliar' ? 'selected' : '' ?>>Auxiliar</option>
      <option value="Auditor" <?= $usuario['Rol_usuario'] == 'Auditor' ? 'selected' : '' ?>>Auditor</option>
    </select>
  </div>
  <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Actualizar</button>
  <a href="index.php" class="ml-4 text-gray-700 hover:underline">Cancelar</a>
</form>

<?php include '../../templates/footer.php'; ?>