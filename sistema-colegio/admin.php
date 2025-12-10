<?php
// admin.php - CONTROLADOR
session_start();

// 1. INACTIVIDAD
$tiempo_limite = 1800; 
if (isset($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso']) > $tiempo_limite) {
    session_unset(); session_destroy(); header("Location: login.php?mensaje=sesion_expirada"); exit();
}
$_SESSION['ultimo_acceso'] = time();

// 2. CONFIGURACIÓN
require_once 'config/conexion.php';
require_once 'models/AdminModel.php';

// Validar Rol 'Administrador'
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Administrador') {
    header('Location: login.php'); exit();
}

$mensaje_exito = '';
$mensaje_error = '';
$adminModel = new AdminModel($conexion);

// 3. POST (ACCIONES)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // GUARDAR APODERADO
    if (isset($_POST['guardar_apoderado'])) {
        $nombre = mb_convert_case(trim($_POST['nombre']), MB_CASE_TITLE, "UTF-8");
        $apellido = mb_convert_case(trim($_POST['apellido']), MB_CASE_TITLE, "UTF-8");
        $nombre_alumno = mb_convert_case(trim($_POST['nombre_alumno']), MB_CASE_TITLE, "UTF-8");
        $apellido_alumno = mb_convert_case(trim($_POST['apellido_alumno']), MB_CASE_TITLE, "UTF-8");
        $telefono_limpio = preg_replace('/[^0-9+]/', '', $_POST['telefono']);

        $datos = [
            'nombre' => $nombre, 
            'apellido' => $apellido,
            'rut' => trim($_POST['rut']), // El rut tal cual
            'correo' => strtolower(trim($_POST['email'])),
            'telefono' => $telefono_limpio, 
            'password' => !empty($_POST['password']) ? $_POST['password'] : null,
            'nombre_alumno' => $nombre_alumno, 
            'apellido_alumno' => $apellido_alumno,
            'curso' => $_POST['curso'], 
            'id_usuario' => !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null
        ];

        if ($adminModel->guardarApoderado($datos)) {
            $mensaje_exito = "Apoderado guardado correctamente.";
        } else {
            // Si falla, suele ser por RUT o Email duplicado
            $mensaje_error = "Error al guardar. Verifique que el RUT o Email no existan ya.";
        }
    }

    // ELIMINAR
    if (isset($_POST['eliminar_apoderado'])) {
        if ($adminModel->eliminarApoderado($_POST['usuario_id'])) $mensaje_exito = "Usuario eliminado.";
        else $mensaje_error = "Error al eliminar.";
    }
}

// 4. DATOS PARA LA VISTA
$stats = $adminModel->obtenerEstadisticas();
$apoderados = $adminModel->obtenerListaApoderados();
$lista_pagos = $adminModel->obtenerTodosLosPagos();

$usuario_nombre = $_SESSION['usuario']['nombre'];
$total_apoderados = $stats['total_apoderados'] ?? 0;
$total_pagos = $stats['total_pagos'] ?? 0;
$total_recaudado = $stats['total_recaudado'] ?? 0;

require 'views/admin.view.php';
?>