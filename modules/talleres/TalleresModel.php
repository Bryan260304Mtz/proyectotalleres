<?php

// incluir el archivo de configuración
require_once "./core/DB.php";

class TalleresModel extends DB {

    public function darTalleres() {
        $this->rows = array();
       $this->query= "SELECT * FROM talleres WHERE idtalleres ORDER BY idtalleres DESC";
        $this->get_query();
		return $this->rows;
	
	}
    public function crear($nombre, $estado, $imagen)
	{
		$this->query = "INSERT INTO talleres (nombre, estado, imagen) VALUES ( '$nombre', $estado, '$imagen')"; 
		$this->set_query();
	}

	/*public function actualizar($item){
        $consulta = "UPDATE inventarios SET producto=:producto, stock=:stock
        WHERE folio=:folio";
        $query = $this->db->connect()->prepare($consulta);
        try{
            $query->execute([
                'folio'=>$item['folio'],
                'producto'=>$item['producto'],
                'stock'=>$item['stock']]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }
*/}