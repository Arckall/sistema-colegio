<?php
// models/UsuarioModel.php

class UsuarioModel {
    private $con;

    public function __construct($conexion) {
        $this->con = $conexion;
    }

    public function autenticar($rut, $password) {
        try {
            // 1. Buscamos el usuario por RUT y traemos el nombre del rol
            $sql = "SELECT U.*, R.nombre as nombre_rol 
                    FROM USUARIO U 
                    INNER JOIN ROL R ON U.rol_id = R.id 
                    WHERE U.rut = :rut LIMIT 1";
            
            $stmt = $this->con->prepare($sql);
            $stmt->execute([':rut' => $rut]);
            $usuario = $stmt->fetch();

            if ($usuario) {
                // 2. IMPORTANTE: Usamos password_verify para comparar
                // lo que escribió el usuario vs el hash en la base de datos
                if (password_verify($password, $usuario['contrasena'])) {
                    return $usuario;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>