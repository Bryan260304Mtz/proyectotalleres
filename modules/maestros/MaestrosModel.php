<?php

// incluir el archivo de configuración
require_once "./core/DB.php";

class MaestrosModel extends DB {

    public function darCursos() {
        $this->rows = array();
		$this->query = "SELECT * FROM curso";
        $this->get_query();
		return $this->rows[0];
	
	}
}