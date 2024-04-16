<?php 
// incluir el archivo de configuración
require_once './settings.php';

abstract class DB {
	private static $db_host = DB_HOST;
	private static $db_user =  DB_USER;
	private static $db_pass = DB_PASS;
	private static $db_name = DB_NAME;
	private static $db_charset = 'utf8';
	private $conn;
	protected $afectadas_rows;
	protected $query;
	protected $rows = array();

	private function db_open() {
		$this->conn = new mysqli(
			self::$db_host,
			self::$db_user,
			self::$db_pass,
			self::$db_name
		);

		$this->conn->set_charset(self::$db_charset);
	}

	private function db_close() {
		$this->conn->close();
	}

	protected function set_query() {
		$this->db_open();
		$this->conn->query($this->query);
                $this->afectadas_rows = $this->conn->affected_rows;
		$this->db_close();
	}

	protected function get_query() {
		$this->db_open();

		$result = $this->conn->query($this->query) ;
		$this->rows = array();
		while( $this->rows[] = $result->fetch_assoc() );
		
		 $result->close();
		 $this->db_close();
		 array_pop($this->rows);
	}
}