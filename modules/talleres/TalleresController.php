<?php
	
	require_once "./core/handlers.php";
	require_once "TalleresView.php";
	require_once "TalleresModel.php";
	require_once "./modules/persona/PersonaModel.php";
	
	class TalleresController
	{
		
		function __construct($metodo, $parametros)
		{
			
			if (method_exists($this, $metodo)) {
				call_user_func(array($this, $metodo), $parametros);
				} else {
				echo "El metodo no existe " . $metodo;
			}
			
			
		}
		
		public function actualizar(){
			if (isset($_POST["actualizar-taller"])) {
				$target_dir = "public/images/talleres/";
				$target_name = $target_dir . basename($_FILES["imagen"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_name, PATHINFO_EXTENSION));
				$file_name = $target_dir . $_POST["nombre"] . "." . $imageFileType;
				$idtalleres = $_POST["idtalleres"];
				
				$check = getimagesize($_FILES["imagen"]["tmp_name"]);
				if ($check === false) {
					$uploadOk = 0;
				}
				
				if (file_exists($target_name)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
				
				if ($_FILES["imagen"]["size"] > 500000) {
					$uploadOk = 0;
				}
				
				if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
				) {
					$uploadOk = 0;
				}
				
				if ($uploadOk == 0) {
					header("Location: http://www.talleres.local/talleres/crear");
                    exit(); // Asegurar que se detenga la ejecución después de la redirección
					} else {
					if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $file_name)) {
						$arrayImg = explode("/", $file_name);
						$nomImg = $arrayImg[count($arrayImg) - 1];
						$talleresModel = new TalleresModel();
						$talleresModel->actualizar($idtalleres,$_POST["nombre"], $nomImg);
						// Redireccionar a la página donde está el botón "Crear un taller"
						header("Location: http://www.talleres.local/talleres/crear");
						exit(); // Asegurar que se detenga la ejecución después de la redirección
						} else {
						header("Location: http://www.talleres.local/talleres/crear");
						exit(); // Asegurar que se detenga la ejecución después de la redirección
					}
				}
			}
			
			
		}
		
		
		public function guardar()
		{
			if (isset($_POST["guardar-taller"])) {
				$target_dir = "public/images/talleres/";
				$target_name = $target_dir . basename($_FILES["imagen"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_name, PATHINFO_EXTENSION));
				$file_name = $target_dir . $_POST["nombre"] . "." . $imageFileType;
				
				$check = getimagesize($_FILES["imagen"]["tmp_name"]);
				if ($check === false) {
					$uploadOk = 0;
				}
				
				if (file_exists($target_name)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
				
				if ($_FILES["imagen"]["size"] > 500000) {
					$uploadOk = 0;
				}
				
				if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
				) {
					$uploadOk = 0;
				}
				
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
					} else {
					if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $file_name)) {
						$arrayImg = explode("/", $file_name);
						$nomImg = $arrayImg[count($arrayImg) - 1];
						$talleresModel = new TalleresModel();
						$talleresModel->crear($_POST["nombre"], 1, $nomImg);
						
						// Redireccionar a la página donde está el botón "Crear un taller"
						header("Location: http://www.talleres.local/talleres/crear");
						exit(); // Asegurar que se detenga la ejecución después de la redirección
						} else {
						echo "Sorry, there was an error uploading your file.";
					}
				}
			}
		}
		
		public function cambiarEstado( $param = array() ) {
			
			$talleresModel = new TalleresModel();
			$resultado = $talleresModel->cambiarEstado($param[0]);
			
			header("Location: http://www.talleres.local/talleres/crear");
			exit();
			
		}
		
		public function crear()
		{
			$talleresModel = new TalleresModel();
			$talleres = $talleresModel->darTalleres();
			
			$personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(5);
			
			$talleresView = new TalleresView();
			$talleresView->crear($talleres, $persona);
		}
        public function tallerista()
		{
            $talleresModel = new TalleresModel();
			$docente = $talleresModel->darTallerista();

            $personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(5);

			$talleresView = new TalleresView();
			$talleresView->tallerista($persona, $docente);
		}
		
		/*function actualizarInventario($parametros){
			$idtalleres = $parametros[0];
			//var_dump($folio);
			$nombreN = $_POST['nombre'];
			$imagen = $_POST['imagen'];
			
			if($this->TalleresModel->actualizar(['idtalleres' =>$idtalleres,'nombre' =>$nombreN,'imagen' =>$imagen])){
            //actualizacion correcta
            $talleres= new Talleres;
            $talleres->idtalleres= $idtalleres;
            $talleres->nombre= $nombreN;
            $talleres->imagen= $imagen;
			$this-> view->talleres=$talleres;
			$this->view->mensaje="Inventario actualizado";
			}
			else{
            //error en la actuaizacion
			
            $this->view->mensaje="Ocurrio un error, Inventario no actualizado";
			}
			$this->view->render('consultarinventario/detalles');
			}
			
		*/ public function home()
		{
		}
	}
