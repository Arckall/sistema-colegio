<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Colegio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"><i class="fas fa-school"></i></div>
                <h3 class="sidebar-title">Panel Administrativo</h3>
                <p class="sidebar-subtitle">Colegio Educativo 2025</p>
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item active" onclick="showSection('dashboard')">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('apoderados')">
                    <i class="fas fa-users"></i><span>Apoderados</span>
                    <span class="nav-badge" id="apoderadosCount"><?php echo $total_apoderados; ?></span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('pagos')">
                    <i class="fas fa-credit-card"></i><span>Pagos</span>
                    <span class="nav-badge" id="pagosPendientes">12</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('configuracion')">
                    <i class="fas fa-cog"></i><span>Configuración</span>
                </a>
                <a href="#" class="nav-item" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span>
                </a>
            </nav>
        </div>

        <div class="main-content">
            <?php if ($mensaje_exito): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $mensaje_exito ?></div>
            <?php endif; ?>
            <?php if ($mensaje_error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $mensaje_error ?></div>
            <?php endif; ?>

            <div id="dashboardSection">
                <div class="header">
                    <h1 class="header-title">Dashboard Administrativo</h1>
                    <div class="header-actions">
                        <button class="btn-header btn-primary" onclick="exportData()"><i class="fas fa-download"></i>
                            Exportar Datos</button>
                        <button class="btn-header btn-secondary" onclick="refreshData()"><i class="fas fa-sync-alt"></i>
                            Actualizar</button>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                            <div class="stat-change positive">+5.2%</div>
                        </div>
                        <div class="stat-number" id="totalApoderados"><?php echo $total_apoderados; ?></div>
                        <div class="stat-label">Total Apoderados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success"><i class="fas fa-credit-card"></i></div>
                            <div class="stat-change positive">+12.8%</div>
                        </div>
                        <div class="stat-number" id="totalPagos"><?php echo $total_pagos; ?></div>
                        <div class="stat-label">Pagos Procesados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning"><i class="fas fa-dollar-sign"></i></div>
                            <div class="stat-change positive">+8.5%</div>
                        </div>
                        <div class="stat-number" id="totalRecaudado">
                            $<?php echo number_format($total_recaudado, 0, ',', '.'); ?></div>
                        <div class="stat-label">Total Recaudado</div>
                    </div>
                </div>

                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Apoderados Registrados (Resumen)</h3>
                        <button class="btn-header btn-primary" onclick="showAddModal()"><i class="fas fa-plus"></i>
                            Nuevo Apoderado</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Apoderado</th>
                                    <th>RUT</th>
                                    <th>Email</th>
                                    <th>Pagos Realizados</th>
                                    <th>Total Pagado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apoderados as $apoderado): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?php echo strtoupper(substr($apoderado['nombre'], 0, 1) . substr($apoderado['apellido'], 0, 1)); ?>
                                                </div>
                                                <div class="user-details">
                                                    <h4><?php echo $apoderado['nombre'] . ' ' . $apoderado['apellido']; ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $apoderado['rut']; ?></td>
                                        <td><?php echo $apoderado['correo']; ?></td>
                                        <td><?php echo $apoderado['pagos_realizados']; ?></td>
                                        <td>$<?php echo number_format($apoderado['total_pagado'] ?? 0, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="apoderadosSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Gestión de Apoderados</h1>
                    <div class="header-actions">
                        <button class="btn-header btn-primary" onclick="showAddModal()"><i class="fas fa-plus"></i>
                            Agregar Apoderado</button>
                        <button class="btn-header btn-secondary" onclick="importData()"><i class="fas fa-upload"></i>
                            Importar</button>
                    </div>
                </div>

                <div class="content-section">
                    <div class="search-filter">
                        <div class="search-row">
                            <input type="text" class="search-input" placeholder="Buscar por nombre, RUT o curso..."
                                id="searchInput">
                            <button class="btn-header btn-primary" onclick="searchUsers()"><i class="fas fa-search"></i>
                                Buscar</button>
                        </div>
                        <div class="filter-buttons">
                            <button class="filter-btn active" onclick="filterUsers('all')">Todos</button>
                            <button class="filter-btn" onclick="filterUsers('active')">Activos</button>
                            <button class="filter-btn" onclick="filterUsers('inactive')">Inactivos</button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Apoderado</th>
                                    <th>Alumno</th>
                                    <th>RUT</th>
                                    <th>Curso</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <?php foreach ($apoderados as $apoderado): ?>
                                    <tr data-status="active">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?php echo strtoupper(substr($apoderado['nombre'], 0, 1) . substr($apoderado['apellido'], 0, 1)); ?>
                                                </div>
                                                <div class="user-details">
                                                    <h4><?php echo $apoderado['nombre'] . ' ' . $apoderado['apellido']; ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $apoderado['nombre_alumno_db'] . ' ' . $apoderado['apellido_alumno_db']; ?>
                                        </td>
                                        <td><?php echo $apoderado['rut']; ?></td>
                                        <td><?php echo $apoderado['curso']; ?></td>
                                        <td><?php echo $apoderado['telefono'] ?: 'Sin teléfono'; ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-view" onclick="viewUser(
                                                '<?php echo $apoderado['nombre'] . ' ' . $apoderado['apellido']; ?>',
                                                '<?php echo $apoderado['rut']; ?>',
                                                '<?php echo $apoderado['correo']; ?>',
                                                '<?php echo $apoderado['telefono']; ?>',
                                                '<?php echo $apoderado['nombre_alumno_db'] . ' ' . $apoderado['apellido_alumno_db']; ?>',
                                                '<?php echo $apoderado['curso']; ?>',
                                                '<?php echo $apoderado['pagos_realizados']; ?>',
                                                '<?php echo $apoderado['total_pagado']; ?>'
                                            )"><i class="fas fa-eye"></i> Ver</button>

                                                <button class="btn-action btn-edit" onclick="editUser(
                                                '<?php echo $apoderado['id']; ?>', 
                                                '<?php echo $apoderado['nombre']; ?>', 
                                                '<?php echo $apoderado['apellido']; ?>', 
                                                '<?php echo $apoderado['rut']; ?>', 
                                                '<?php echo $apoderado['curso']; ?>', 
                                                '<?php echo $apoderado['telefono']; ?>', 
                                                '<?php echo $apoderado['correo']; ?>',
                                                '<?php echo $apoderado['nombre_alumno_db']; ?>',
                                                '<?php echo $apoderado['apellido_alumno_db']; ?>'
                                            )"><i class="fas fa-edit"></i> Editar</button>

                                                <button class="btn-action btn-delete"
                                                    onclick="confirmDeleteUser('<?php echo $apoderado['id']; ?>')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="pagosSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Gestión de Pagos</h1>
                    <div class="header-actions">
                    </div>
                </div>
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Todos los Pagos</h3>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Apoderado</th>
                                    <th>Mes</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pagosTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="configuracionSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Configuración del Sistema</h1>
                </div>
                <div class="content-section">
                    <div style="padding: 30px; text-align: center; color: #666;">
                        <i class="fas fa-cog" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3>Configuración del Sistema</h3>
                        <h4>(En desarrollo)</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">Gestión de Apoderado</div>
            <form method="POST" action="admin.php">
                <input type="hidden" name="guardar_apoderado" value="1">
                <input type="hidden" name="accion" id="accion" value="agregar">
                <input type="hidden" name="usuario_id" id="usuario_id" value="">

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <h4
                        style="margin-bottom: 10px; color: #667eea; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                        Datos del Apoderado</h4>
                    <div class="form-row"><label class="form-label">Nombre *</label><input type="text"
                            class="form-input" name="nombre" id="userNombre" required></div>
                    <div class="form-row"><label class="form-label">Apellido *</label><input type="text"
                            class="form-input" name="apellido" id="userApellido" required></div>
                    <div class="form-row"><label class="form-label">RUT *</label><input type="text" class="form-input"
                            name="rut" id="userRUT" placeholder="12.345.678-9" required></div>
                    <div class="form-row"><label class="form-label">Teléfono</label><input type="tel" class="form-input"
                            name="telefono" id="userPhone" placeholder="+56 9 1234 5678"></div>
                    <div class="form-row"><label class="form-label">Email</label><input type="email" class="form-input"
                            name="email" id="userEmail" placeholder="correo@ejemplo.com"></div>
                    <div class="form-row"><label class="form-label">Contraseña</label><input type="password"
                            class="form-input" name="password" id="userPassword" placeholder="Mínimo 6 caracteres">
                    </div>
                </div>

                <div style="background: #fff5f5; padding: 15px; border-radius: 8px; border: 1px solid #ffebeb;">
                    <h4
                        style="margin-bottom: 10px; color: #e53e3e; border-bottom: 1px solid #fecaca; padding-bottom: 5px;">
                        Datos del Alumno</h4>
                    <div class="form-row"><label class="form-label">Nombre Alumno *</label><input type="text"
                            class="form-input" name="nombre_alumno" id="studentName" required></div>
                    <div class="form-row"><label class="form-label">Apellido Alumno *</label><input type="text"
                            class="form-input" name="apellido_alumno" id="studentSurname" required></div>
                    <div class="form-row">
                        <label class="form-label">Curso *</label>
                        <select class="form-select" name="curso" id="userCurso" required>
                            <option value="">Seleccionar curso</option>
                            <option value="Pre Kinder">Pre Kinder</option>
                            <option value="Kinder">Kinder</option>
                            <option value="Primero Básico">Primero Básico</option>
                            <option value="Segundo Básico">Segundo Básico</option>
                            <option value="Tercero Básico">Tercero Básico</option>
                            <option value="Cuarto Básico">Cuarto Básico</option>
                            <option value="Quinto Básico">Quinto Básico</option>
                            <option value="Sexto Básico">Sexto Básico</option>
                            <option value="Séptimo Básico">Séptimo Básico</option>
                            <option value="Octavo Básico">Octavo Básico</option>
                        </select>
                    </div>
                </div>

                <div class="modal-actions" style="margin-top: 20px;">
                    <button type="button" class="btn-modal btn-cancel"
                        onclick="closeModal('userModal')">Cancelar</button>
                    <button type="submit" class="btn-modal btn-submit" id="submitBtn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="viewProfileModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">Perfil del Apoderado</div>
            <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <div
                    style="width: 80px; height: 80px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold; margin-bottom: 10px;">
                    <i class="fas fa-user"></i></div>
                <h2 id="viewProfileName" style="color: #333;">-</h2>
                <p id="viewProfileRut" style="color: #666;">-</p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                    <h4 style="color: #667eea; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">
                        Contacto</h4>
                    <p style="margin-bottom: 8px;"><i class="fas fa-envelope" style="width: 20px; color: #999;"></i>
                        <span id="viewProfileEmail">-</span></p>
                    <p><i class="fas fa-phone" style="width: 20px; color: #999;"></i> <span
                            id="viewProfilePhone">-</span></p>
                </div>
                <div style="background: #fff5f5; padding: 15px; border-radius: 10px;">
                    <h4 style="color: #e53e3e; margin-bottom: 10px; font-size: 14px; text-transform: uppercase;">Alumno
                    </h4>
                    <p style="margin-bottom: 8px;"><strong>Nombre:</strong> <span id="viewProfileStudent">-</span></p>
                    <p><strong>Curso:</strong> <span id="viewProfileCourse">-</span></p>
                </div>
            </div>
            <div
                style="margin-top: 15px; background: #f0fdf4; padding: 15px; border-radius: 10px; border: 1px solid #dcfce7;">
                <div style="display: flex; justify-content: space-between;">
                    <div><small style="color: #166534;">Cuotas Pagadas</small>
                        <div id="viewProfilePaidCount" style="font-weight: bold; font-size: 18px; color: #15803d;">0
                        </div>
                    </div>
                    <div style="text-align: right;"><small style="color: #166534;">Total Aportado</small>
                        <div id="viewProfileTotalAmount" style="font-weight: bold; font-size: 18px; color: #15803d;">$0
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-actions"><button type="button" class="btn-modal btn-submit"
                    onclick="closeModal('viewProfileModal')">Cerrar</button></div>
        </div>
    </div>

    <div class="modal" id="viewPagoModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">Detalles del Pago</div>
            <div style="text-align: center; margin: 20px 0;">
                <div
                    style="width: 80px; height: 80px; background: #f0f2f5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 30px; color: #667eea;">
                    <i class="fas fa-receipt"></i></div>
                <h2 id="viewPagoMonto" style="color: #333; font-size: 28px; margin-bottom: 5px;">$0</h2>
                <p id="viewPagoMes" style="color: #666; font-size: 16px;">Mes</p>
            </div>
            <div style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                <div
                    style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <span style="color: #666;">Apoderado:</span><span style="font-weight: 600; color: #333;"
                        id="viewPagoApoderado">-</span></div>
                <div
                    style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <span style="color: #666;">Fecha:</span><span style="font-weight: 600; color: #333;"
                        id="viewPagoFecha">-</span></div>
                <div style="display: flex; justify-content: space-between; align-items: center;"><span
                        style="color: #666;">Estado Actual:</span><span id="viewPagoEstadoBadge"
                        class="status-badge">-</span></div>
            </div>
            <div class="modal-actions"><button type="button" class="btn-modal btn-submit"
                    onclick="closeModal('viewPagoModal')">Cerrar</button></div>
        </div>
    </div>

    <div class="modal" id="editPagoModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">Actualizar Estado de Pago</div>
            <input type="hidden" id="editPagoIndex">
            <div style="margin: 25px 0;">
                <p style="margin-bottom: 15px; color: #666;">Seleccione el nuevo estado para el pago de <strong
                        id="editPagoNombre"></strong>:</p>
                <div class="form-group">
                    <label class="form-label">Estado del Pago</label>
                    <select id="editPagoSelect" class="form-select"
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-size: 16px;">
                        <option value="Pendiente">Pendiente 🟠</option>
                        <option value="Pagado">Pagado 🟢</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancel"
                    onclick="closeModal('editPagoModal')">Cancelar</button>
                <button type="button" class="btn-modal btn-submit" onclick="savePagoStatus()">Guardar Cambios</button>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteUserModal">
        <div class="modal-content" style="max-width: 400px; text-align: center;">
            <div
                style="width: 60px; height: 60px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #dc2626; font-size: 24px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="color: #1f2937; margin-bottom: 10px; font-size: 20px;">¿Eliminar Apoderado?</h3>
            <p style="color: #6b7280; margin-bottom: 25px; line-height: 1.5;">
                Estás a punto de eliminar a este usuario y todos sus datos asociados (alumno, pagos). <strong>Esta
                    acción no se puede deshacer.</strong>
            </p>
            <form method="POST" action="admin.php">
                <input type="hidden" name="eliminar_apoderado" value="1">
                <input type="hidden" name="usuario_id" id="deleteUserIdInput">
                <div class="modal-actions" style="justify-content: center;">
                    <button type="button" class="btn-modal btn-cancel"
                        onclick="closeModal('deleteUserModal')">Cancelar</button>
                    <button type="submit" class="btn-modal" style="background: #dc2626; color: white; border: none;">Sí,
                        Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="confirmation-modal" id="logoutConfirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="confirmation-title">¿Confirmar cierre de sesión?</div>
            <div class="confirmation-message">¿Estás seguro que deseas cerrar sesión?</div>
            <div class="confirmation-actions">
                <button class="btn-confirmation btn-cancel" onclick="closeLogoutConfirmation()">Cancelar</button>
                <button class="btn-confirmation btn-confirm" onclick="logout()">Cerrar Sesión</button>
            </div>
        </div>
    </div>

    <div class="notification" id="notification"><i class="fas fa-check-circle"></i> <span
            id="notificationText">Operación exitosa</span></div>

    <script>
        const serverData = {
            apoderados: <?php echo json_encode($apoderados); ?>
        };
    </script>
    <script src="assets/js/admin.js"></script>
</body>

</html>