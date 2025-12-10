<?php
// apoderado.php - CONTROLADOR
session_start();

// 1. CONTROL DE INACTIVIDAD (30 Minutos)
$tiempo_limite = 1800; 
if (isset($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso']) > $tiempo_limite) {
    session_unset(); session_destroy(); header("Location: login.php?mensaje=sesion_expirada"); exit();
}
$_SESSION['ultimo_acceso'] = time();

// 2. CONFIGURACIÓN
require_once 'config/conexion.php';
require_once 'models/ApoderadoModel.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Apoderado') {
    header('Location: login.php'); die();
}

$id_usuario = $_SESSION['usuario']['id'];
$modelo = new ApoderadoModel($conexion);

$mensaje_exito = '';
$mensaje_error = '';

// --- 3. PROCESAR ACTUALIZACIÓN DE PERFIL (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
    
    // Capturar datos
    $nombre_nuevo   = trim($_POST['nombre']);
    $apellido_nuevo = trim($_POST['apellido']);
    $email_nuevo    = trim($_POST['email']);
    
    // MEJORA AQUÍ: Limpiamos el teléfono antes de guardar
    // Eliminamos todo lo que NO sea número o el signo '+'
    $telefono_nuevo = preg_replace('/[^0-9+]/', '', $_POST['telefono']);

    if (empty($nombre_nuevo) || empty($apellido_nuevo) || empty($email_nuevo)) {
        $mensaje_error = "Nombre, Apellido y Email son obligatorios.";
    } else {
        if ($modelo->actualizarPerfil($id_usuario, $nombre_nuevo, $apellido_nuevo, $email_nuevo, $telefono_nuevo)) {
            $mensaje_exito = "Datos actualizados correctamente.";
            
            // Actualizar sesión para reflejar cambios inmediatamente
            $_SESSION['usuario']['nombre'] = $nombre_nuevo;
            $_SESSION['usuario']['apellido'] = $apellido_nuevo;
        } else {
            $mensaje_error = "Error al actualizar la base de datos.";
        }
    }
}

// 4. OBTENER DATOS (Siempre después del POST para que vengan actualizados)
$datos_usuario = $modelo->obtenerPerfilApoderado($id_usuario);
$alumno = $modelo->obtenerAlumnoPorApoderado($id_usuario);
$cuotas = $modelo->obtenerCuotas($id_usuario);

// 5. CÁLCULOS DASHBOARD
$pagos_realizados = 0;
$pagos_pendientes = 0;
$monto_total_pagado = 0;
$historial_pagos = []; 

if (!empty($cuotas)) {
    foreach ($cuotas as $c) {
        if ($c['estado_pago'] == 'Abonado' || $c['estado_pago'] == 'Pagado') {
            $pagos_realizados++;
            $monto_total_pagado += $c['monto'];
            $historial_pagos[] = $c;
        } else {
            $pagos_pendientes++;
        }
    }
}

// 6. FORMATEO TELÉFONO (Lógica Visual)
// Esto toma el número sucio de la BD y lo muestra bonito en el Sidebar
$telefono_visual = "Sin teléfono";
if ($datos_usuario && !empty($datos_usuario['telefono'])) {
    // Limpiamos solo para analizar
    $fono = preg_replace('/[^0-9]/', '', $datos_usuario['telefono']);
    
    // Caso 1: Celular chileno (569 1234 5678)
    if (strlen($fono) == 11 && substr($fono, 0, 3) == '569') {
        $telefono_visual = '+56 9 ' . substr($fono, 3, 4) . ' ' . substr($fono, 7);
    } 
    // Caso 2: Celular chileno sin +56 (9 1234 5678)
    elseif (strlen($fono) == 9) {
        $telefono_visual = '+56 9 ' . substr($fono, 1, 4) . ' ' . substr($fono, 5);
    } 
    // Caso 3: Otro número, se muestra tal cual
    else {
        $telefono_visual = $datos_usuario['telefono'];
    }
}

// 7. VARIABLES VISTA
$usuario_nombre = $_SESSION['usuario']['nombre'];
$apoderado = [
    'alumno_nombre' => $alumno['nombre'] ?? '',
    'alumno_apellido' => $alumno['apellido'] ?? '',
    'curso' => $alumno['curso'] ?? 'Sin asignar',
    'telefono' => $telefono_visual // Aquí va el teléfono formateado
];

require 'views/apoderado.view.php';
?>