<?php
// login.php - CONTROLADOR
session_start();
require_once 'config/conexion.php'; 

// Verificación de seguridad de la conexión
if (!isset($conexion)) {
    die("Error: La configuración de conexión no cargó la variable \$conexion.");
}

// Redirección si ya está logueado
if (isset($_SESSION['usuario'])) {
    if ($_SESSION['usuario']['rol'] === 'Administrador') {
        header('Location: admin.php');
    } else {
        header('Location: apoderado.php');
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'models/UsuarioModel.php';

    // Capturamos los inputs
    $rutInput = trim($_POST['rut'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');

    if (empty($rutInput) || empty($passwordInput)) {
        $error = "Por favor ingrese todos los campos";
    } else {
        $modelo = new UsuarioModel($conexion);
        
        // El modelo ahora usará password_verify internamente
        $usuarioEncontrado = $modelo->autenticar($rutInput, $passwordInput);

        if ($usuarioEncontrado) {
            // Login Exitoso
            $_SESSION['usuario'] = [
                'id' => $usuarioEncontrado['id'],
                'nombre' => $usuarioEncontrado['nombre'],
                'apellido' => $usuarioEncontrado['apellido'],
                'rol' => $usuarioEncontrado['nombre_rol'], // 'Administrador' o 'Apoderado'
                'rol_id' => $usuarioEncontrado['rol_id']
            ];
            
            $_SESSION['ultimo_acceso'] = time();

            // Redirección
            if ($usuarioEncontrado['nombre_rol'] === 'Administrador') {
                header('Location: admin.php');
            } else {
                header('Location: apoderado.php');
            }
            exit();
        } else {
            $error = "RUT o contraseña incorrectos";
        }
    }
}

require 'views/login.view.php';
?>