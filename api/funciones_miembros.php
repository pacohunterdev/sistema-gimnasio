<?php
include_once "base_datos.php";

function registrarMiembro($miembro){
    $matricula = generarMatricula();
    $imagen = ($miembro->imagen) ? obtenerImagen($miembro->imagen) : './imagenes/usuario.png';
    $sentencia = "INSERT INTO miembros 
    (matricula, nombre, telefono, direccion, edad,
    sufreEnfermedad, tieneSeguro, enfermedad, institucion, 
    nombreContacto, telefonoContacto, imagen, fechaRegistro) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $parametros = [$matricula, $miembro->nombre, $miembro->telefono, $miembro->direccion, 
    $miembro->edad, $miembro->sufreEnfermedad, $miembro->tieneSeguro, $miembro->enfermedad, 
    $miembro->institucion, $miembro->nombreContacto,
    $miembro->telefonoContacto, $imagen, date("Y-m-d H:i:s")
];
    $resultado = insertar($sentencia, $parametros);
    if($resultado) return $matricula;
}

function generarMatricula(){
    $id = selectQuery("SELECT MAX(id) AS id FROM miembros")[0]->id;
    $ultimoId = $id + 1;
    return date('Y').'-'.$ultimoId;
}



function obtenerMiembros(){
    $sentencia = "SELECT miembros.*, membresias.nombre AS membresia 
    FROM miembros
    LEFT JOIN membresias ON membresias.id = miembros.idMembresia ";
    $miembros =  selectQuery($sentencia);
    return verificarMembresia($miembros);
}

function obtenerMiembroNombreMatricula($busqueda){
    $sentencia = "SELECT miembros.*, membresias.nombre AS membresia, membresias.id AS idMembresia 
    FROM miembros
    LEFT JOIN membresias ON membresias.id = miembros.idMembresia
    WHERE (miembros.nombre LIKE ? OR miembros.matricula LIKE ?)";
    $parametros = ["%".$busqueda."%", "%".$busqueda."%"];
    return selectPrepare($sentencia, $parametros);
}

function registrarPago($pago){
    $sentencia = "INSERT INTO pagos (matricula, idMembresia, idUsuario, fecha, monto) VALUES (?,?,?,?,?)";
    $parametros = [$pago->matricula, $pago->idMembresia, $pago->idUsuario, $pago->fecha, $pago->pago];
    $pagoRegistrado = insertar($sentencia, $parametros);
    if($pagoRegistrado) return actualizarMembresia($pago->matricula, $pago->idMembresia, $pago->duracion);
}

function actualizarMembresia($matricula, $idMembresia, $duracion){
    $sentencia = "UPDATE miembros SET idMembresia = ?, estado = ?, fechaInicio = ?, fechaFin = DATE_ADD(fechaInicio, INTERVAL ? DAY) WHERE matricula = ? ";
    $parametros = [$idMembresia, 'ACTIVO', date("Y-m-d H:i:s"), $duracion, $matricula];
    return editar($sentencia, $parametros);
} 

function verificarMembresia($miembros){
    foreach($miembros as $miembro) {
        if($miembro->fechaFin < date("Y-m-d H:i:s")){
            marcarMembresiaVencida($miembro->id);
        }
    }
    return $miembros;
}

function marcarMembresiaVencida($id){
    $sentencia = "UPDATE miembros SET estado = ? WHERE id = ?";
    $parametros = ['VENCIDO', $id];
    editar($sentencia, $parametros);
}

function obtenerPorId($id){
    $sentencia = "SELECT * FROM miembros WHERE id = ?";
    $parametros = [$id];
    return selectPrepare($sentencia, $parametros)[0];
}

function eliminarMiembro($id){
    $sentencia = "DELETE FROM miembros WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarMiembro($miembro) {
    $miembro->imagen = ($miembro->imagenCambia) ? obtenerImagen($miembro->imagen) : $miembro->imagen;
    $sentencia =  "UPDATE miembros SET nombre = ?, telefono = ?, direccion = ?, edad = ?,
    sufreEnfermedad = ?, tieneSeguro = ?, enfermedad = ?, institucion = ?, 
    nombreContacto = ?, telefonoContacto = ?, imagen = ? WHERE id = ?";
    $parametros = [$miembro->nombre, $miembro->telefono, $miembro->direccion, 
    $miembro->edad, $miembro->sufreEnfermedad, $miembro->tieneSeguro, $miembro->enfermedad, 
    $miembro->institucion, $miembro->nombreContacto,
    $miembro->telefonoContacto, $miembro->imagen, $miembro->id];
    return editar($sentencia, $parametros);
}

function obtenerImagenPorMatricula($matricula){
    $sentencia  = "SELECT imagen FROM miembros WHERE matricula = ?";
    return selectPrepare($sentencia, [$matricula])[0];
}