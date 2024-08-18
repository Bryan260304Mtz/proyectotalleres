<?php
// incluir el archivo de configuración
require_once "./core/DB.php";

class PersonaModel extends DB
{

	public function darPersona($idpersonas)
	{
		$this->rows = array();
		$this->query = "SELECT CONCAT(persona.nombre, ' ', persona.apellidopat, ' ', persona.apellidomat) AS nombrePersona
		FROM persona 
		WHERE persona.idpersonas = $idpersonas";
		$this->get_query();
		return $this->rows[0];
	}
	public function darMatricula1($idpersonas)
	{
		$this->rows = array();
		$this->query = "SELECT alumno.nocuenta AS matriculaAlumno
						FROM alumno
						JOIN persona ON persona.idpersonas = alumno.idpersonas
						WHERE persona.idpersonas = $idpersonas";
		$this->get_query();
		return isset($this->rows[0]['matriculaAlumno']) ? $this->rows[0]['matriculaAlumno'] : null;
	}
	public function darMatricula($idpersonas)
	{
		$this->rows = array();
		$this->query = "SELECT alumno.nocuenta AS matriculaAlumno
		FROM alumno
		JOIN persona ON persona.idpersonas = alumno.idpersonas
		WHERE persona.idpersonas = $idpersonas";
		$this->get_query();
		return $this->rows[0];
	}


	public function darTallerista($idTallerista)
	{
		$this->rows = array();
		$this->query = "SELECT DISTINCT
    CONCAT(tp.nombre, ' ', tp.apellidopat, ' ', tp.apellidomat) AS nombre_completo_tallerista
	FROM 
    cursan_talleres ct
	INNER JOIN 
    horario_talleres ht ON ct.idhorario_talleres = ht.idhorario_talleres
	INNER JOIN 
    grupo_talleres gt ON ht.idgrupo_talleres = gt.idgrupo_talleres
	INNER JOIN 
    talleristas t ON gt.idtallerista = t.idtallerista
	INNER JOIN 
    alumno a ON ct.nocuenta = a.nocuenta
	INNER JOIN 
    persona p ON a.idpersonas = p.idpersonas
	INNER JOIN 
    persona tp ON t.idtallerista = tp.idpersonas
	WHERE 
    t.idtallerista = $idTallerista";
		$this->get_query();
		return $this->rows[0];
	}
}
