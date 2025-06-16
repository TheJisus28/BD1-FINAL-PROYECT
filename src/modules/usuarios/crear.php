<?php include '../../config/db.php'; ?>
<?php include '../../templates/header.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = $_POST['nombre'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
  $rol = $_POST['rol'];

  $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre_usuario, Contraseña, Rol_usuario) VALUES (?, ?, ?)");
  $stmt->execute([$nombre, $contrasena, $rol]);

  header("Location: index.php");
}
?>

<h2 class="text-xl font-semibold mb-4">Crear Usuario</h2>
<form method="POST" class="space-y-4">
  <div>
    <label class="block mb-1">Nombre de Usuario</label>
    <input type="text" name="nombre" required class="w-full border px-3 py-2 rounded">
  </div>
  <div>
    <label class="block mb-1">Contraseña</label>
    <input type="password" name="contrasena" required class="w-full border px-3 py-2 rounded">
  </div>
  <div>
    <label class="block mb-1">Rol</label>
    <select name="rol" required class="w-full border px-3 py-2 rounded">
      <option value="Administrador">Administrador</option>
      <option value="Auxiliar">Auxiliar</option>
      <option value="Auditor">Auditor</option>
    </select>
  </div>
  <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Guardar</button>
  <a href="index.php" class="ml-4 text-gray-700 hover:underline">Cancelar</a>
</form>

<?php include '../../templates/footer.php'; ?>