<?php
require_once __DIR__ . '/../DATOS/D_Electro.php';
require_once __DIR__ . '/../DATOS/D_coneccion.php';

/**
 * Description of N_Electro
 *
 * @author HP
 */
class N_Electro {

    public function adicionar($id, $nombre, $modelo, $marca, $precio, $garantia) {
    // Crear el objeto electrodoméstico para pasar a la capa de datos
    $electrodomestico = new D_Electro();
    // Llamada al método de la capa de datos para insertar el electrodoméstico
    $electrodomestico->Adicionar($id, $nombre, $modelo, $marca, $precio, $garantia);
}

    // Método para buscar todos los electrodomésticos
    public function buscarTodo() {
        $electrodomestico = new D_Electro() ;// Pasamos valores nulos para no inicializar todos los parámetros
        return $electrodomestico->BuscarTodo();
    }

    // Método para eliminar un electrodoméstico por ID
    public function eliminar($id) {
        $electrodomestico = new D_Electro(); // Solo necesitamos el objeto
        $electrodomestico->Eliminar($id);
    }

    // Método para buscar electrodomésticos por similitud de término
    public function buscarPorSimilitud($termino) {
        $electrodomestico = new D_Electro(); // Solo inicializamos con valores nulos
        return $electrodomestico->buscarPorSimilitud($termino);
    }

  public function modificar($id, $nombre, $modelo, $marca, $precio, $garantia) {
    // Crear el objeto electrodoméstico con los nuevos valores
    $electrodomestico = new D_Electro();
    // Llamada al método de modificación
    $electrodomestico->modificar($id, $nombre, $modelo, $marca, $precio, $garantia);  // Corrección aquí
}


    // Método para buscar un electrodoméstico por ID
    public function buscarPorId($id) {
        $electrodomestico = new D_Electro(); // Sin necesidad de valores completos
        return $electrodomestico->buscarPorId($id);
    }
}
?>
