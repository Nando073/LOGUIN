<?php
require_once 'NEGOCIO/N_User.php';
$userService = new N_User();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $clave = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING);

    // Validar credenciales usando el método loguear
    $usuarioValido = $userService->loguear($usuario, $clave);

    if ($usuarioValido) {
        // Redirigir al CRUD de electrodomésticos si es válido
        header('Location: PRESENTACION/electrodomestico.php');
        exit();
    } else {
        $error = 'Usuario o clave incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>       
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                <?php endif; ?>

    <div id="body">
    <div class="container">
        <div class="form-container">
            <h1>messimo</h1>
            <h2>Crear cuenta</h2>
            <form  method="post">
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario"  required>
                <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña"  required>
                <button type="submit" class="primary-btn" name="login">Iniciar sesion</button><br/>
            </form>
            <a class="CREAR" href="PRESENTACION/index.php">Crear Usuario</a>
            <p>o registrarse con</p>
            <div class="social-buttons">
                <button class="social-btn">G</button>
                <button class="social-btn">f</button>
            </div>
            <p>¿Ya tienes una cuenta? <a href="#">Inicia sesión</a></p>
            <p class="terms">Al crear una cuenta, aceptas los <a href="#">Términos de Servicio</a> y la <a href="#">Política de Privacidad</a>.</p>
        </div>
    </div>

</div> 
</body>
</html>
