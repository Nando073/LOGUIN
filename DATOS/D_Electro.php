<?php

class D_Electro {
    private $id;
    private $nombre;
    private $modelo;
    private $marca;
    private $precio;
    private $garantia;
    private $con;
    private $ps;

    // Constructor por defecto
    public function __construct() {
        $this->id = 1;
        $this->nombre = "Batidora";
        $this->modelo = "jh7";
        $this->marca = "LG";
        $this->precio = 150;
        $this->garantia = "3 meses";
        
        $this->con = (new D_coneccion())->Conectar(); // Inicializar conexión
    }

    // Constructor por parámetros
    public function __constructWithParams($id, $nombre, $modelo, $marca, $precio, $garantia) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->modelo = $modelo;
        $this->marca = $marca;
        $this->precio = $precio;
        $this->garantia = $garantia;
        
        $this->con = (new D_coneccion())->Conectar(); // Inicializar conexión
    }

    // Método toString
    public function __toString() {
        return "D_Electrodomesticos{id={$this->id}, nombre={$this->nombre}, modelo={$this->modelo}, marca={$this->marca}, precio={$this->precio}, garantia={$this->garantia}}";
    }

    // Getters y Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function setModelo($modelo) {
        $this->modelo = $modelo;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function setMarca($marca) {
        $this->marca = $marca;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getGarantia() {
        return $this->garantia;
    }

    public function setGarantia($garantia) {
        $this->garantia = $garantia;
    }

    // Método para adicionar un electrodoméstico
public function Adicionar($id, $nombre, $modelo, $marca, $precio, $garantia) {
    $sql = "CALL AdicionarElectrodomestico(?, ?, ?, ?, ?, ?)";
    try {
        $ps = $this->con->prepare($sql);
        $ps->execute([
            $id, // El id se pasa como entero
            $nombre,
            $modelo,
            $marca,
            $precio,
            $garantia
        ]);
        echo "Electrodoméstico registrado correctamente.";
    } catch (PDOException $ex) {
        echo "Error al registrar: " . $ex->getMessage();
    }
}



    // Método para obtener todos los electrodomésticos
    public function BuscarTodo() {
        $sql = "CALL GetAllElectrodomesticos2()";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute();
            $result = $ps->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: []; // Retorna un arreglo vacío si no hay resultados
        } catch (PDOException $ex) {
            echo "Error al buscar: " . $ex->getMessage();
            return [];
        }
    }

    // Método para eliminar un electrodoméstico por ID
    public function Eliminar($id) {
        $sql = "CALL DeleteElectrodomestico(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id]);
            echo "El electrodoméstico fue eliminado correctamente.";
        } catch (PDOException $ex) {
            echo "Error al eliminar: " . $ex->getMessage();
        }
    }

    // Método para buscar electrodomésticos por similitud
    public function buscarPorSimilitud($termino) {
        $sql = "CALL SearchElectrodomesticos2(?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$termino]);
            return $ps->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Error al buscar: " . $ex->getMessage();
            return [];
        }
    }

    // Método para modificar un electrodoméstico
    public function modificar($id, $nombre, $modelo, $marca, $precio, $garantia) {
        $sql = "CALL UpdateElectrodomestico2(?, ?, ?, ?, ?, ?)";
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id, $nombre, $modelo, $marca, $precio, $garantia]);
            echo "Electrodoméstico actualizado correctamente.";
        } catch (PDOException $ex) {
            echo "Error al actualizar: " . $ex->getMessage();
        }
    }

    // Método para buscar un electrodoméstico por ID
    public function buscarPorId($id) {
        $sql = "CALL buscarPorId(?)"; // Llamamos al procedimiento almacenado con un parámetro
        try {
            $ps = $this->con->prepare($sql);
            $ps->execute([$id]);
            return $ps->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al ejecutar el procedimiento: " . $e->getMessage();
            return null;
        }
    }
}

?>
