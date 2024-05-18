<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="flex">
    <!-- Formulario de ingreso -->
    <div class="w-1/3">
      <h1 class="text-3xl font-bold underline mb-4">Todo List</h1>

      <?php
      // Configuración de la base de datos
      $dbhost = '40.117.148.233';
      $dbname = 'remote_db';
      $dbuser = 'remote';
      $dbpass = 'Nicol.225-linux';

      try {
          // Conectar a la base de datos utilizando PDO
          $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4";
          $options = [
              PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES   => false,
          ];

          $pdo = new PDO($dsn, $dbuser, $dbpass, $options);

          // Agregar nueva tarea
          if (isset($_POST['add_task'])) {
              $description = $_POST['description'];
              if (!empty($description)) {
                  $stmt = $pdo->prepare("INSERT INTO tasks (description) VALUES (:description)");
                  $stmt->execute(['description' => $description]);
                  echo "<p class='text-green-500'>Tarea agregada correctamente</p>";
              }
          }

          // Modificar tarea
          if (isset($_POST['update_task'])) {
              $id = (int)$_POST['id'];
              $description = $_POST['description'];
              if (!empty($description)) {
                  $stmt = $pdo->prepare("UPDATE tasks SET description = :description WHERE id = :id");
                  $stmt->execute(['description' => $description, 'id' => $id]);
                  echo "<p class='text-blue-500'>Tarea modificada correctamente</p>";
              }
          }

          // Eliminar tarea
          if (isset($_GET['delete'])) {
              $id = (int)$_GET['delete'];
              $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
              $stmt->execute(['id' => $id]);
              echo "<p class='text-red-500'>Tarea eliminada correctamente</p>";
          }

          // Marcar tarea como completada
          if (isset($_GET['complete'])) {
              $id = (int)$_GET['complete'];
              $stmt = $pdo->prepare("UPDATE tasks SET completed = 1 WHERE id = :id");
              $stmt->execute(['id' => $id]);
          }

          // Obtener y mostrar todas las tareas
          $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
          $tasks = $stmt->fetchAll();

      } catch (PDOException $e) {
          echo "Error en la conexión: " . $e->getMessage();
      }
      ?>

      <form method="post" action="" class="mb-4">
        <input type="text" name="description" placeholder="Nueva tarea" class="p-2 border border-gray-300 rounded mr-2">
        <button type="submit" name="add_task" class="bg-blue-500 text-white p-2 rounded">Agregar</button>
      </form>
    </div>

    <!-- Lista de tareas -->
    <div class="w-2/3 pl-6">
      <h2 class="text-2xl font-bold mb-4">Tareas</h2>
      <ul class="list-disc pl-5">
        <?php foreach ($tasks as $task): ?>
          <li class="flex justify-between items-center <?php echo $task['completed'] ? 'text-green-500' : 'text-red-500'; ?>">
            <span><?php echo htmlspecialchars($task['description']); ?></span>
            <div class="flex space-x-2">
              <a href="?complete=<?php echo $task['id']; ?>" class="text-blue-500">Completar</a>
              <form method="post" action="" class="flex space-x-2">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <input type="text" name="description" value="<?php echo htmlspecialchars($task['description']); ?>" class="p-1 border border-gray-300 rounded">
                <button type="submit" name="update_task" class="bg-yellow-500 text-white p-1 rounded">Modificar</button>
              </form>
              <a href="?delete=<?php echo $task['id']; ?>" class="text-red-500">Eliminar</a>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>
</html>
