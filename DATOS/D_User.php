<?php
class D_User {
    private $id;
    private $nombre;
    private $usuario;
    private $clave;
    private $con;

    // Constructor
    public function __construct($id = 0, $nombre = "Default Name", $usuario = "Default User", $clave = "Default Password") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->con = (new D_coneccion())->Conectar(); // Inicializar conexión
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getUsuario() { return $this->usuario; }
    public function setUsuario($usuario) { $this->usuario = $usuario; }

    public function getClave() { return $this->clave; }
    public function setClave($clave) { $this->clave = $clave; }

    // Método para adicionar un usuario
    public function Adicionar($id, $nombre, $usuario, $clave) {
        $sql = "CALL AddUsuario(?, ?, ?, ?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id, $nombre, $usuario, $clave]);
            echo "Usuario registrado correctamente.";
        } catch (PDOException $ex) {
            echo "Error al registrar: " . $ex->getMessage();
        }
    }

    // Método para buscar todos los usuarios
    public function BuscarTodo() {
        $sql = "CALL GetAllUsuario()";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute();
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error al buscar: " . $ex->getMessage();
            return [];
        }
    }

    // Método para eliminar un usuario
    public function Eliminar($id) {
        $sql = "CALL DeleteUser(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id]);
            echo "Usuario eliminado correctamente.";
        } catch (PDOException $ex) {
            echo "Error al eliminar: " . $ex->getMessage();
        }
    }

    // Método para buscar usuarios por similitud
    public function buscarPorSimilitud($termino) {
        $sql = "CALL SearchUsuario(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$termino]);
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error al buscar: " . $ex->getMessage();
            return [];
        }
    }

    // Método para modificar un usuario
    public function modificar($id, $nombre, $usuario, $clave) {
        $sql = "CALL UpdateUsuario(?, ?, ?, ?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id, $nombre, $usuario, $clave]);
            echo "Usuario actualizado correctamente.";
        } catch (PDOException $ex) {
            echo "Error al actualizar: " . $ex->getMessage();
        }
    }

    // Método para buscar un usuario por ID
    public function buscarPorId($id) {
        $sql = "CALL buscarPorIdUser(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id]);
            return $ps->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error al buscar por ID: " . $ex->getMessage();
            return null;
        }
    }

    // Método para buscar un usuario por nombre de usuario
    public function buscarPorUsuario($usuario) {
        $sql = "CALL GetUserByUsername(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$usuario]);
            return $ps->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error al buscar usuario por nombre: " . $ex->getMessage();
            return null;
        }
    }

    // Método para eliminar una clave de usuario
    public function eliminarClave($usuario) {
        $sql = "CALL DeletePass(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$usuario]);
            echo "Contraseña eliminada correctamente.";
            return true;
        } catch (PDOException $ex) {
            echo "Error al eliminar la contraseña: " . $ex->getMessage();
            return false;
        }
    }
}
?>
