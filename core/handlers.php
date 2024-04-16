<?php

/* * ************* FUNCIONES DE AYUDA***************** */

// funcion que marca una opcion en un select para las vistas
function addSelected($lista,$campo, $valor) {
        foreach ($lista as $key => $value) {
            $lista[$key]["selected"] = $value[$campo] == $valor ? "selected" : "";
        }
        return $lista;
    }

// funcion que validad si el dato es un email 
function isEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// funcion que verifica que los campos tengan datos 
function isNullLogin($usuario, $password) {
    if (strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1) {
        return true;
    } else {
        return false;
    }
}

// funcion que limpia de caracteres especiales los datos ingresados 
function recoge($var) {
    $tmp = (isset($_REQUEST[$var])) ? trim(htmlspecialchars($_REQUEST[$var], ENT_QUOTES, "UTF-8")) : "";
    return $tmp;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function trimUpper($data) {
    $data = trim($data);
    $data = strtoupper($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validaNumerosLetras($datos) {
    $strings = array('AbCd1zyZ9', $datos);
    foreach ($strings as $testcase) {
        if (!ctype_alnum($testcase))
            return false;
    }
    return true;
}

// funcion que genera una encriptacion de un campo
function hashPassword($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    return $hash;
}

// funcion que valida si dos cadenas de texto son iguales, se usa para validar el password ingresado
// por el usuario y el que se encuentra en la BD 
function validaPassword($var1, $var2) {
    if (strcmp($var1, $var2) !== 0) {
        return false;
    } else {
        return true;
    }
}

// escribir valores de una matriz para las pruebas de datos obtenidos
function escribeMatriz($matriz = array()) {
    echo "<pre>";
    print_r($matriz);
    echo "</pre>";
    exit();
}

// validar si es un numero 
function isInteger($value) {
    return is_int($value) || ctype_digit($value) ? true : false;
}

// redondea un numero para la calificion final en las calificaciones parciales
function redondear($dato) {
    $dato = number_format($dato, 0);
    if ($dato < 70) {
        $calificacion = 60;
    } else {
        $residuo = $dato % 10;
        if ($residuo < 5) {
            $calificacion = $dato - $residuo;
        } else {
            $calificacion = $dato + 10 - $residuo;
        }
    }
    return $calificacion;
}

// cambia la fecha de MySQL(YYY-mm-dd) a normal(dd/mm/YYYY)
function fechaNormal($fecha) {
    $mifecha = explode('-', $fecha);
    if (count($mifecha) == 3) {
        $lafecha = $mifecha[2] . "/" . $mifecha[1] . "/" . $mifecha[0];
        return $lafecha;
    } else {
     return $fecha;   
    }
}

// cambia la fecha de ExBach a formato MySQL(YYY-mm-dd)
function fechaLargaToMysql($cadena){
   
     $fecha = explode(' ', $cadena);
     $day = $fecha[0];
     $year = $fecha[4];
     $numMonth = 0;
     $months = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto",
     "septiembre", "octubre", "noviembre", "diciembre"];
     foreach($months as $key => $nameMonth){
        if(strcmp(strtoupper($nameMonth), $fecha[2]) === 0){
            $numMonth=$key+1;
            break;
        }
     } 
     return "$year-$numMonth-$day";
}

/*
 * FUNCION QUE EMULA EL FETCH_ASSOC DE PDO
 * Esta función nos permite crear un array asociativo con los resultados
 * Así accedemos fácimente a su valor por el nombre de columna en la base de datos
 * Es particularmente útil cuando en el SELECT tenemos muchas columnas
 * porque de lo contrario, tendríamos que hacer un bind de cada columna a mano
 * Esta función se puede incorporar a una clase utilitaria, para re-usarla en
 * todas las consultas que requieran este tipo de operaciones
 */

function myGetResult($Statement) {
    $RESULT = array();
    $Statement->store_result();
    for ($i = 0; $i < $Statement->num_rows; $i++) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ($Field = $Metadata->fetch_field()) {
            $PARAMS[] = &$RESULT[$i][$Field->name];
        }
        call_user_func_array(array($Statement, 'bind_result'), $PARAMS);
        $Statement->fetch();
    }
    return $RESULT;
}
