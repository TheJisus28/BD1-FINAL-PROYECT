<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>SGIF - Sistema de Gestión de Inventario Farmacéutico</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6">
      <h2 class="text-2xl font-bold text-blue-600 mb-6">SGIF</h2>
      <nav class="space-y-4">
        <a href="/" class="block text-gray-700 hover:text-blue-600">Inicio</a>
        <a href="/modules/usuarios/index.php" class="block text-gray-700 hover:text-blue-600">Usuarios</a>
        <a href="/modules/productos/index.php" class="block text-gray-700 hover:text-blue-600">Productos</a>
        <a href="/modules/movimientos/index.php" class="block text-gray-700 hover:text-blue-600">Movimientos</a>
        <a href="/modules/reportes/index.php" class="block text-gray-700 hover:text-blue-600">Reportes</a>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 p-6">