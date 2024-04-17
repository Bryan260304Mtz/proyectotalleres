<?php
require_once "./core/handlers.php";
require_once "talleresview.php";
require_once "TalleresModel.php";
require_once "./modules/persona/PersonaModel.php";

class TalleresController
{
    public function __construct($metodo, $parametros)
    {
        if (method_exists($this, $metodo)) {
            call_user_func(array($this, $metodo), $parametros);
        } else {
            echo "El método no existe: " . $metodo;
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

            $talleresModel = new TalleresModel();
            $tallerExistente = $talleresModel->darTalleres($_POST["nombre"]);
            if ($tallerExistente) {
                // Mostrar ventana emergente indicando que el taller ya existe
                echo "<script>alert('El taller ya existe');</script>";
                $uploadOk = 0;
            }

            if ($_FILES["imagen"]["size"] > 500000) {
                $uploadOk = 0;

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
    }



    public function crear()
    {
        $talleresView = new TalleresView();
        $talleresView->crear();

        $talleresModel = new TalleresModel();
        $talleres = $talleresModel->darTalleres();

        $personaModel = new PersonaModel();
        $persona = $personaModel->darPersona(15);

        $talleresView = new TalleresView();
        $talleresView->home($talleres, $persona);
    }

    public function home()
    {
        /* $talleresModel = new TalleresModel();
        $cursos = $talleresModel->darCursos();

        $personaModel = new PersonaModel();
        $persona = $personaModel->darPersona(25);
        //escribeMatriz($persona);
        // echo "Hola Soy Bryan";
        $talleresView = new TalleresView();
        $talleresView->home($cursos, $persona);*/
    }
}
