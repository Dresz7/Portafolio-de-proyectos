<?php
include_once("../functions.php");

// Recuperar parámetros de POST o finalizar si no se proporcionan
$fechaInicio = $_POST['fecha_inicio'] ?? die("Fecha de inicio no recibida");
$fechaFinal = $_POST['fecha_final'] ?? die("Fecha de cierre no recibida");
$sucursalSelect = $_POST['sucursalSelect'] ?? '';

// Añadir cláusula WHERE adicional para sucursal si es necesario
$whereSucursal = '';
if (in_array($sucursalSelect, ['1', '2'])) {
    $whereSucursal = " AND idSucursal = $sucursalSelect";
}

function calcular_ingresos($conexion, $fechaInicio, $fechaFinal, $whereSucursal) {
    $ingresos = [];

    // Ajustar las fechas de inicio y final para incluir toda la jornada del día final
    $fechaInicioConHora = $fechaInicio . ' 00:00:00';
    $fechaFinalConHora = $fechaFinal . ' 23:59:59';

    $fechaActual = new DateTime($fechaInicio);
    $fechaFin = new DateTime($fechaFinal);
    while ($fechaActual <= $fechaFin) {
        // Asegurarse de que la comparación incluya las horas para cubrir todo el día
        $fechaConsultaInicio = $fechaActual->format('Y-m-d 00:00:00');
        $fechaConsultaFin = $fechaActual->format('Y-m-d 23:59:59');
        
        $sql = "SELECT
            (SELECT COALESCE(SUM(montoSaldado), 0)
             FROM info_pago
             WHERE fechaPago BETWEEN '$fechaConsultaInicio' AND '$fechaConsultaFin' $whereSucursal) AS IngresoDiario,
            (SELECT COALESCE(SUM(montoGasto), 0)
             FROM gastos
             WHERE fechaGasto BETWEEN '$fechaConsultaInicio' AND '$fechaConsultaFin' $whereSucursal) AS GastoGeneral,
            (SELECT COALESCE(SUM(montoGastoV), 0)
             FROM gastosvehiculos
             WHERE fechaGastoV BETWEEN '$fechaConsultaInicio' AND '$fechaConsultaFin' $whereSucursal) AS GastosUnidades";

        $resultado = mysqli_query($conexion, $sql);
        if (!$resultado) {
            die('Error en la consulta: ' . mysqli_error($conexion));
        }
        $fila = mysqli_fetch_assoc($resultado);

        // Agregar datos calculados al array de ingresos para el día actual
        $ingresos[] = [
            'fecha' => $fechaActual->format('Y-m-d'),
            'ingresoDiario' => round($fila['IngresoDiario'], 2),
            'gastoUnidades' => round($fila['GastosUnidades'], 2),
            'gastoGeneral' => round($fila['GastoGeneral'], 2),
            'ingresoNeto' => round($fila['IngresoDiario'] - ($fila['GastosUnidades'] + $fila['GastoGeneral']), 2)
        ];

        // Avanzar al siguiente día
        $fechaActual->modify('+1 day');
    }

    return $ingresos;
}


// Llamar a la función calcular_ingresos y pasar los parámetros necesarios
$ingresosCalculados = calcular_ingresos($conexion, $fechaInicio, $fechaFinal, $whereSucursal);

// Devolver los ingresos calculados en formato JSON
header('Content-Type: application/json');
echo json_encode($ingresosCalculados);
?>