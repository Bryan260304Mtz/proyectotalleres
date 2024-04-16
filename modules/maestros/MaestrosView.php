<?php

require_once "./core/Template.php";

class MaestrosView {

    public function home($cursos, $persona) {
        $contenido = file_get_contents("./public/html/maestros/home.html");
        
        $template = new Template($contenido);
        $contenido = $template->render_regex($cursos, "LISTA_CURSOS"); //renderizar tablas

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        echo $contenido;

	}
}