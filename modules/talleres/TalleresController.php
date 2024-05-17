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
	public function crear()
	{
		$talleresModel = new TalleresModel();
		$talleres = $talleresModel->darTalleres();

		$personaModel = new PersonaModel();
		$persona = $personaModel->darPersona(5);

		$talleresView = new TalleresView();
		$talleresView->crear($talleres, $persona);
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
	public function actualizar()
	{
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
					$talleresModel->actualizar($idtalleres, $_POST["nombre"], $nomImg);
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
	public function cambiarEstado($param = array())
	{

		$talleresModel = new TalleresModel();
		$resultado = $talleresModel->cambiarEstado($param[0]);

		header("Location: http://www.talleres.local/talleres/crear");
		exit();
	}

	public function tallerista()
	{
		$talleresModel = new TalleresModel();
		$docente = $talleresModel->darProfesor();

		$talleresModel = new TalleresModel();
		$tallerista = $talleresModel->darTallerista();

		$personaModel = new PersonaModel();
		$persona = $personaModel->darPersona(5);

		$talleresView = new TalleresView();
		$talleresView->tallerista($persona, $docente, $tallerista);
	}
	public function agregarTallerista()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$idprofesor = $_POST['idprofesor'];
			$talleresModel = new TalleresModel();
			$talleresModel->guardarTallerista($idprofesor);

			header("Location: http://www.talleres.local/talleres/tallerista");
			exit();
		}
	}
	public function cambiarEstadoTallerista($param = array())
	{
		$talleresModel = new TalleresModel();
		$resultado = $talleresModel->cambiarEstadoTallerista($param[0]);


		header("Location: http://www.talleres.local/talleres/tallerista");
		exit();
	}

	public function crearGrupoTaller()
	{

		$talleresModel = new TalleresModel();
		$tallerista = $talleresModel->darTallerista1();

		$talleresModel = new TalleresModel();
		$talleres = $talleresModel->darTalleres1();

		$personaModel = new PersonaModel();
		$persona = $personaModel->darPersona(5);

		$talleresModel = new TalleresModel();
		$periodo = $talleresModel->darPeriodo();

		$talleresModel = new TalleresModel();
		$grupo_talleres = $talleresModel->darGrupoTaller();


		$talleresView = new TalleresView();
		$talleresView->crearGrupoTaller($persona, $tallerista, $talleres, $periodo, $grupo_talleres);
	}
	public function guardarGrupoTaller()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$idtallerista = $_POST['idtallerista'];
			$idtaller = $_POST['idtalleres'];
			$cupo = $_POST['cupo'];
			$idperiodo = $_POST['idperiodo'];
			$talleresModel = new TalleresModel();
			$talleresModel->guardarGrupoTaller($idtallerista, $idtaller, $cupo, $idperiodo);

			header("Location: http://www.talleres.local/talleres/crearGrupoTaller");
			exit();
		}
	}
	public function horario($param = array())
	{

		if (count($param) > 0) {
			$idGrupo = $param[0];
			$talleresModel = new TalleresModel();
			$grupo_talleres = $talleresModel->darGrupo($idGrupo);

			$personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(5);

			$horarios = $this->darHorarios($idGrupo);

			(new TalleresView())->horario($persona, $grupo_talleres, $horarios);
		} else {
			header("Location: /talleres/crear_grupo_taller");
			exit(0);
		}
	}

	public function horariosGuardar($param = array())
	{
		$idgrupo_talleres = $param[0];
		$dia = $param[1];
		$hora = $param[2];
		$talleresModel = new TalleresModel();

		$horario_Ocupado = $talleresModel->horarioOcupado($idgrupo_talleres, $dia, $hora);

    if ($horario_Ocupado == 1) {
        echo "<script>
            if (confirm('Seguro que quieres eliminar este grupo. Hay Alumnos registrados')) {
                // Si el usuario confirma, redirigir a la confirmación de eliminación
                window.location.href = '/talleres/eliminarHorarioScript/{$idgrupo_talleres}/{$dia}/{$hora}';
            } else {
                // Si el usuario cancela, redirigir a la página principal
                window.location.href = '/talleres/horario/{$idgrupo_talleres}';
            }
        </script>";
        exit();
    } else if ($talleresModel->estaOcupada($idgrupo_talleres, $dia, $hora)) {
        $talleresModel->eliminarHorario($idgrupo_talleres, $dia, $hora);
    } else {
        $talleresModel->horariosGuardar($idgrupo_talleres, $dia, $hora);
    }
    header("Location: /talleres/horario/$idgrupo_talleres");
    exit();
}
	public function eliminarHorarioScript($param = array())
	{
		$idgrupo_talleres = $param[0];
		$dia = $param[1];
		$hora = $param[2];
		$talleresModel = new TalleresModel();
		$talleresModel->eliminarHorario($idgrupo_talleres, $dia, $hora);
		header("Location: /talleres/horario/$idgrupo_talleres");
		exit();
	}

	private function darHorarios($idGrupo)
	{
		$horarios = array();

		for ($i = 7; $i < 18; $i++) {
			$horarios[] = [
				"horas" => $i . " - " . ($i + 1),
				"dia1" => "<a href='/talleres/horarios_guardar/$idGrupo/1/$i:00:00' > " . ((new TalleresModel())->estaOcupada($idGrupo, 1, $i) ? "H" : "X") . " </a>",
				"dia2" => "<a href='/talleres/horarios_guardar/$idGrupo/2/$i:00:00' > " . ((new TalleresModel())->estaOcupada($idGrupo, 2, $i) ? "H" : "X") . " </a>",
				"dia3" => "<a href='/talleres/horarios_guardar/$idGrupo/3/$i:00:00' > " . ((new TalleresModel())->estaOcupada($idGrupo, 3, $i) ? "H" : "X") . " </a>",
				"dia4" => "<a href='/talleres/horarios_guardar/$idGrupo/4/$i:00:00' > " . ((new TalleresModel())->estaOcupada($idGrupo, 4, $i) ? "H" : "X") . " </a>",
				"dia5" => "<a href='/talleres/horarios_guardar/$idGrupo/5/$i:00:00' > " . ((new TalleresModel())->estaOcupada($idGrupo, 5, $i) ? "H" : "X") . " </a>"
			];
		}
		return $horarios;
	}

	public function horarioAlumno($param = array())
	{

		if (count($param) > 0) {
			$idGrupo = $param[0];
			$talleresModel = new TalleresModel();
			$grupo_talleres = $talleresModel->darGrupo($idGrupo);



			$personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(336);
			$matriculaAlumno = $personaModel->darMatricula(336);

			$horarios = $this->darHorarioAlumno($idGrupo);

			(new TalleresView())->horarioAlumno($persona, $grupo_talleres, $horarios, $matriculaAlumno);
		} else {
			
			exit(0);
		}
	}

	public function horariosGuardarAlumno($param = array())
	{
		$idgrupo_talleres = $param[0];
		$dia = $param[1];
		$hora = $param[2];
		$talleresModel = new TalleresModel();

		$talleresModel->estaOcupada($idgrupo_talleres, $dia, $hora);

		header("location: /talleres/horario/$idgrupo_talleres");
		exit();
	}


	private function darHorarioAlumno($idGrupo)
{
		$horarios = array();
	
		for ($i = 7; $i < 18; $i++) {
			$horarioDia = array(
				"horas" => $i . " - " . ($i + 1),
				"dia1" => "",
				"dia2" => "",
				"dia3" => "",
				"dia4" => "",
				"dia5" => ""
			);
	
			// Verificar ocupación para cada día de la semana
			for ($dia = 1; $dia <= 5; $dia++) {
				$ocupado = (new TalleresModel())->estaOcupada($idGrupo, $dia, $i);
				$horarioDia["dia$dia"] = $ocupado ? "H" : "";
			}
	
			$horarios[] = $horarioDia;
		}
	
		return $horarios;
	
}



	public function verGrupoTaller()
	{
		$talleresModel = new TalleresModel();
		$grupoTalleres = $talleresModel->darGrupoTallerActivo();

		$personaModel = new PersonaModel();
		$persona = $personaModel->darPersona(336);

		$talleresView = new TalleresView();
		$talleresView->verGrupoTaller($persona, $grupoTalleres);
	}

	public function eliminarGrupoTaller($param = array())
	{
		$idgrupo_talleres = $param[0];
		$talleresModel = new TalleresModel();

		$horarios_registrados = $talleresModel->consultarHorariosPorGrupo($idgrupo_talleres);

		if ($horarios_registrados == 1) {
			echo "<script>
						if(confirm('Seguro que quieres eliminar este grupo. Hay Alumnos registrados')) {
							// Si el usuario confirma, redirigir a la confirmación de eliminación
							window.location.href = '/talleres/eliminargrupoScrip/{$idgrupo_talleres}';
						} else {
							// Si el usuario cancela, redirigir a la página principal
							window.location.href = '/talleres/crearGrupoTaller';
						}
					  </script>";
		} else if ($horarios_registrados == 0) {
			$talleresModel->eliminarGrupoTaller($idgrupo_talleres);
			header("Location: /talleres/crearGrupoTaller");
			exit();
		}
	}


	public function eliminargrupoScrip($param = array())
	{
		$idgrupo_talleres = $param[0];
		$talleresModel = new TalleresModel();
		$talleresModel->eliminarGrupoTaller($idgrupo_talleres);
		header("location: /talleres/crearGrupoTaller");
		exit();
	}

	public function home()
	{
	}
}
