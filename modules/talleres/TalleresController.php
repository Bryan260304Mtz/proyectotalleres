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
			if ($_FILES["imagen"]["size"] > 2097152) { // 2 MB en bytes
				if ($uploadOk == 0) {
					echo "<script>alert('Lo siento, tu archivo sobrepasa los 2 MB.');";
					echo "window.location.href = 'http://www.talleres.local/talleres/crear';</script>";
					exit(); // Asegurar que se detenga la ejecución después de mostrar la alerta y redirigir
				} 
				$uploadOk = 0;
			}

			if (
				$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif"
			) {
				$uploadOk = 0;
			}

			if ($uploadOk == 0) {
				echo "<script>alert('Lo siento, tu archivo no fue soportado.');";
				echo "window.location.href = 'http://www.talleres.local/talleres/crear';</script>";
				exit(); // Asegurar que se detenga la ejecución después de mostrar la alerta y redirigir
			} else {
				if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $file_name)) {
					$arrayImg = explode("/", $file_name);
					$nomImg = $arrayImg[count($arrayImg) - 1];
					$talleresModel = new TalleresModel();
					$talleresModel->crear($_POST["nombre"], 1, $nomImg);

					header("Location: http://www.talleres.local/talleres/crear");
					exit(); 
				} else {
					echo "Lo siento, hubo un error al subir tu archivo.";
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

			if ($_FILES["imagen"]["size"] > 2097152) { // 2 MB en bytes
				if ($uploadOk == 0) {
					echo "<script>alert('Lo siento, tu archivo sobrepasa los 2 MB.');";
					echo "window.location.href = 'http://www.talleres.local/talleres/crear';</script>";
					exit(); // Asegurar que se detenga la ejecución después de mostrar la alerta y redirigir
				} 
				$uploadOk = 0;
			}

			if (
				$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif"
			) {
				$uploadOk = 0;
			}

			if ($uploadOk == 0) {
				echo "<script>alert('Lo siento, tu archivo no fue soportado.');";
				echo "window.location.href = 'http://www.talleres.local/talleres/crear';</script>";
				exit(); // Asegurar que se detenga la ejecución después de mostrar la alerta y redirigir
			} else {
				if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $file_name)) {
					$arrayImg = explode("/", $file_name);
					$nomImg = $arrayImg[count($arrayImg) - 1];
					$talleresModel = new TalleresModel();
					$talleresModel->actualizar($idtalleres, $_POST["nombre"], $nomImg);
					// Redireccionar a la página donde está el botón "Crear un taller"
					header("Location: http://www.talleres.local/talleres/crear");
					exit(); 
				} else {
					header("Location: http://www.talleres.local/talleres/crear");
					exit(); 
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
        $idProfesor = $_POST['idprofesor'];
        
        // Verificar si el profesor ya es tallerista
        $talleresModel = new TalleresModel();
        $existeTallerista = $talleresModel->verificarTalleristaExistente($idProfesor);

        // Si el profesor ya es tallerista, mostrar un mensaje de error y redireccionar
        if ($existeTallerista) {
				echo "<script>alert('Este profesor ya es tallerista.');";
				echo "window.location.href = 'http://www.talleres.local/talleres/tallerista';</script>";
				exit(); 
		}
				else {
            // Si el profesor no es tallerista, guardarlo en la base de datos
            $talleresModel->guardarTallerista($idProfesor);
            header("Location: http://www.talleres.local/talleres/tallerista");
            exit();
        }
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
		$tallerista = $talleresModel->darTalleristaActivo();

		$talleresModel = new TalleresModel();
		$talleres = $talleresModel->darTallerActivo();

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

        // Validación del valor de cupo
        if ($cupo < 1 || $cupo > 40) {
            // Manejar el error, por ejemplo, redirigiendo a la página anterior con un mensaje de error
           header("Location: http://www.talleres.local/talleres/crearGrupoTaller");
		   exit();
	   }
        
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

		$totalHorarios = $talleresModel->contarHorariosSeleccionados($idgrupo_talleres);

    if ($totalHorarios >= 8) {
        echo "<script>
            alert('No puedes seleccionar más de 8 horarios.');
            window.location.href = '/talleres/horario/{$idgrupo_talleres}';
        </script>";
        exit();
    }

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
            "dia1" => "<a href='/talleres/horarios_guardar/$idGrupo/1/$i:00:00' class='estado dia1'>" . ((new TalleresModel())->estaOcupada($idGrupo, 1, $i) ? "✓" : "O") . "</a>",
            "dia2" => "<a href='/talleres/horarios_guardar/$idGrupo/2/$i:00:00' class='estado dia2'>" . ((new TalleresModel())->estaOcupada($idGrupo, 2, $i) ? "✓" : "O") . "</a>",
            "dia3" => "<a href='/talleres/horarios_guardar/$idGrupo/3/$i:00:00' class='estado dia3'>" . ((new TalleresModel())->estaOcupada($idGrupo, 3, $i) ? "✓" : "O") . "</a>",
            "dia4" => "<a href='/talleres/horarios_guardar/$idGrupo/4/$i:00:00' class='estado dia4'>" . ((new TalleresModel())->estaOcupada($idGrupo, 4, $i) ? "✓" : "O") . "</a>",
            "dia5" => "<a href='/talleres/horarios_guardar/$idGrupo/5/$i:00:00' class='estado dia5'>" . ((new TalleresModel())->estaOcupada($idGrupo, 5, $i) ? "✓" : "O") . "</a>"
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

	public function guardarHorarioAlumno($idHorarioTaller)
	{
		if (is_array($idHorarioTaller) && count($idHorarioTaller) > 0) {
			$idHorarioTaller = $idHorarioTaller[0];
		}

		$talleresModel = new TalleresModel();
		$personaModel = new PersonaModel();
		$noCuenta = $personaModel->darMatricula1(336);

		$registrado = $talleresModel->verificarHorarioRegistrado($noCuenta, $idHorarioTaller);

		if ($registrado) {
			$talleresModel->eliminarHorarioAlumno($noCuenta, $idHorarioTaller);
		} else {
			$talleresModel->horariosAlumnoGuardar($noCuenta, $idHorarioTaller);
		}
		
		$id_horario = $talleresModel->obtenerIdGrupoTalleres($idHorarioTaller);
		header("Location: /talleres/horarioAlumno/$id_horario");
		exit();
	}


	private function darHorarioAlumno($idGrupo)
	{
		$horarios = array();

		$talleresModel = new TalleresModel();
		$personaModel = new PersonaModel();
		$noCuenta = $personaModel->darMatricula1(336);

		for ($i = 7; $i < 18; $i++) {
			$horarioDia = array(
				"horas" => $i . " - " . ($i + 1),
				"dia1" => "",
				"dia2" => "",
				"dia3" => "",
				"dia4" => "",
				"dia5" => ""
			);

			for ($dia = 1; $dia <= 5; $dia++) {
				$idHorarioTaller = $talleresModel->obtenerIdHorario($idGrupo, $dia, $i);
				$ocupado = $talleresModel->estaOcupada($idGrupo, $dia, $i);

				if ($idHorarioTaller && $ocupado) {
					$registrado = $talleresModel->verificarHorarioRegistrado($noCuenta, $idHorarioTaller);
					if ($registrado) {
						$horarioDia["dia$dia"] = "<a href='/talleres/guardarHorarioAlumno/$idHorarioTaller'>✓</a>";
					} else {
						$horarioDia["dia$dia"] = "<a href='/talleres/guardarHorarioAlumno/$idHorarioTaller'>O</a>";
					}
				} else {
					$horarioDia["dia$dia"] = $ocupado ? "O" : "";
				}
			}

			$horarios[] = $horarioDia;
		}

		return $horarios;
	}


	public function horarioSeleccion($param = array())
	{
	
		if (count($param) > 0) {
			$idGrupo = $param[0];
			$talleresModel = new TalleresModel();
			$grupo_talleres = $talleresModel->darGrupo($idGrupo);

			$personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(336);
			$noCuenta = $personaModel->darMatricula(336);
			$horarios = $this->darHorarioSeleccionAlumno($idGrupo);

			(new TalleresView())->tallerInscritoAlumno($persona, $horarios, $grupo_talleres, $noCuenta);
		} else {
			
		}
	}


	private function darHorarioSeleccionAlumno($idGrupo, $soloElegidos = false) {
		$horarios = array();
	
		$talleresModel = new TalleresModel();
		$personaModel = new PersonaModel();
		$noCuenta = $personaModel->darMatricula1(336);
	
		for ($i = 7; $i < 18; $i++) {
			$horarioDia = array(
				"horas" => $i . " - " . ($i + 1),
				"dia1" => "",
				"dia2" => "",
				"dia3" => "",
				"dia4" => "",
				"dia5" => ""
			);
	
			for ($dia = 1; $dia <= 5; $dia++) {
				$idHorarioTaller = $talleresModel->obtenerIdHorario($idGrupo, $dia, $i);
				$ocupado = $talleresModel->estaOcupada($idGrupo, $dia, $i);
	
				if ($idHorarioTaller && $ocupado) {
					$registrado = $talleresModel->verificarHorarioRegistrado($noCuenta, $idHorarioTaller);
					if ($registrado) {
						$horarioDia["dia$dia"] = "S";
					} else if (!$soloElegidos) {
						$horarioDia["dia$dia"] = "";
					}
				}
			}
	
			$horarios[] = $horarioDia;
		}
	
		return $horarios;
	}
	public function horarioSeleccionAlumno($param = array()) {
		if (count($param) > 0) {
			$idGrupo = $param[0];
			$talleresModel = new TalleresModel();
			$grupo_talleres = $talleresModel->darGrupo($idGrupo);
	
			$personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(336);
			$matriculaAlumno = $personaModel->darMatricula(336);
	
			// Pasar true para mostrar solo los horarios elegidos
			$horarios = $this->darHorarioAlumno($idGrupo, true);
	
			(new TalleresView())->horarioAlumno($persona, $grupo_talleres, $horarios, $matriculaAlumno);
		} else {
			exit(0);
		}
	}

	public function verGrupoTaller()
	{
		$talleresModel = new TalleresModel();
		$grupoTalleres = $talleresModel->darGrupoTallerActivo();

		$mensaje = "";
		$hayTalleres = !empty($grupoTalleres);
		if (!$hayTalleres) {
			$mensaje = "No hay talleres disponibles.";
		}

		$personaModel = new PersonaModel();
		$persona = $personaModel->darPersona(336);

		$talleresView = new TalleresView();
		$talleresView->verGrupoTaller($persona, $grupoTalleres, $mensaje, $hayTalleres);
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
