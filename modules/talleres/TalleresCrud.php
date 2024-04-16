<?php
require_once "./core/handlers.php";
require_once "TalleresView.php";
require_once "TalleresModel.php";

class TalleresCrud
{
 
    public function __construct()
    {
        $this->view = new TalleresView();
        $this->model = new TalleresModel();
    }

    public function crearTaller()
    {
        $this->view->crear();
    }

    public function guardarTaller()
    {
        if (isset($_POST["guardar-taller"])) {
            // Lógica para guardar el taller en la base de datos
            $this->model->guardarTaller($_POST);
        } else {
            echo "Error al guardar el taller.";
        }
    }

    public function listarTalleres()
    {
        $talleres = $this->model->listarTalleres();
        $this->view->listar($talleres);
    }

    public function eliminarTaller($id)
    {
        // Lógica para eliminar el taller de la base de datos
        $this->model->eliminarTaller($id);
    }

    public function actualizarTaller($id)
    {
        // Lógica para obtener los datos del taller a actualizar
        $taller = $this->model->obtenerTaller($id);
        // Mostrar el formulario de actualización con los datos del taller
        $this->view->actualizar($taller);
    }

    public function modificarTaller()
    {
        if (isset($_POST["modificar-taller"])) {
            // Lógica para modificar el taller en la base de datos
            $this->model->modificarTaller($_POST);
        } else {
            echo "Error al modificar el taller.";
        }
    }
}

?>
