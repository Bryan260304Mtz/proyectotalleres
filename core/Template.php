<?php

class Template {

    function __construct($str) {
        $this->template = $str;
    }

    function render($dict) {
        $this->set_dict($dict);
        return str_replace(array_keys($dict), array_values($dict), $this->template);
    }

    function render_regex($dict = array(), $id = '') {
        $regex = "#<!--$id-->([\s\S]*)<!--$id-->#";
        preg_match($regex, $this->template, $matches);

        $strorig = $this->template;
        $this->template = $matches[0];
        $render = '';
        foreach ($dict as $possible_obj) {
            $render .= $this->render($possible_obj);
        }

        $render = str_replace("<!--$id-->", '', $render);

        return str_replace($this->template, $render, $strorig);
    }

    private function set_dict(&$dict) {
        $this->sanear_diccionario($dict);
        $dict2 = $dict;
        foreach ($dict2 as $key => $value) {
            $dict["{{$key}}"] = $value;
            unset($dict[$key]);
        }
    }

    private function sanear_diccionario(&$dict) {
        settype($dict, 'array');
        $dict2 = $dict;
        foreach ($dict2 as $key => $value) {
            if (is_object($value) or is_array($value)) {
                unset($dict[$key]);
            }
        }
    }

    public function renderizarMenuUsuario($categorias, $modulos, $usuario) {
        $this->template = file_get_contents("./public/html/intranet/header.html");
        $this->template = $this->render($usuario);
        $this->template = $this->render_regex($categorias, "MENU_CATEGORIAS");
        $categoria = $modulos[0]["menu"];
        $menuCategorias = array();
        for ($i = 0; $i < count($modulos); $i++) {
            if ($categoria != $modulos[$i]["menu"]) {
                $this->template = $this->render_regex($menuCategorias, "MENU_MODULOS_" . $categoria);
                $categoria = $modulos[$i]["menu"];
                $menuCategorias = array();
            }
            $nombre = explode("/", $modulos[$i]["url"]);
            $n = count($nombre);
            $modulos[$i]["url"] = substr($nombre[$n - 1], 0, -4);
            $modulos[$i]["modulo"] = $n > 1 ? $nombre[$n - 2] : 'intranet';
            $menuCategorias[] = $modulos[$i];
        }
        $this->template = $this->render_regex($menuCategorias, "MENU_MODULOS_" . $categoria);
        return $this->template;
    }

}
