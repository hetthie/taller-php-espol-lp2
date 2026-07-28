<?php
// Devuelve el nombre del archivo CSV de tareas propio de cada usuario
function archivoTareas($usuario) {
    return "tareas_" . $usuario . ".csv";
}

// Agrega una nueva tarea con id único, texto y estado "pendiente"
function guardarTarea($usuario, $texto) {
    $archivo = archivoTareas($usuario);
    $id = uniqid();
    $fp = fopen($archivo, "a");
    fputcsv($fp, [$id, $texto, "pendiente"]);
    fclose($fp);
}

// Retorna las tareas del usuario separadas en pendientes y completadas
function listarTareas($usuario) {
    $archivo = archivoTareas($usuario);
    $pendientes = [];
    $completadas = [];

    if (file_exists($archivo)) {
        $fp = fopen($archivo, "r");
        while (($fila = fgetcsv($fp)) !== false) {
            if (count($fila) < 3) continue;
            $tarea = ['id' => $fila[0], 'texto' => $fila[1], 'estado' => $fila[2]];
            if ($tarea['estado'] === 'completada') {
                $completadas[] = $tarea;
            } else {
                $pendientes[] = $tarea;
            }
        }
        fclose($fp);
    }

    return ['pendientes' => $pendientes, 'completadas' => $completadas];
}

// Cambia el estado de una tarea a "completada"
function completarTarea($usuario, $id) {
    $archivo = archivoTareas($usuario);
    if (!file_exists($archivo)) return;

    $fp = fopen($archivo, "r");
    $filas = [];
    while (($fila = fgetcsv($fp)) !== false) {
        if ($fila[0] === $id) {
            $fila[2] = "completada";
        }
        $filas[] = $fila;
    }
    fclose($fp);

    $fp = fopen($archivo, "w");
    foreach ($filas as $fila) {
        fputcsv($fp, $fila);
    }
    fclose($fp);
}

// Elimina la línea correspondiente a la tarea indicada
function eliminarTarea($usuario, $id) {
    $archivo = archivoTareas($usuario);
    if (!file_exists($archivo)) return;

    $fp = fopen($archivo, "r");
    $filas = [];
    while (($fila = fgetcsv($fp)) !== false) {
        if ($fila[0] !== $id) {
            $filas[] = $fila;
        }
    }
    fclose($fp);

    $fp = fopen($archivo, "w");
    foreach ($filas as $fila) {
        fputcsv($fp, $fila);
    }
    fclose($fp);
}
?>
