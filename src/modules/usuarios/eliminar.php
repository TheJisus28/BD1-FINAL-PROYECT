<?php include '../../config/db.php'; ?>

<?php
if (!isset($_GET['id'])) {
  die('ID de usuario no especificado');
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM Usuarios WHERE ID_usuario = ?");
$stmt->execute([$id]);

header("Location: index.php");
