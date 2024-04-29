<?php

require_once "./core/Template.php";

class TalleresView {

    public function crear($talleres, $persona) {
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

    public function crearGrupoTaller($persona, $tallerista, $talleres, $periodo)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-grupo.html");   
     
       $template = new Template($contenido);
        $contenido = $template->render_regex($tallerista, "LISTA_TALLERISTAS");

        $template = new Template($contenido);
        $contenido = $template->render_regex($talleres, "LISTA_TALLERES");
        
        $template = new Template($contenido);
        $contenido = $template->render_regex($periodo, "PERIODO");

        $template = new Template($contenido);
        $contenido = $template->render($persona, $tallerista, $talleres, $periodo);
        echo $contenido;

    }
    public function home() {
      }
}