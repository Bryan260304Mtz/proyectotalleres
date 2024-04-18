<?php

require_once 'ModulosModel.php';

class Modulos {

    // funcion para obtner los modulos al los cuales se tiene permiso
    public function menu($idusuario) {
        $modulosModel = new ModulosModel();
        $categorias = $modulosModel->buscarCategoriasModulos($idusuario);
        $modulos = $modulosModel->buscarModulos($idusuario);
        return array("categorias" => $categorias, "modulos" => $modulos);
    }

    
}
