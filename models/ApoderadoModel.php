<?php
// models/ApoderadoModel.php

class ApoderadoModel {
    private $con;

    public function __construct($conexion) {
        $this->con = $conexion;
    }

    // 1. Obtener datos (Perfil)
    public function obtenerPerfilApoderado($id_usuario) {
        try {
            $sql = "SELECT * FROM USUARIO WHERE id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([':id' => $id_usuario]);
            return $stmt->fetch();
        } catch (PDOException $e) { return null; }
    }

    // 2. Obtener Alumno
    public function obtenerAlumnoPorApoderado($id_apoderado) {
        try {
            $sql = "SELECT * FROM ALUMNO WHERE apoderado_id = :id LIMIT 1";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([':id' => $id_apoderado]);
            return $stmt->fetch();
        } catch (PDOException $e) { return null; }
    }

    // 3. Obtener Cuotas
    public function obtenerCuotas($id_apoderado) {
        try {
            $sql = "SELECT C.*, P.fecha_pago, P.id as pago_id, COALESCE(P.monto_pagado, 0) as pagado
                    FROM CUOTA C
                    LEFT JOIN PAGO P ON C.id = P.cuota_id
                    WHERE C.apoderado_id = :id
                    ORDER BY C.mes ASC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([':id' => $id_apoderado]);
            return $stmt->fetchAll();
        } catch (PDOException $e) { return []; }
    }

    // --- NUEVO: 4. ACTUALIZAR PERFIL ---
    public function actualizarPerfil($id, $nombre, $apellido, $correo, $telefono) {
        try {
            $sql = "UPDATE USUARIO 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        correo = :correo, 
                        telefono = :telefono 
                    WHERE id = :id";
            
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>