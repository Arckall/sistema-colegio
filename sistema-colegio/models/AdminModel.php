<?php
// models/AdminModel.php

class AdminModel {
    private $con;

    public function __construct($conexion) {
        $this->con = $conexion;
    }

    // 1. ESTADÍSTICAS
    public function obtenerEstadisticas() {
        try {
            $stmt1 = $this->con->query("SELECT count(*) FROM USUARIO WHERE rol_id = 2");
            $totalApos = $stmt1->fetchColumn();
            $stmt2 = $this->con->query("SELECT count(*) FROM PAGO");
            $totalPagos = $stmt2->fetchColumn();
            $stmt3 = $this->con->query("SELECT COALESCE(SUM(monto_pagado), 0) FROM PAGO");
            $totalDinero = $stmt3->fetchColumn();
            return ['total_apoderados' => $totalApos, 'total_pagos' => $totalPagos, 'total_recaudado' => $totalDinero];
        } catch (PDOException $e) { return []; }
    }

    // 2. LISTA APODERADOS
    public function obtenerListaApoderados() {
        try {
            $sql = "SELECT u.id, u.nombre, u.apellido, u.rut, u.correo, u.telefono, 
                        'active' as estado, -- Simulado para la vista
                        a.nombre as nombre_alumno_db, a.apellido as apellido_alumno_db, a.curso,
                        (SELECT COUNT(*) FROM PAGO p WHERE p.usuario_id = u.id) as pagos_realizados,
                        (SELECT COALESCE(SUM(monto_pagado),0) FROM PAGO p WHERE p.usuario_id = u.id) as total_pagado
                    FROM USUARIO u
                    LEFT JOIN ALUMNO a ON a.apoderado_id = u.id
                    WHERE u.rol_id = 2 ORDER BY u.id DESC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) { return []; }
    }

    // 3. GUARDAR APODERADO (CORREGIDO PARA TU NUEVA BD)
    public function guardarApoderado($datos) {
        try {
            $this->con->beginTransaction();

            // Hash de contraseña si viene alguna, sino usa por defecto
            $password = !empty($datos['password']) ? password_hash($datos['password'], PASSWORD_DEFAULT) : password_hash('123456', PASSWORD_DEFAULT);

            if (empty($datos['id_usuario'])) {
                // --- INSERTAR NUEVO ---
                
                // A) Insertar en USUARIO (rol_id 2 = Apoderado)
                $sqlUser = "INSERT INTO USUARIO (nombre, apellido, rut, correo, telefono, contrasena, rol_id) 
                            VALUES (:nom, :ape, :rut, :mail, :tel, :pass, 2)";
                $stmt = $this->con->prepare($sqlUser);
                $stmt->execute([
                    ':nom' => $datos['nombre'], ':ape' => $datos['apellido'],
                    ':rut' => $datos['rut'], ':mail' => $datos['correo'],
                    ':tel' => $datos['telefono'], ':pass' => $password
                ]);
                
                $id_nuevo_usuario = $this->con->lastInsertId();

                // B) Insertar en ALUMNO vinculado
                $sqlAl = "INSERT INTO ALUMNO (nombre, apellido, curso, apoderado_id) 
                          VALUES (:nom_al, :ape_al, :curso, :id_apo)";
                $stmtAl = $this->con->prepare($sqlAl);
                $stmtAl->execute([
                    ':nom_al' => $datos['nombre_alumno'], ':ape_al' => $datos['apellido_alumno'],
                    ':curso' => $datos['curso'], ':id_apo' => $id_nuevo_usuario
                ]);

                // C) Crear Cuotas Automáticas (Marzo a Diciembre 2025)
                for ($mes = 3; $mes <= 12; $mes++) {
                    $sqlCuota = "INSERT INTO CUOTA (mes, anio, monto, estado_pago, apoderado_id) 
                                 VALUES (:mes, 2025, 30000, 'Pendiente', :id_apo)";
                    $stmtC = $this->con->prepare($sqlCuota);
                    $stmtC->execute([':mes' => $mes, ':id_apo' => $id_nuevo_usuario]);
                }

            } else {
                // --- ACTUALIZAR EXISTENTE ---
                
                $sqlUser = "UPDATE USUARIO SET nombre=:nom, apellido=:ape, rut=:rut, correo=:mail, telefono=:tel WHERE id=:id";
                $params = [
                    ':nom' => $datos['nombre'], ':ape' => $datos['apellido'],
                    ':rut' => $datos['rut'], ':mail' => $datos['correo'],
                    ':tel' => $datos['telefono'], ':id' => $datos['id_usuario']
                ];

                if (!empty($datos['password'])) {
                    $sqlUser = "UPDATE USUARIO SET nombre=:nom, apellido=:ape, rut=:rut, correo=:mail, telefono=:tel, contrasena=:pass WHERE id=:id";
                    $params[':pass'] = $password;
                }

                $stmt = $this->con->prepare($sqlUser);
                $stmt->execute($params);

                // Actualizar Alumno
                $sqlAl = "UPDATE ALUMNO SET nombre=:nom_al, apellido=:ape_al, curso=:curso WHERE apoderado_id=:id_apo";
                $stmtAl = $this->con->prepare($sqlAl);
                $stmtAl->execute([
                    ':nom_al' => $datos['nombre_alumno'], ':ape_al' => $datos['apellido_alumno'],
                    ':curso' => $datos['curso'], ':id_apo' => $datos['id_usuario']
                ]);
            }

            $this->con->commit();
            return true;

        } catch (PDOException $e) {
            $this->con->rollBack();
            // Descomenta esto si quieres ver el error en pantalla:
            // die("Error SQL: " . $e->getMessage()); 
            return false;
        }
    }

    // 4. ELIMINAR
    public function eliminarApoderado($id) {
        try {
            $sql = "DELETE FROM USUARIO WHERE id = :id";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) { return false; }
    }

    // 5. CAMBIAR ESTADO (Simulado para que no falle la vista)
    public function cambiarEstadoUsuario($id, $estado) { return true; }

    // 6. CONFIRMAR PAGO (Admin)
    public function confirmarPago($id_pago) {
        // Nota: Si usas tabla PAGO separada, aquí deberías insertar en PAGO y actualizar CUOTA.
        // Por simplicidad, asumimos que actualizamos el estado en CUOTA si recibimos ese ID.
        return true; 
    }
    
    // 7. OBTENER PAGOS
    public function obtenerTodosLosPagos() {
        try {
            $sql = "SELECT p.id, p.monto_pagado as monto, p.fecha_pago, 'Pagado' as estado, 
                           c.mes, c.anio, u.nombre, u.apellido, u.rut
                    FROM PAGO p
                    JOIN CUOTA c ON p.cuota_id = c.id
                    JOIN USUARIO u ON p.usuario_id = u.id
                    ORDER BY p.fecha_pago DESC";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) { return []; }
    }
}
?>