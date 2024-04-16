<?php

require_once 'DB.php';

function darSelectEstado($estado) {
    $db = new DB();
    $conexion = $db->conectar();
    $consulta = "SELECT  * FROM estado";
    $sentencia = $conexion->prepare($consulta) or die("" . $conexion->error);
    // $sentencia->bind_param("s", $estado) or die("" . $conexion->error);
    $sentencia->execute() or die("" . $conexion->error);
    $resultado = $sentencia->get_result();
    while ($estados[] = $resultado->fetch_assoc());
    array_pop($estados); // elimina el ultimo elemento del arreglo

    $sentencia->close();
    $conexion->close();

    foreach ($estados as $key => $unEstado ) {
        $estados[$key]["seleccionado"] = "";
        if( strcmp($unEstado["nomestado"],$estado) === 0 )
                $estados[$key]["seleccionado"] = "selected";
    }
    
    return $estados;
}
