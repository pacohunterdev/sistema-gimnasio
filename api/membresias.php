<?php
include_once "encabezado.php";

$payload = json_decode(file_get_contents("php://input"));
if (!$payload) {
    http_response_code(500);
    exit;
}

include_once "funciones_membresias.php";

$metodo = $payload->metodo;

switch($metodo){
    case "post":
        echo json_encode(registrarMembresia($payload->membresia));
        break;
    case "get";
        echo json_encode(obtenerMembresias());
        break;
    case "delete";
        echo json_encode(eliminarMembresia($payload->id));
        break;
    
    case "put";
        echo json_encode(editarMembresia($payload->membresia));
        break;
}

