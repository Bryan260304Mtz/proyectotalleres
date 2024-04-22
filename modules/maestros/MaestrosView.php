<?php

require_once "./core/Template.php";

class MaestrosView {

    public function home($persona) {
        $contenido = file_get_contents("./public/html/maestros/home.html");
        
        $template = new Template($contenido);
        $contenido = $template->render($persona);

        echo $contenido;

	}
    public function tallerista($persona, $docente)
    {
        $contenido = file_get_contents("./public/html/maestros/home.html");
        
        $template = new Template($contenido);
        $contenido = $template->render_regex($docente, "LISTA_TALLERISTA");

        $template = new Template($contenido);
        $contenido = $template->render($persona, $docente);
        echo $contenido;
    }
}