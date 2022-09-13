<?php

include_once "base_datos.php";


function obtenerTotalVisitas(){
    $sentencia  = "SELECT COUNT(*) AS total FROM visitas";
    return selectQuery($sentencia)[0]->total;
}

function obtenerVisitasHoy(){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE DATE(fecha) = CURDATE()";
    return selectQuery($sentencia)[0]->total;
}

function obtenerVisitasSemana(){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE())";
    return selectQuery($sentencia)[0]->total;
}

function obtenerVisitasMes(){
    $sentencia = "SELECT COUNT(*) AS total FROM visitas WHERE  MONTH(fecha) = MONTH(CURRENT_DATE())
    AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    return selectQuery($sentencia)[0]->total;
}

function obtenerTotalPagos(){
    $sentencia  = "SELECT SUM(monto) AS total FROM pagos";
    return selectQuery($sentencia)[0]->total;
}
    
function obtenerPagosHoy(){
    $sentencia = "SELECT IFNULL(SUM(monto),0) AS total FROM pagos WHERE DATE(fecha) = CURDATE()";
    return selectQuery($sentencia)[0]->total;
}
    
function obtenerPagosSemana(){
    $sentencia = "SELECT IFNULL(SUM(monto), 0) AS total FROM pagos WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE())";
    return selectQuery($sentencia)[0]->total;
}
    
function obtenerPagosMes(){
    $sentencia = "SELECT IFNULL(SUM(monto),0) AS total FROM pagos WHERE  MONTH(fecha) = MONTH(CURRENT_DATE())
        AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    return selectQuery($sentencia)[0]->total;
}

function obtenerVisitasHora(){
    $sentencia = "SELECT CONCAT(DATE_FORMAT(fecha,'%H'), 'hrs') AS hora, 
    COUNT(*) AS numeroVisitas FROM visitas 
    GROUP BY DATE_FORMAT(fecha,'%H') 
    ORDER BY hora ASC
    ";
    return selectQuery($sentencia);
}

function obtenerVisitasDiasSemana() {
    $sentencia = "SELECT DAYNAME(fecha) AS dia, DAYOFWEEK(fecha) AS numeroDia, COUNT(*) AS numeroVisitas FROM visitas
     WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE())
     GROUP BY dia 
     ORDER BY fecha ASC";
    return selectQuery($sentencia);

}

function obtenerVisitasPorDiaMes(){
    $sentencia = "SELECT DAY(fecha) AS dia, COUNT(*) AS numeroVisitas
	FROM visitas
	WHERE MONTH(fecha) = MONTH(CURRENT_DATE())
    AND YEAR(fecha) = YEAR(CURRENT_DATE())
	GROUP BY dia
	ORDER BY dia ASC";
    return selectQuery($sentencia);
}

function obtenerPagosDiasSemana() {
    $sentencia = "SELECT DAYNAME(fecha) AS dia, DAYOFWEEK(fecha) AS numeroDia, SUM(monto) AS total FROM pagos
     WHERE YEARWEEK(fecha)=YEARWEEK(CURDATE())
     GROUP BY dia 
     ORDER BY fecha ASC";
    return selectQuery($sentencia);

}

function obtenerPagosPorDiaMes(){
    $sentencia = "SELECT DAY(fecha) AS dia, SUM(monto) AS total
	FROM pagos
	WHERE MONTH(fecha) = MONTH(CURRENT_DATE())
    AND YEAR(fecha) = YEAR(CURRENT_DATE())
	GROUP BY dia
	ORDER BY dia ASC";
    return selectQuery($sentencia);
}

function obtenerPagosPorMeses(){
    $sentencia = "SELECT MONTH(fecha) AS mes, IFNULL(SUM(monto),0) AS total FROM pagos 
    WHERE YEAR(fecha) = YEAR(CURRENT_DATE())
    GROUP BY MONTH(fecha) ORDER BY mes ASC";
    return selectQuery($sentencia);
}