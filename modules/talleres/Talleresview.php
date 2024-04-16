<?php

require_once "./core/Template.php";

class TalleresView
{
    public function crear()
    {
        $contenido = file_get_contents("./public/html/talleres/talleres-crear.html");
    }


    public function home($cursos, $persona)
    {
        // $contenido = file_get_contents("./public/html/talleres.html");
        $contenido = file_get_contents("./public/html/talleres/talleres-crear.html");


        $template = new Template($contenido);
        $contenido = $template->render_regex($cursos, "LISTA_TALLERES");

        $template = new Template($contenido);
        $contenido = $template->render($persona);

        echo $contenido;
    }
}
