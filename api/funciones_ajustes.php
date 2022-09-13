<?php
include_once "base_datos.php";

function editarAjustes($informacion) {
    $informacion->logo = ($informacion->logoCambia) ? obtenerImagen($informacion->logo) : $informacion->logo;
    $sentencia = "UPDATE ajustes SET nombre = ?, logo = ?, direccion = ?, telefono = ?";
    $parametros = [$informacion->nombre, $informacion->logo, $informacion->direccion, $informacion->telefono];
    return editar($sentencia, $parametros);
}

function registrarAjustes($informacion) {
    $logo = ($informacion->logo) ? obtenerImagen($informacion->logo) : './imagenes/logo.png';
    $sentencia = "INSERT INTO ajustes (nombre, logo, direccion, telefono) VALUES(?,?,?,?)";
    $parametros = [$informacion->nombre, $logo, $informacion->direccion, $informacion->telefono];
    return insertar($sentencia, $parametros);
}

function obtenerInformacionAjustes() {
    $sentencia = "SELECT  * FROM ajustes";
    return selectQuery($sentencia);
}