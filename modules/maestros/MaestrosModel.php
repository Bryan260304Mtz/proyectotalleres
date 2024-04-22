<?php

// incluir el archivo de configuración
require_once "./core/DB.php";

class MaestrosModel extends DB {

    
    public function darTallerista()
    {
      
        $this->query = "SELECT profesor.*, persona.apellidopat, persona.apellidomat, persona.nombre 
        FROM profesor
        INNER JOIN persona ON profesor.idprofesor = persona.idpersonas";
        $this->get_query();
        return $this->rows;

    }
}