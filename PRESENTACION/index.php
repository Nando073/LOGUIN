<?php
require_once '../NEGOCIO/N_User.php';
$userService = new N_User();

/// Verifica si se pasa un ID en la URL para editar o eliminar
$usuario = null;

if (isset($_GET['id'])) {
    $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($user_id) {
        // Crear una instancia del servicio de negocio
        $userService = new N_User();
        
        // Verifica si se ha solicitado eliminar
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            // Llamada al método de negocio para eliminar el electrodoméstico
            $userService->eliminar($user_id);
            // Redirigir al listado después de eliminar
            header('Location: index.php');
            exit();
        } else {
            // Llamada al método de negocio para obtener los datos del electrodoméstico
            $usuario = $userService->buscarPorId($user_id);
            if (!$usuario) {
                echo "No se encontró el user.";
            }
        }
    } else {
        echo "ID inválido.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
    $usuarioNombre = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING));
    $clave = trim($_POST['clave']); // Eliminar espacios sin sanitizar
    $accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);

    if ($id && $nombre && $usuarioNombre && $clave && $accion) {
        $existingUser = $userService->buscarPorId($id);
        // Verificar si la clave está en SHA-256
        if (esSHA256($clave)) {
            $clave = hash('sha256', $clave);
        } 

        if ($accion === 'crear') {
            if ($existingUser) {
                echo "Error: El usuario con el ID $id ya existe. No se puede crear.";
            } else {
                $userService->adicionar($id, $nombre, $usuarioNombre, $clave);
                header('Location: index.php');
                exit();
            }
        } elseif ($accion === 'guardar') {
            if ($existingUser) {
                $userService->modificar($id, $nombre, $usuarioNombre, $clave);
                header('Location: index.php');
                exit();
            } else {
                echo "Error: El usuario con el ID $id no existe. No se puede modificar.";
            }
        } else {
            echo "Error: Acción no válida.";
        }
    } else {
        echo "Error: Todos los campos son necesarios y deben ser válidos.";
    }
}

// Función para verificar si la clave es SHA-256
function esSHA256($clave) {
    return preg_match('/^[a-fA-F0-9]{64}$/', $clave);
}



// Obtener la lista de usuarios
$usuarios = $userService->buscarTodo();

// Buscar por término
$searchTerm = isset($_GET['search']) ? filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) : '';
if ($searchTerm) {
    $usuarios = $userService->buscarPorSimilitud($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestionar Usuarios</title>
</head>
<body>
    <div class="card mb-4" style="max-width: 540px;margin-left: 60vh">
        <div class="row g-0">
          <div class="col-md-5">
              <img src="../image/usuarios.jpg" class="img-fluid rounded-start">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <h4 class="card-title">ELECTRODOMESTICOS</h4>
              <h3 class="card-text"><small class="text-body-secondary">CRUD</small></h3>
            </div>
          </div>
        </div>
      </div>
    <div class="container mt-5">
        <h2 class="page-header">Gestionar Usuarios</h2>

        <!-- Formulario para crear o editar -->
        <!-- Formulario único para crear o guardar cambios -->
<form action="index.php" method="post">
    <div class="form-group">
        <label for="id">ID</label>
        <input type="number" class="form-control" id="id" name="id" value="<?php echo isset($usuario) ? htmlspecialchars($usuario['id']) : ''; ?>" <?php echo isset($usuario) ? 'readonly' : ''; ?> required>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($usuario) ? htmlspecialchars($usuario['nombre']) : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="usuario">Nombre de usuario</label>
        <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo isset($usuario) ? htmlspecialchars($usuario['usuario']) : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="clave">Contraseña</label>
        <input type="text" class="form-control" id="clave" name="clave" value="<?php echo isset($usuario) ? htmlspecialchars($usuario['clave']) : ''; ?>" required>
    </div>

    <!-- Botones para enviar el formulario con acciones diferentes -->
    <button type="submit" name="accion" value="crear" class="btn btn-primary mt-3">Crear Usuario</button>
    <button type="submit" name="accion" value="guardar" class="btn btn-success mt-3" <?php echo isset($usuario) ? '' : 'disabled'; ?>>Guardar Cambios</button>
    <!-- Botón para limpiar (nuevo) -->
    <a href="index.php" class="btn btn-secondary mt-3">Nuevo</a>
</form>


        <!-- Lista de usuarios -->
        <h3 class="mt-5">Usuarios</h3>
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($searchTerm); ?>" />
            <button type="submit" class="btn btn-info">Buscar</button>
        </form>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Clave (Encriptada)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($user['clave']); ?></td>
                        <td>
                            <a href="index.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="index.php?id=<?php echo $user['id']; ?>&action=delete" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../index.php"><button type="button"class="btn btn-info">Login</button></a>
    </div>
</body>
</html>
