<?php

require_once "./core/Template.php";

class TalleresView
{

    public function crear($talleres, $persona)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-crear.html");

        $template = new Template($contenido);
        $contenido = $template->render_regex($talleres, "LISTA_TALLERES"); //renderizar tablas

        $template = new Template($contenido);
        $contenido = $template->render_regex($talleres, "ACTUALIZAR_TALLERES"); //renderizar tablas

        $template = new Template($contenido);
        $contenido = $template->render($persona, $talleres);

        echo $contenido;
    }

    public function tallerista($persona, $docente, $tallerista)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-tallerista.html");

        $template = new Template($contenido);
        $contenido = $template->render_regex($docente, "LISTA_PROFESORES");

        $template = new Template($contenido);
        $contenido = $template->render_regex($tallerista, "LISTA_TALLERISTAS");

        $template = new Template($contenido);
        $contenido = $template->render($persona, $docente, $tallerista);
        echo $contenido;
    }

    public function crearGrupoTaller($persona, $tallerista, $talleres, $periodo, $grupo_talleres)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-grupo.html");

        $template = new Template($contenido);
        $contenido = $template->render_regex($tallerista, "LISTA_TALLERISTAS");

        $template = new Template($contenido);
        $contenido = $template->render_regex($talleres, "LISTA_TALLERES");

        $template = new Template($contenido);
        $contenido = $template->render_regex($periodo, "PERIODO");

        $template = new Template($contenido);
        $contenido = $template->render_regex($grupo_talleres, "GRUPO_TALLERES");


        $template = new Template($contenido);
        $contenido = $template->render($persona, $tallerista, $talleres, $periodo, $grupo_talleres);
        echo $contenido;
    }
    public function horario($persona, $grupo_talleres, $horarios)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-horario.html");

        $template = new Template($contenido);
        $contenido = $template->render($grupo_talleres);

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        $template = new Template($contenido);
        $contenido = $template->render_regex($horarios, "HORARIOS");

        echo $contenido;
    }
    public function verGrupoTaller($persona, $grupo_talleres, $mensaje, $hayTalleres)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-alumnos.html");

        if (!$hayTalleres) {
            $contenido = str_replace("<!--MENSAJE-->", "<p>$mensaje</p>", $contenido);
            // Remover la tabla completamente
            $contenido = preg_replace("/<!--TABLA-->(.*?)<!--FIN_TABLA-->/s", "", $contenido);
        } else {
            $contenido = str_replace("<!--MENSAJE-->", "", $contenido);
            $template = new Template($contenido);
            $contenido = $template->render_regex($grupo_talleres, "GRUPO_TALLERES");
        }

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        echo $contenido;
    }


    public function horarioAlumno($persona, $grupo_talleres, $horarios, $matriculaAlumno)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-horario-alumnos.html");

        $template = new Template($contenido);
        $contenido = $template->render($grupo_talleres);

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        $template = new Template($contenido);
        $contenido = $template->render($matriculaAlumno);

        $template = new Template($contenido);
        $contenido = $template->render_regex($horarios, "HORARIOS");

        echo $contenido;
    }
    public function tallerInscritoAlumno($persona, $horarios, $grupo_talleres, $matriculaAlumno)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-inscrito-alumno.html");

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        $template = new Template($contenido);
        $contenido = $template->render($grupo_talleres);

        $template = new Template($contenido);
        $contenido = $template->render($matriculaAlumno);

        $template = new Template($contenido);
        $contenido = $template->render_regex($horarios, "HORARIOS");

        echo $contenido;
    }
    public function asistencia($persona, $horariosGrupoTaller)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-asistencia.html");
    
        // Generar la lista de opciones para los horarios
        $listaHorarios = "";
        foreach ($horariosGrupoTaller as $horarioTall) {
            $listaHorarios .= "<option value='{$horarioTall}'>{$horarioTall}</option>";
        }
    
        // Reemplazar el marcador de lista de horarios con las opciones generadas
        $contenido = str_replace("<!--LISTA_HORARIOS-->", $listaHorarios, $contenido);
    
        // Renderizar el contenido con la información del tallerista
        $template = new Template($contenido);
        $contenido = $template->render($persona);
    
        echo $contenido;
    }
    
    public function ListaAsiStencia($persona, $listaAsistencia)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-ListaAsistencia.html");
        $template = new Template($contenido);
        $contenido = $template->render($persona);
    
        // Agregar el código para mostrar la lista de asistencia
        $tablaAsistencia = '<table><thead><tr><th>Nombre Completo</th></tr></thead><tbody>';
        foreach ($listaAsistencia as $estudiante) {
            $tablaAsistencia .= '<tr><td>' . $estudiante['nombreCursan'] . '</td></tr>';
        }
        $tablaAsistencia .= '</tbody></table>';
    
        // Insertar la tabla en el contenido renderizado
        $contenido = str_replace('{tabla_asistencia}', $tablaAsistencia, $contenido);
    
        echo $contenido;
    }
    
    
}
