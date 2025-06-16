<?php include 'templates/header.php'; ?>

<h1 class="text-3xl font-bold mb-6">Sistema de Gestión de Inventario Farmacéutico</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
  <!-- Usuarios -->
  <a href="modules/usuarios/index.php" class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-blue-500 hover:shadow-xl transition duration-200">
    <h2 class="text-xl font-bold text-blue-600 mb-2">Usuarios</h2>
    <p class="text-gray-700 text-sm">Gestionar usuarios del sistema: crear, editar y eliminar.</p>
  </a>

  <!-- Productos -->
  <a href="modules/productos/index.php" class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-green-500 hover:shadow-xl transition duration-200">
    <h2 class="text-xl font-bold text-green-600 mb-2">Productos</h2>
    <p class="text-gray-700 text-sm">Gestión del inventario de medicamentos y sus características.</p>
  </a>

  <!-- Movimientos -->
  <a href="modules/movimientos/index.php" class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-yellow-500 hover:shadow-xl transition duration-200">
    <h2 class="text-xl font-bold text-yellow-600 mb-2">Movimientos</h2>
    <p class="text-gray-700 text-sm">Registrar entradas y salidas de productos del inventario.</p>
  </a>

  <!-- Reportes -->
  <a href="modules/reportes/index.php" class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-red-500 hover:shadow-xl transition duration-200">
    <h2 class="text-xl font-bold text-red-600 mb-2">Reportes</h2>
    <p class="text-gray-700 text-sm">Visualizar y descargar reportes generados del inventario.</p>
  </a>
</div>

<?php include 'templates/footer.php'; ?>