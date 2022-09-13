<?php
include_once "base_datos.php";
define ("PASS_DEFECTO", "GimHunter123");

function registrarUsuario($usuario){
    $usuario->password = password_hash(PASS_DEFECTO, PASSWORD_DEFAULT);
    $sentencia = "INSERT INTO usuarios (usuario, nombre, telefono, password) VALUES (?,?,?,?)";
    $parametros = [$usuario->usuario, $usuario->nombre, $usuario->telefono, $usuario->password];
    return insertar($sentencia, $parametros);
}

function obtenerUsuarios(){
    $sentencia = "SELECT id,usuario, nombre, telefono FROM usuarios";
    return selectQuery($sentencia);
}

function eliminarUsuario($id){
    $sentencia = "DELETE FROM usuarios WHERE id = ?";
    return eliminar($sentencia, $id);
}

function obtenerUsuarioPorId($id){
    $sentencia = "SELECT id, usuario, nombre, telefono FROM usuarios WHERE id = ?";
    $parametros = [$id];
    return selectPrepare($sentencia, $parametros)[0];
}

function editarUsuario($usuario){
    $sentencia = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ? WHERE id = ?";
    $parametros = [$usuario->usuario, $usuario->nombre, $usuario->telefono, $usuario->id];
    return editar($sentencia, $parametros);
}

function iniciarSesion($usuario){
    $sentencia = "SELECT id, usuario, password FROM usuarios WHERE usuario = ?";
    $parametros  = [$usuario->usuario];

    $resultado = selectPrepare($sentencia, $parametros)[0];

    if($resultado){
        $passwordVerificada = password_verify($usuario->password, $resultado->password);
        if($resultado && $passwordVerificada) {
            $usuario = [
                "nombreUsuario" => $resultado->usuario,
                "idUsuario" => $resultado->id
            ];
        
            $verificaPass = verificarPassword(PASS_DEFECTO, $resultado->id);
            if($verificaPass) {
                return ["resultado" => "cambia", "datos" => $usuario];
            }
        
            return ["resultado" => true, "datos" => $usuario];
        } else {
            return ["resultado" => false];
        }
    }
}

function verificarPassword($password, $id){
    $sentencia = "SELECT password FROM usuarios  WHERE id = ?";
    $parametros = [$id];
    $respuesta = selectPrepare($sentencia, $parametros);
    $usuario = $respuesta[0];
    //print_r($usuario);
    if ($usuario === FALSE) return false;
    elseif($usuario) {
        $passwordVerifica = password_verify($password, $usuario->password);
        if($usuario && $passwordVerifica) {return true;}
        else{return false;}
    } 
}

function cambiarPassword($password, $id){
    $nuevaPassword = password_hash($password, PASSWORD_DEFAULT);
    $sentencia = "UPDATE usuarios SET password = ? WHERE id = ?";
    $parametros = [$nuevaPassword, $id];
    return editar($sentencia, $parametros);
}

function obtenerTotalVisitas($idUsuario){
    $sentencia  = "SELECT COUNT(*) AS total FROM visitas WHERE idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}

function obtenerVisitasHoy($idUsuario){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE DATE(fecha) = CURDATE() AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}

function obtenerVisitasSemana($idUsuario){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE()) AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}

function obtenerVisitasMes($idUsuario){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE  MONTH(fecha) = MONTH(CURRENT_DATE())
    AND YEAR(fecha) = YEAR(CURRENT_DATE()) AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}

function obtenerTotalPagos($idUsuario){
    $sentencia  = "SELECT SUM(monto) AS total FROM pagos WHERE idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}
    
function obtenerPagosHoy($idUsuario){
    $sentencia = "SELECT IFNULL(SUM(monto),0) AS total FROM pagos WHERE DATE(fecha) = CURDATE() AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}
    
function obtenerPagosSemana($idUsuario){
    $sentencia = "SELECT IFNULL(SUM(monto), 0) AS total FROM pagos WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE()) AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}
    
function obtenerPagosMes($idUsuario){
    $sentencia = "SELECT SUM(monto) AS total FROM pagos WHERE  MONTH(fecha) = MONTH(CURRENT_DATE())
        AND YEAR(fecha) = YEAR(CURRENT_DATE()) AND idUsuario = ?";
    return selectPrepare($sentencia, [$idUsuario])[0]->total;
}

