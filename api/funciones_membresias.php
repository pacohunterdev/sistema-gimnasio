<?php
include_once "base_datos.php";

function registrarMembresia($membresia){
    $sentencia = "INSERT INTO membresias (nombre, duracion, precio) VALUES (?,?,?)";
    $parametros = [$membresia->nombre, $membresia->duracion, $membresia->precio];
    return insertar($sentencia, $parametros);
}

function obtenerMembresias(){
    $sentencia = "SELECT * FROM membresias";
    return selectQuery($sentencia);
}

function eliminarMembresia($id){
    $sentencia = "DELETE FROM membresias WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarMembresia($membresia){
    $sentencia = "UPDATE membresias SET nombre = ?, duracion = ?, precio = ? WHERE id = ?";
    $parametros = [$membresia->nombre, $membresia->duracion, $membresia->precio, $membresia->id];
    return editar($sentencia, $parametros);
}
