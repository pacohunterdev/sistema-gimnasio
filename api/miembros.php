<?php
include_once "encabezado.php";

$payload = json_decode(file_get_contents("php://input"));
if (!$payload) {
    http_response_code(500);
    exit;
}

include_once "funciones_miembros.php";

$metodo = $payload->metodo;

switch($metodo){
    case "registrar":
        echo json_encode(registrarMiembro($payload->miembro));
        break;
    case "get";
        echo json_encode(obtenerMiembros());
        break;
    case "eliminar";
        echo json_encode(eliminarMiembro($payload->id));
        break;
    case "editar";
        echo json_encode(editarMiembro($payload->miembro));
        break;
    case("pagar"):
        echo json_encode(registrarPago($payload->pago));
    break;
    
    case("obtener_nombre_matricula"):
        echo json_encode(obtenerMiembroNombreMatricula($payload->busqueda));
    break;
    
    case("obtener_imagen"):
        echo json_encode(obtenerImagenPorMatricula($payload->matricula));
    break;
    
    case("obtener_id"):
        echo json_encode(obtenerPorId($payload->id));
    break;
}
