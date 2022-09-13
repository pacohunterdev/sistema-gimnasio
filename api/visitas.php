<?php
include_once "encabezado.php";

$payload = json_decode(file_get_contents("php://input"));
if (!$payload) {
    http_response_code(500);
    exit;
}

include_once "funciones_visitas.php";

$metodo = $payload->metodo;

switch($metodo){
    case "registrar":
        echo json_encode(registrarVisista($payload->visita));
        break;
    
    case "registrar_regular":
        echo json_encode(registrarVisitaRegular($payload->visita));
        break;
    case "obtener":
        echo json_encode(
           [
            "visitas" => obtenerVisitas($payload->filtros),
            "totalesMembresias" => obtenerTotalesMembresias($payload->filtros),
            "totalesUsuarios" => obtenerTotalesPorUsuario($payload->filtros),
            "totalesMiembros" => obtenerTotalesPorMiembro($payload->filtros)
            ]
        );
        break;

}

