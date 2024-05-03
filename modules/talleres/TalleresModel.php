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
        INNER JOIN persona ON persona.idpersonas =  talleristas.idtallerista
        ORDER BY nombre_talleristas";
        $this->get_query();
        return $this->rows;
    }
    public function guardarTallerista($idtallerista)
    {
        $this->query = "INSERT INTO talleristas (idtallerista,estado) VALUES ($idtallerista,1)";
        $this->set_query();
    }
    public function cambiarEstadoTallerista($idtallerista)
    {
        $this->query = "UPDATE talleristas SET estado = !estado WHERE idtallerista = $idtallerista ";
        $this->set_query();
    }
    public function darTallerista1()
    {
        $this->query = "SELECT talleristas.*, nombreCompleto(persona.idpersonas) AS 'nombre_talleristas'
        FROM talleristas
        INNER JOIN persona ON persona.idpersonas =  talleristas.idtallerista  WHERE talleristas.estado = 1
        ORDER BY nombre_talleristas";
        $this->get_query();
        return $this->rows;
    }
    public function darTalleres1()
    {
        $this->rows = array();
        $this->query = "SELECT talleres.*, talleres.nombre  AS 'nombre_taller'
        FROM talleres
        WHERE talleres.estado = 1
        ORDER BY nombre_taller ";
        $this->get_query();
        return $this->rows;  
    }
    public function darPeriodo()
    {
        $this->rows = array();
        $this->query = "SELECT periodo.idperiodo, periodo.periodo  AS 'periodo_actual'
        FROM periodo
        WHERE periodo.actual = 1";
        $this->get_query();
        return $this->rows;  
    }
    public function guardarGrupoTaller($idtallerista,$idtaller,$cupo, $idperiodo)
    {
        $this->query = "INSERT INTO grupo_talleres (idtallerista,idtaller,idperiodo,cupo) VALUES ($idtallerista,$idtaller,$idperiodo,$cupo)";
        $this->set_query();
    }
    public function darGrupoTaller()
    {
        $this->rows = array();
        $this->query = "SELECT grupo_talleres.*, 
        nombreCompleto(persona.idpersonas) AS 'nombre_talleristas',
        talleres.nombre AS 'nombre_taller',
        periodo.periodo AS 'periodo',
        grupo_talleres.cupo 
        FROM grupo_talleres
        INNER JOIN persona ON persona.idpersonas =  grupo_talleres.idtallerista
        INNER JOIN talleres ON talleres.idtalleres= grupo_talleres.idtaller
        INNER JOIN periodo ON periodo.idperiodo = grupo_talleres.idperiodo
        ORDER BY idgrupo_talleres DESC";
        $this->get_query();
        return $this->rows;  
    }
    public function darGrupo($idgrupo)
    {
        $this->rows = array();
        $this->query = "SELECT grupo_talleres.*, 
        nombreCompleto(persona.idpersonas) AS 'nombre_talleristas',
        talleres.nombre AS 'nombre_taller',
        periodo.periodo AS 'periodo',
        grupo_talleres.cupo 
        FROM grupo_talleres
        INNER JOIN persona ON persona.idpersonas =  grupo_talleres.idtallerista
        INNER JOIN talleres ON talleres.idtalleres= grupo_talleres.idtaller
        INNER JOIN periodo ON periodo.idperiodo = grupo_talleres.idperiodo
        WHERE grupo_talleres.idgrupo_talleres = $idgrupo
        ORDER BY idgrupo_talleres DESC";
        $this->get_query();
        return $this->rows[0];  
    }
	
	public function horariosGuardar($idgrupo_talleres, $dia, $hora)
    {
        $this->query = "INSERT INTO horario_talleres (idgrupo_talleres, dia, inicio, duracion) VALUES ( $idgrupo_talleres, $dia, '$hora',1)";
        $this->set_query();
    }
}