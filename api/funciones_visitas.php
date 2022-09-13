<?php
include_once "base_datos.php";

function registrarVisista($visita){
    $sentencia =  "INSERT INTO visitas (idMiembro, idUsuario, idMembresia, fecha) VALUES (?,?,?,?)";
    $parametros = [$visita->idMiembro, $visita->idUsuario, $visita->idMembresia, date("Y-m-d H:i:s")];
    return insertar($sentencia, $parametros);
}

function obtenerVisitas($filtros) {
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia = "SELECT visitas.fecha, miembros.nombre, miembros.imagen, miembros.matricula, usuarios.usuario,
    IFNULL(membresias.nombre, 'VISITA REGULAR') AS membresia 
    FROM visitas
    LEFT JOIN membresias ON membresias.id = visitas.idMembresia
    LEFT JOIN miembros ON miembros.id = visitas.idMiembro
    LEFT JOIN usuarios ON usuarios.id = visitas.idUsuario
    WHERE DATE(visitas.fecha) >= ? AND DATE(visitas.fecha) <= ? ";
    $parametros = [$fechaInicio, $fechaFin];

    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesMembresias($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia = "SELECT COUNT(*) AS total, IFNULL(membresias.nombre, 'Visitas regular') AS nombre
    FROM visitas
    LEFT JOIN membresias ON membresias.id = visitas.idMembresia
    WHERE DATE(visitas.fecha) >= ? AND DATE(visitas.fecha) <= ? 
    GROUP BY visitas.idMembresia
    ORDER BY total DESC";

    $parametros = [$fechaInicio, $fechaFin];

    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesPorUsuario($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia  = "SELECT COUNT(*) AS total, usuarios.usuario AS nombre FROM visitas
    INNER JOIN usuarios ON usuarios.id = visitas.idUsuario
    WHERE DATE(visitas.fecha) >= ? AND DATE(visitas.fecha) <= ? 
    GROUP BY visitas.idUsuario
    ORDER BY total DESC";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros);
}

function obtenerTotalesPorMiembro($filtros){
    $fechaInicio = (isset($filtros->fechaInicio)) ? $filtros->fechaInicio : FECHA_HOY;
    $fechaFin = (isset($filtros->fechaFin)) ? $filtros->fechaFin : FECHA_HOY;

    $sentencia  = "SELECT COUNT(*) AS total, miembros.nombre, miembros.matricula, miembros.imagen FROM visitas
    INNER JOIN miembros ON miembros.id = visitas.idMiembro
    WHERE DATE(visitas.fecha) >= ? AND DATE(visitas.fecha) <= ? 
    GROUP BY visitas.idMiembro
    ORDER BY total DESC
    LIMIT 5";
    $parametros = [$fechaInicio, $fechaFin];
    return selectPrepare($sentencia, $parametros);
}

function registrarVisitaRegular($visita){
    $idUsuario = $visita->idUsuario;
    $monto = $visita->pago;

    $sentenciaVisita = "INSERT INTO visitas(idUsuario, fecha) VALUES (?,?)";
    $sentenciaPago = "INSERT INTO pagos (matricula, idMembresia, idUsuario, fecha, monto) VALUES(?,?,?,?,?)";

    $visita = insertar($sentenciaVisita, [$idUsuario, date("Y-m-d H:i:s")]);

    $pago = insertar($sentenciaPago, ['0','0', $idUsuario, date("Y-m-d H:i:s"), $monto]);

    return $visita && $pago;

}

