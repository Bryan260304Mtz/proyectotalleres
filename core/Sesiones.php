<?php

session_start();

class Sesiones {

    public function crearSesion($nocuenta, $datos) {
        $_SESSION["cuenta_intranet"] = $nocuenta;
        $_SESSION["cuenta_nombre"] = $datos["usuario"];
        $_SESSION["nombre_completo"] = $datos["nombre"];
    }

    public function verificarSesion($error = "") {
        if (!isset($_SESSION['cuenta_intranet'])) {
            session_destroy();
            header("location: /intranet/login/$error");
            exit();
        }
    }

    public function cerrarSesion() {
        session_destroy();
        header("Location: /");
        exit();
    }

}

function objSesiones() {
    return new Sesiones();
}
