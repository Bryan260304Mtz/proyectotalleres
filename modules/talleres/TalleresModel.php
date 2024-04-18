<?php

// incluir el archivo de configuración
require_once "./core/DB.php";

class TalleresModel extends DB
{
	
	public function actualizar($idtalleres, $nombre, $imagen)
    {
        $this->rows = array();
        $this->query = "UPDATE talleres 
		                SET nombre = '$nombre', imagen = '$imagen' 
		                WHERE talleres.idtalleres = $idtalleres ";
        $this->set_query();
    }

    public function darTalleres()
    {
        $this->rows = array();
        $this->query = "SELECT * FROM talleres WHERE idtalleres ORDER BY idtalleres DESC";
        $this->get_query();
        return $this->rows;
    }

    public function cambiarEstado($idTalleres) {
        // Preparar la consulta para cambiar el estado del taller
        $query = "UPDATE talleres SET estado = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE idtalleres = :idTaller";

       
        // Verificar si la consulta fue exitosa
        if ($stmt->rowCount() > 0) {
            // La consulta se ejecutó con éxito
            return true;
        } else {
            // La consulta no se ejecutó correctamente
            return false;
        }
    }

    public function crear($nombre, $estado, $imagen)
    {
        $this->query = "INSERT INTO talleres (nombre, estado, imagen) VALUES ( '$nombre', $estado, '$imagen')";
        $this->set_query();
    }
}
