<?php
include_once "base_datos.php";


function obtenerPagos($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia = "SELECT pagos.fecha, pagos.monto, miembros.nombre, miembros.imagen, miembros.matricula, usuarios.usuario,
    IFNULL(membresias.nombre, 'VISITA REGULAR') AS membresia 
    FROM pagos
    LEFT JOIN membresias ON membresias.id = pagos.idMembresia
    LEFT JOIN miembros ON miembros.matricula = pagos.matricula
    LEFT JOIN usuarios ON usuarios.id = pagos.idUsuario
    WHERE DATE(pagos.fecha) >= ? AND DATE(pagos.fecha) <= ? ";
    $parametros = [$fechaInicio, $fechaFin];

    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesPago($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia = "SELECT IFNULL(SUM(monto),0) AS totalPagos FROM pagos WHERE DATE(fecha) >= ? AND DATE(fecha) <= ?";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros)[0]->totalPagos;
}

function obtenerTotalesMembresia($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia  = "SELECT SUM(pagos.monto) AS total, IFNULL(membresias.nombre, 'Visitas regular') AS nombre FROM pagos
    LEFT  JOIN membresias ON membresias.id = pagos.idMembresia
    WHERE DATE(pagos.fecha) >= ? AND DATE(pagos.fecha) <= ? 
    GROUP BY pagos.idMembresia
    ORDER BY total DESC";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesPorUsuario($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia  = "SELECT SUM(pagos.monto) AS total, usuarios.usuario AS nombre FROM pagos
    INNER JOIN usuarios ON usuarios.id = pagos.idUsuario
    WHERE DATE(pagos.fecha) >= ? AND DATE(pagos.fecha) <= ? 
    GROUP BY pagos.idUsuario
    ORDER BY total DESC";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesPorMiembro($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia  = "SELECT SUM(pagos.monto) AS total, miembros.nombre, miembros.matricula, miembros.imagen FROM pagos
    INNER JOIN miembros ON miembros.matricula = pagos.matricula
    WHERE DATE(pagos.fecha) >= ? AND DATE(pagos.fecha) <= ? 
    GROUP BY pagos.matricula
    ORDER BY total DESC
    LIMIT 5";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros);
}

