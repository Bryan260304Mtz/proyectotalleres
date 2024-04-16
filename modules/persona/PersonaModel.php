<?php
require_once "./core/DB.php";

class PersonaModel extends DB
{
    public function darPersona($idpersonas)
    {
        $this->rows = array();
        $this->query= "SELECT persona.nombre, persona.apellidomat, persona.apellidopat FROM persona WHERE persona.idpersonas = $idpersonas";
        $this->get_query();
        return $this->rows[0];
    }
}
