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
    public function existeNombre($nombre)
    {
        $this->query = "SELECT CASE 
        WHEN EXISTS ( 
            SELECT 1 
            FROM talleres t 
            WHERE t.nombre= $nombre ) 
            THEN 1 
            ELSE 0 
            END AS resultado";
        $this->get_query();
        return $this->rows[0]['resultado'];
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
    public function verificarTalleristaExistente($idProfesor = null)
    {
    $query = "SELECT talleristas.*, nombreCompleto(persona.idpersonas) AS 'nombre_talleristas'
              FROM talleristas
              INNER JOIN persona ON persona.idpersonas = talleristas.idtallerista";

    if ($idProfesor !== null) {
        $query .= " WHERE talleristas.idtallerista = $idProfesor";
    }
    $query .= " ORDER BY nombre_talleristas";

    $this->query = $query;
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
    public function darTalleristaActivo()
    {
        $this->query = "SELECT talleristas.*, nombreCompleto(persona.idpersonas) AS 'nombre_talleristas'
        FROM talleristas
        INNER JOIN persona ON persona.idpersonas =  talleristas.idtallerista  WHERE talleristas.estado = 1
        ORDER BY nombre_talleristas";
        $this->get_query();
        return $this->rows;
    }
    public function darTallerActivo()
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
    public function guardarGrupoTaller($idtallerista, $idtaller, $cupo, $idperiodo)
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

    public function horarioOcupado($idGrupo, $dia, $hora)
    {
        $horaCompleta = $hora . ':00';
        $this->query = "SELECT CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM cursan_talleres ct 
                JOIN horario_talleres ht ON ct.idhorario_talleres = ht.idhorario_talleres
                WHERE ht.idgrupo_talleres = $idGrupo 
                  AND ht.dia = $dia
                  AND ht.inicio = '$horaCompleta'
            ) THEN 1 
            ELSE 0 
        END AS resultado";

        $this->get_query();
        return $this->rows[0]['resultado'];
    }


    public function estaOcupada($idGrupo, $dia, $hora)
    {
        $this->query = "SELECT * FROM horario_talleres WHERE idgrupo_talleres = $idGrupo AND dia = $dia AND inicio = '$hora:00'";
        $this->get_query();
        if (count($this->rows) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarHorario($idgrupo_talleres, $dia, $hora)
    {
        $query = "DELETE FROM horario_talleres WHERE idgrupo_talleres = $idgrupo_talleres AND dia = $dia AND inicio = '$hora:00'";
        $this->execute_query($query);
    }
    public function darGrupoTallerActivo()
    {
        $this->rows = array();
        $this->query = "SELECT DISTINCT grupo_talleres.*, 
            nombreCompleto(persona.idpersonas) AS 'nombre_talleristas',
            talleres.nombre AS 'nombre_taller',
            periodo.periodo AS 'periodo',
            grupo_talleres.cupo 
            FROM grupo_talleres
            INNER JOIN persona ON persona.idpersonas = grupo_talleres.idtallerista
            INNER JOIN talleres ON talleres.idtalleres = grupo_talleres.idtaller
            INNER JOIN periodo ON periodo.idperiodo = grupo_talleres.idperiodo
            INNER JOIN horario_talleres ON horario_talleres.idgrupo_talleres = grupo_talleres.idgrupo_talleres 
            WHERE talleres.estado = 1
            ORDER BY idgrupo_talleres DESC";
        $this->get_query();
        return $this->rows;
    }

    public function eliminarGrupoTaller($idgrupo_talleres)
    {
        $query = "DELETE FROM grupo_talleres WHERE idgrupo_talleres = $idgrupo_talleres";
        $this->execute_query($query);
    }


    public function consultarHorariosPorGrupo($idgrupo_talleres)
    {

        $this->query = "SELECT CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM horario_talleres ht 
                            JOIN cursan_talleres ct ON ht.idhorario_talleres = ct.idhorario_talleres 
                            WHERE ht.idgrupo_talleres = $idgrupo_talleres
                        ) THEN 1 
                        ELSE 0 
                    END AS resultado";

        $this->get_query();
        return $this->rows[0]['resultado'];
    }
}
