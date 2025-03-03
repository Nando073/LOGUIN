<?php
require_once __DIR__ . '/../DATOS/D_User.php';
require_once __DIR__ . '/../DATOS/D_coneccion.php';
class N_User {

    // Método para adicionar usuario
    public function adicionar($id, $nombre, $usuario, $clave) {
        // Encriptar la clave con SHA-256
        $claveEncriptada = $this->hashSHA256($clave);
        $user = new D_User(); 
        $user->Adicionar($id, $nombre, $usuario, $claveEncriptada);  // Llamar al método de D_User
    }

    // Método para buscar todos los usuarios
    public function buscarTodo() {
        $user = new D_User();
        return $user->BuscarTodo();
    }

    // Método para eliminar un usuario por ID
    public function eliminar($id) {
        $user = new D_User();
        $user->Eliminar($id);  // Llamar al método Eliminar de D_User
    }

    // Método para buscar usuarios por similitud de término
    public function buscarPorSimilitud($termino) {
        $user = new D_User();
        return $user->buscarPorSimilitud($termino);  // Llamar al método buscarPorSimilitud de D_User
    }

    // Método para modificar un usuario
    public function modificar($id, $nombre, $usuario, $clave) {
        // Encriptar la clave con SHA-256
        $claveEncriptada = $this->hashSHA256($clave);
        $user = new D_User();
        $user->modificar($id, $nombre, $usuario, $claveEncriptada);  // Llamar al método modificar de D_User
    }

    // Método para buscar un usuario por ID
    public function buscarPorId($id) {
        $user = new D_User();
        return $user->buscarPorId($id);
    }

    // Método para loguear un usuario
    public function loguear($usuario, $clave) {
        $d = new D_User();
        $usuarioDb = $d->buscarPorUsuario($usuario);  // Método para buscar el usuario en base al nombre de usuario

        if ($usuarioDb) {
            // Encriptar la contraseña ingresada con SHA-256
            $claveEncriptada = $this->hashSHA256($clave);

            // Comparar la contraseña encriptada
            return $claveEncriptada === $usuarioDb['clave'];  // Verifica si la contraseña ingresada coincide
        }
        return false;
    }

    // Método para generar el hash SHA-256
    private function hashSHA256($input) {
        return hash('sha256', $input);
    }
}
?>
