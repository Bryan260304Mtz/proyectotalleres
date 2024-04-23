<?php

// incluir el archivo de configuración
require_once "./core/DB.php";

class MaestrosModel extends DB
{


    public function darTallerista()
    {

        $this->query = "SELECT nombreCompleto(persona.idpersonas) AS 'nombre_profesor'
        FROM persona
        WHERE persona.profesor = 1 AND persona.profesor_activo = 1 
        ORDER BY nombre_profesor ";
        $this->get_query();
        return $this->rows;
    }
    public function guardarTallerista()
    {
    }
}
