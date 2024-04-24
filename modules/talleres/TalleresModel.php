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

    public function cambiarEstado($idTalleres)
    {
        $this->query = "UPDATE talleres SET estado = !estado WHERE idtalleres = $idTalleres ";
        $this->set_query();
    }

    public function crear($nombre, $estado, $imagen)
    {
        $this->query = "INSERT INTO talleres (nombre, estado, imagen) VALUES ( '$nombre', $estado, '$imagen')";
        $this->set_query();
    }
    public function darProfesor()
    {

        $this->query = "SELECT persona.idpersonas, nombreCompleto(persona.idpersonas) AS 'nombre_profesor'
        FROM persona
        WHERE persona.profesor = 1 AND persona.profesor_activo = 1 
        ORDER BY nombre_profesor ";
        $this->get_query();
        return $this->rows;
    }
    public function darTallerista()
    {
        $this->query = "SELECT talleristas.*, nombreCompleto(persona.idpersonas) AS 'nombre_talleristas'
        FROM talleristas
        INNER JOIN persona ON persona.idpersonas =  talleristas.idprofesor 
        ORDER BY nombre_talleristas";
        $this->get_query();
        return $this->rows;
    }
    public function guardarTallerista($idProfesor)
    {
        $this->query = "INSERT INTO talleristas (idprofesor,estado) VALUES ($idProfesor,1)";
        $this->set_query();
    }
}