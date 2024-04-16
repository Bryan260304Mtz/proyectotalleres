<?php

// se requiere un archivo con metodos de ayuda
require_once 'handlers.php';
require_once "DB.php";

session_start();

class HandlerSession {

    public function check_user_data($tipo) {
        // recoge es una funcion de ayuda para evitar caracteres especiales
        $usuario = recoge("no_usuario");
        $password = recoge("pwd_usuario");
        // si no hay usuario y password se llama a la funcion para destruir session 
        if ($usuario == "" || $password == "") {
            $this->destroy_session();
        } else {
            $db = new DB();
            $mysqli = $db->conectar();

            // se prepara la consulta para traer los datos del usuario
            $consulta = "";

            if ($tipo == USER_ALUMNO)
                $consulta = "SELECT persona.password, persona.alumno_activo, persona.idpersonas"
                        . " FROM alumno INNER JOIN persona"
                        . " ON alumno.idpersonas = persona.idpersonas"
                        . " WHERE alumno.nocuenta = ?  LIMIT 1 ";
            else if ($tipo == USER_DOCENTE)
                $consulta = "SELECT persona.password, persona.profesor_activo, persona.idpersonas "
                        . " FROM profesor INNER JOIN persona "
                        . " ON profesor.idpersonas = persona.idpersonas "
                        . " WHERE persona.idpersonas = ?  LIMIT 1 ";

            if (!($stmt = $mysqli->prepare($consulta))) {
                echo "Falló la preparación: (" . $mysqli->errno . ") " . $mysqli->error;
            }

            if ($tipo == USER_ALUMNO) {
                if (!$stmt->bind_param("s", $usuario))
                    echo "Falló la vinculación de parámetros: (" . $stmt->errno . ") " . $stmt->error;
            } else if ($tipo == USER_DOCENTE) {
                if (!$stmt->bind_param("i", $usuario))
                    echo "Falló la vinculación de parámetros: (" . $stmt->errno . ") " . $stmt->error;
            }

            if (!$stmt->execute()) {
                echo "Falló la ejecución: (" . $stmt->errno . ") " . $stmt->error;
            }
            $stmt->store_result();
            $num = $stmt->num_rows;

            // si el numero de resultados es mayor a 0, se comprueba el password
            if ($num > 0) {
                // obtener los valores de la consulta en las siguientes variables
                $stmt->bind_result($pwd, $estatus, $idpersonas);
                $stmt->fetch();
                //if (password_verify($password, $pwd)) {
                if ($pwd == sha1($password)) {
                    $this->start_session($idpersonas, $tipo);
                } else {
                    // el password es incorrecto
                    $datos = array();
                    if ($tipo == USER_ALUMNO)
                        $datos = array("alumnos", "error");
                    else if ($tipo == USER_DOCENTE)
                        $datos = array("maestros", "error");
                    $this->destroy_session($datos);
                }
            } else {
                // el usuario es incorrecto
                $datos = array();
                if ($tipo == USER_ALUMNO)
                    $datos = array("alumnos", "error");
                else if ($tipo == USER_DOCENTE)
                    $datos = array("maestros", "error");
                $this->destroy_session($datos);
            }
            $stmt->close();
            $mysqli->close();
        }
    }

    // metodo para crear las variables de sesi�n
    function start_session($idpersonas, $rol) {
        $_SESSION['idpersonas'] = $idpersonas;
        $_SESSION['logueado'] = $rol;
        // segun el tipo de usuario se redirecciona al home
        if ($rol == USER_ALUMNO)
            header('Location: /alumnos/index/');
        else if ($rol == USER_DOCENTE)
            header('Location: /maestros/index/');
        else
            header('Location: /usuario/login/hay_un_problema');
    }

    // metodo que destruye la sesion y regresa la url por default
    function destroy_session($mensaje = array()) {
        session_destroy();

        if (empty($mensaje))
            header("Location: /");
        else
            header("Location: /usuario/login/" . $mensaje[0] . "/" . $mensaje[1]);
    }

    // este metodo para verificar que el usuario tiene los privilegios para 
    // acceder a algun metodo
    function check_session($tipo) {
        // si no esta logeado se sale de la app
        if (!isset($_SESSION['logueado'])) {
            $usuario = "";
            if ($tipo == USER_ALUMNO)
                $usuario = "alumnos";
            else if ($tipo == USER_DOCENTE)
                $usuario = "maestros";
            header("Location: /$usuario/login");
        } else if ($_SESSION['logueado'] != $tipo) {
            // este acceso restringido se podria gestionar de otra manera
            echo "Acceso restringido";
            exit();
        }
    }

}

// este metodo es para auto crear un objeto de la clase
function HandlerSession() {
    return new HandlerSession();
}
