<?php

class FrontController {

    public function start() {
        $uri = $_SERVER['REQUEST_URI'];   //  Ejemplo:  www.sistema.local/admin/home
        $datos = explode('/', $uri);
        array_shift($datos);  // quitar el primer elemento del arreglo
        if (count($datos) >= 2) {
            $modulo = $datos[0];
            $recurso = $datos[1];
        } else {
            $modulo = DEFAULT_MODULE;
            $recurso = DEFAULT_RESOURCE;
        }
        $argumentos = array();
        for ($i = 2; $i < count($datos); $i++) {
            $argumentos[] = $datos[$i];
        }

        $datos = explode('_', $recurso);
        $recurso = $datos[0];
        for ($i = 1; $i < count($datos); $i++) {
            $recurso .= ucwords($datos[$i]);
        }

        $className = ucwords($modulo) . "Controller";
        $ruta = "./modules/" . $modulo . "/" . $className . ".php";
        if (file_exists($ruta)) {
            require_once $ruta;
            $controller = new $className($recurso, $argumentos);
        } else {
            echo "El recurso no existe. ";
        }
    }

}
