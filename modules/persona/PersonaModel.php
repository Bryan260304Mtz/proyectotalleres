<?php
// incluir el archivo de configuración
require_once "./core/DB.php";

class PersonaModel extends DB {

	public function darPersona($idpersonas) {
        $this->rows = array();
		$this->query = "SELECT CONCAT(persona.nombre, ' ', persona.apellidopat, ' ', persona.apellidomat) AS nombrePersona
		FROM persona 
		WHERE persona.idpersonas = $idpersonas";
		$this->get_query();
		return $this->rows[0];	
	}
	public function darMatricula($idpersonas) {
        $this->rows = array();
		$this->query = "SELECT alumno.nocuenta AS matriculaAlumno
		FROM alumno
		JOIN persona ON persona.idpersonas = alumno.idpersonas
		WHERE persona.idpersonas = $idpersonas";
		$this->get_query();
		return $this->rows[0];	
	}
}

?>