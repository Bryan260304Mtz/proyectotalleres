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
            // Aquí va tu código para guardar el taller...
            $target_dir = "public/images/talleres/";
            $target_extension = $target_dir . basename($_FILES["Imagen"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_extension, PATHINFO_EXTENSION));
            $file_name = $_POST["nombre"] . "." . $imageFileType;
            $check = getimagesize($_FILES["Imagen"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            if ($_FILES["Imagen"]["size"] > 500000) {
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
                if (move_uploaded_file($_FILES["Imagen"]["tmp_name"], $file_name)) {
                    $talleresModel = new TalleresModel();
                    $nombre = $_POST["nombre"];
                    $imagen = $file_name;
                    $talleresModel->crear($nombre, 1, $imagen);
                    header("Location: http://www.talleres.local/talleres/crear");
                    exit(); 
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "Error al guardar el taller.";
        }
    }
   
    public function modificar()
    {
        if (isset($_POST["modificar-taller"])) {
            $idTaller = $_POST["id_taller"];
            $nombre = $_POST["nombre"];
            $imagen = '';
    
            if ($_FILES["nueva_imagen"]["name"]) {
                $target_dir = "public/images/talleres/";
                $target_file = $target_dir . basename($_FILES["nueva_imagen"]["name"]);
                
                if (move_uploaded_file($_FILES["nueva_imagen"]["tmp_name"], $target_file)) {
                    $imagen = basename($_FILES["nueva_imagen"]["name"]);
                } else {
                    echo "Error al cargar la imagen.";
                    exit(); 
                }
            } else {
                $imagen = $_POST["imagen_actual"];
            }
    
            $talleresModel = new TalleresModel();
            $talleresModel->modificar($idTaller, $nombre, $imagen);
    
            header("Location: http://www.talleres.local/talleres");
            exit();
        } else {
            echo "Error al modificar el taller.";
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
?>
