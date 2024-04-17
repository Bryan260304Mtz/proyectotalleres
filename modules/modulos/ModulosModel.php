<?php
// incluir el archivo de configuración
require_once "./core/DB.php";

class ModulosModel extends DB {

    public function buscarModulos($nocuenta) {
        $this->rows = array();
        $this->query = "SELECT m.* FROM modulo As m INNER JOIN permiso AS p ON p.idmodulo = m.idmodulo"
                . " WHERE p.idusuario = $nocuenta ORDER BY m.menu, m.titulo, m.idmodulo";
        $this->get_query();
        $modulos = array();
        foreach ($this->rows as $key => $value) {
            array_push($modulos,$value);
        }
        return $modulos;
    }
    public function buscarCategoriasModulos($nocuenta) {
        $this->rows = array();
        $this->query = "SELECT DISTINCT(m.menu) FROM modulo As m INNER JOIN permiso AS p ON p.idmodulo = m.idmodulo"
                . " WHERE p.idusuario = $nocuenta ORDER BY m.menu, m.titulo, m.idmodulo";
        $this->get_query();
        $modulos = array();
        foreach ($this->rows as $key => $value) {
            array_push($modulos,$value);
        }
        return $modulos;
    }

}