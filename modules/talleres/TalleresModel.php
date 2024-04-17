<?php
require_once "./core/DB.php";

class talleresModel extends DB
{
    public function darCursos()
    {
        $this->rows = array();
        $this->query= "SELECT * FROM curso";
        $this->get_query();
        return $this->rows;
    }
    public function crear($nombre, $estado, $imagen)
	{
		$this->query = "INSERT INTO talleres (nombre, estado, imagen) VALUES ( '$nombre', $estado, '$imagen')"; 
		$this->set_query();
	}
    

    public function darTalleres()
    {
        $this->rows = array();
        //$this->query= "SELECT * FROM talleres";
        $this->query= "SELECT * FROM talleres ORDER BY idtalleres DESC";

        $this->get_query();
        return $this->rows;
    }
} 
