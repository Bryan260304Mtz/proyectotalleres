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

    public function tallerista($persona, $docente)
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-tallerista.html");
        
        $template = new Template($contenido);
        $contenido = $template->render_regex($docente, "LISTA_TALLERISTA");

        $template = new Template($contenido);
        $contenido = $template->render($persona, $docente);
        echo $contenido;
    }

    public function home($talleres, $persona) {
        /*$contenido = file_get_contents("./public/html/talleres/talleres-crear.html");
        
        $template = new Template($contenido);
        $contenido = $template->render_regex($talleres, "LISTA_TALLERES"); //renderizar tablas

        $template = new Template($contenido);
        $contenido = $template->render($persona, $talleres);

        echo $contenido;

	*/}
}