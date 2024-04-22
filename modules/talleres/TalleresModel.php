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
    public function darTallerista()
    {

        $this->query = "SELECT talleristas.*, persona.nombre, persona.apellidopat, persona.apellidomat
        FROM talleristas
        INNER JOIN profesor ON talleristas.idprofesor = profesor.idprofesor
        INNER JOIN persona ON profesor.idpersonas = persona.idpersonas
        WHERE talleristas.idtalleristas = talleristas.idtalleristas 
        ORDER BY idtalleristas DESC";

        $this->get_query();
        return $this->rows;
    }
    public function guardarTallerista($idProfesor)
    {
        $this->query = "INSERT INTO talleristas (idprofesor) VALUES ($idProfesor)";
        $this->set_query();
    }
}
