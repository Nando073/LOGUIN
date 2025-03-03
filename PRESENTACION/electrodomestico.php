<?php
require_once '../NEGOCIO/N_Electro.php';
$electroService = new N_Electro();

// Verifica si se pasa un ID en la URL para editar o eliminar
$electrodomestico = null;

if (isset($_GET['id'])) {
    $electro_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($electro_id) {
        // Verifica si se ha solicitado eliminar
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            $electroService->eliminar($electro_id);
            header('Location: electrodomestico.php');
            exit();
        } else {
            $electrodomestico = $electroService->buscarPorId($electro_id);
            if (!$electrodomestico) {
                echo "No se encontró el electrodoméstico.";
            }
        }
    } else {
        echo "ID inválido.";
    }
}

// Crear o modificar electrodoméstico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $garantia = filter_input(INPUT_POST, 'garantia', FILTER_SANITIZE_STRING);
    $accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);

    if ($id && $nombre && $modelo && $marca && $precio && $garantia && $accion) {
        $existingElectrodomestico = $electroService->buscarPorId($id);

        if ($accion === 'crear') {
            if ($existingElectrodomestico) {
                echo "Error: El electrodoméstico con el ID $id ya existe. No se puede crear.";
            } else {
                $electroService->adicionar($id, $nombre, $modelo, $marca, $precio, $garantia);
                header('Location: electrodomestico.php');
                exit();
            }
        } elseif ($accion === 'guardar') {
            if ($existingElectrodomestico) {
                $electroService->modificar($id, $nombre, $modelo, $marca, $precio, $garantia);
                header('Location: electrodomestico.php');
                exit();
            } else {
                echo "Error: El electrodoméstico con el ID $id no existe. No se puede modificar.";
            }
        } else {
            echo "Error: Acción no válida.";
        }
    } else {
        echo "Error: Todos los campos son necesarios y deben ser válidos.";
    }
}

// Obtener la lista de electrodomésticos
$electrodomesticos = $electroService->buscarTodo();

// Buscar por término
$searchTerm = isset($_GET['search']) ? filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) : '';
if ($searchTerm) {
    $electrodomesticos = $electroService->buscarPorSimilitud($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestionar Electrodomésticos</title>
</head>
<body>
    <div class="card mb-4" style="max-width: 540px;margin-left: 60vh">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="../image/elect.jpeg" class="img-fluid rounded-start">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h4 class="card-title">ELECTRODOMÉSTICOS</h4>
                    <h3 class="card-text"><small class="text-body-secondary">CRUD</small></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="page-header">Gestionar Electrodomésticos</h2>

        <!-- Formulario para crear o editar -->
        <form action="electrodomestico.php" method="post">
            <div class="form-group">
                <label for="id">ID</label>
                <input type="number" class="form-control" id="id" name="id" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['id']) : ''; ?>" <?php echo isset($electrodomestico) ? 'readonly' : ''; ?> required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['nombre']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo</label>
                <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['modelo']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['marca']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['precio']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="garantia">Garantía</label>
                <input type="text" class="form-control" id="garantia" name="garantia" value="<?php echo isset($electrodomestico) ? htmlspecialchars($electrodomestico['garantia']) : ''; ?>" required>
            </div>

            <!-- Botones -->
            <button type="submit" name="accion" value="crear" class="btn btn-primary mt-3">Crear</button>
            <button type="submit" name="accion" value="guardar" class="btn btn-success mt-3" <?php echo isset($electrodomestico) ? '' : 'disabled'; ?>>Guardar Cambios</button>
            <a href="electrodomestico.php" class="btn btn-secondary mt-3">Nuevo</a>
        </form>

        <!-- Buscador -->
        <form action="electrodomestico.php" method="get" class="mt-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar electrodoméstico..." value="<?php echo htmlspecialchars($searchTerm); ?>" required>
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </form>

        <h2 class="mt-5">Lista de Electrodomésticos</h2>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Garantía</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($electrodomesticos as $electro): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($electro['id']); ?></td>
                        <td><?php echo htmlspecialchars($electro['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($electro['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($electro['marca']); ?></td>
                        <td><?php echo htmlspecialchars($electro['precio']); ?></td>
                        <td><?php echo htmlspecialchars($electro['garantia']); ?></td>
                        <td>
                            <a href="electrodomestico.php?id=<?php echo $electro['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="electrodomestico.php?id=<?php echo $electro['id']; ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este electrodoméstico?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../index.php"><button type="button"class="btn btn-info">Login</button></a>
    </div>
</body>
</html>
