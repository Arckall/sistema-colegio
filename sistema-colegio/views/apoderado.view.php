<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal del Apoderado - Colegio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/apoderado.css">
</head>

<body>
    <div class="apoderado-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"><i class="fas fa-school"></i></div>
                <h3 class="sidebar-title">Portal Apoderado</h3>
                <p class="sidebar-subtitle">Colegio Educativo 2025</p>
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item active" onclick="showSection('dashboard')">
                    <i class="fas fa-home"></i><span>Inicio</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('pagos')">
                    <i class="fas fa-credit-card"></i><span>Mis Pagos</span>
                    <span class="nav-badge" id="pagosPendientes"><?php echo $pagos_pendientes; ?></span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('historial')">
                    <i class="fas fa-history"></i><span>Historial</span>
                </a>
                <a href="#" class="nav-item" onclick="showSection('perfil')">
                    <i class="fas fa-user"></i><span>Mi Perfil</span>
                </a>
                <a href="#" class="nav-item" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span>
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div id="dashboardSection">
                <div class="welcome-card">
                    <div class="welcome-content">
                        <h1 class="welcome-title">¡Bienvenido <?php echo $_SESSION['usuario']['nombre']; ?>!</h1>
                        <p class="welcome-subtitle">Portal de gestión de pagos escolares</p>
                        <div class="welcome-stats">
                            <div class="welcome-stat">
                                <div class="welcome-stat-number" id="totalPagosRealizados">
                                    <?php echo $pagos_realizados; ?>
                                </div>
                                <div class="welcome-stat-label">Pagos Realizados</div>
                            </div>
                            <div class="welcome-stat">
                                <div class="welcome-stat-number" id="totalPagosPendientes">
                                    <?php echo $pagos_pendientes; ?>
                                </div>
                                <div class="welcome-stat-label">Pagos Pendientes</div>
                            </div>
                            <div class="welcome-stat">
                                <div class="welcome-stat-number" id="montoTotalPagado">
                                    $<?php echo number_format($monto_total_pagado, 0, ',', '.'); ?></div>
                                <div class="welcome-stat-label">Total Pagado</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
                            <div class="stat-change positive">Al día</div>
                        </div>
                        <div class="stat-number" id="estadoActual">Al Día</div>
                        <div class="stat-label">Estado de Cuenta</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success"><i class="fas fa-credit-card"></i></div>
                            <div class="stat-change positive">+2 este mes</div>
                        </div>
                        <div class="stat-number" id="pagosMes">2</div>
                        <div class="stat-label">Pagos este Mes</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
                            <div class="stat-change negative">Próximo: Junio</div>
                        </div>
                        <div class="stat-number" id="proximoPago">Junio 2025</div>
                        <div class="stat-label">Próximo Pago</div>
                    </div>
                </div>

                <div class="quick-actions">
                    <div class="quick-action" onclick="showSection('pagos')">
                        <div class="quick-action-icon"><i class="fas fa-credit-card"></i></div>
                        <div class="quick-action-title">Ver Mis Pagos</div>
                        <div class="quick-action-desc">Consulta el estado de tus pagos mensuales</div>
                    </div>
                    <div class="quick-action" onclick="showPaymentInfo()">
                        <div class="quick-action-icon"><i class="fas fa-info-circle"></i></div>
                        <div class="quick-action-title">Información de Pago</div>
                        <div class="quick-action-desc">Datos para realizar tus pagos</div>
                    </div>
                    <div class="quick-action" onclick="showSection('historial')">
                        <div class="quick-action-icon"><i class="fas fa-history"></i></div>
                        <div class="quick-action-title">Historial Completo</div>
                        <div class="quick-action-desc">Revisa todos tus pagos realizados</div>
                    </div>
                    <div class="quick-action" onclick="showSection('perfil')">
                        <div class="quick-action-icon"><i class="fas fa-user-cog"></i></div>
                        <div class="quick-action-title">Actualizar Datos</div>
                        <div class="quick-action-desc">Mantén tu información actualizada</div>
                    </div>
                </div>
            </div>

            <div id="pagosSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Mis Cuotas Mensuales</h1>
                    <div class="header-actions">
                        <button class="btn-header btn-success" onclick="showPaymentInfo()"><i
                                class="fas fa-info-circle"></i> Info de Pago</button>
                        <button class="btn-header btn-primary" onclick="showSection('dashboard')"><i
                                class="fas fa-arrow-left"></i> Volver</button>
                    </div>
                </div>

                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Estado de Cuotas 2025</h3>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn-header btn-secondary" onclick="filterPayments('all')">Todos</button>
                            <button class="btn-header btn-success" onclick="filterPayments('paid')">Pagados</button>
                            <button class="btn-header btn-warning"
                                onclick="filterPayments('pending')">Pendientes</button>
                        </div>
                    </div>

                    <div class="payment-list" id="paymentList">
                        <?php
                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        foreach ($cuotas as $cuota):
                            $estadoClass = ($cuota['estado_pago'] == 'Abonado') ? 'paid' : (($cuota['pagado'] > 0) ? 'processing' : 'pending');
                            $estadoText = ($cuota['estado_pago'] == 'Abonado') ? 'Pagado' : (($cuota['pagado'] > 0) ? 'En Proceso' : 'Pendiente');
                            $estadoBadge = ($cuota['estado_pago'] == 'Abonado') ? 'status-paid' : (($cuota['pagado'] > 0) ? 'status-processing' : 'status-pending');
                            ?>
                            <div class="payment-item <?php echo $estadoClass; ?>">
                                <div class="payment-header">
                                    <span
                                        class="payment-month"><?php echo $meses[$cuota['mes']] . ' ' . $cuota['anio']; ?></span>
                                    <span
                                        class="payment-status <?php echo $estadoBadge; ?>"><?php echo $estadoText; ?></span>
                                </div>
                                <div class="payment-amount">$<?php echo number_format($cuota['monto'], 0, ',', '.'); ?>
                                </div>
                                <div class="payment-details">
                                    <div class="payment-detail">
                                        <div class="payment-detail-label">Estado</div>
                                        <div class="payment-detail-value"><?php echo $estadoText; ?></div>
                                    </div>
                                    <div class="payment-detail">
                                        <div class="payment-detail-label">Monto Total</div>
                                        <div class="payment-detail-value">
                                            $<?php echo number_format($cuota['monto'], 0, ',', '.'); ?></div>
                                    </div>
                                    <div class="payment-detail">
                                        <div class="payment-detail-label">Saldo</div>
                                        <div class="payment-detail-value">
                                            $<?php echo number_format($cuota['monto'] - $cuota['pagado'], 0, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-actions">
                                    <?php if ($cuota['estado_pago'] == 'Abonado'): ?>
                                        <button class="btn-action btn-view"
                                            onclick="viewPayment('<?php echo $meses[$cuota['mes']] . ' ' . $cuota['anio']; ?>')"><i
                                                class="fas fa-eye"></i> Ver Comprobante</button>
                                    <?php elseif ($cuota['pagado'] > 0): ?>
                                        <button class="btn-action btn-view"
                                            onclick="viewPayment('<?php echo $meses[$cuota['mes']] . ' ' . $cuota['anio']; ?>')"><i
                                                class="fas fa-clock"></i> Ver Estado</button>
                                    <?php else: ?>
                                        <button class="btn-action btn-info" onclick="showPaymentInfo()"><i
                                                class="fas fa-info-circle"></i> Info Pago</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div id="historialSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Historial de Pagos</h1>
                    <div class="header-actions">
                        <button class="btn-header btn-secondary" onclick="exportPaymentHistory()"><i
                                class="fas fa-download"></i> Exportar</button>
                        <button class="btn-header btn-primary" onclick="showSection('dashboard')"><i
                                class="fas fa-arrow-left"></i> Volver</button>
                    </div>
                </div>
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Historial Completo de Pagos</h3>
                    </div>
                    <div class="payment-list">
                        <?php if (count($historial_pagos) > 0): ?>
                            <?php foreach ($historial_pagos as $pago): ?>
                                <div class="payment-item paid">
                                    <div class="payment-header">
                                        <span
                                            class="payment-month"><?php echo $meses[$pago['mes']] . ' ' . $pago['anio']; ?></span>
                                        <span class="payment-status status-paid">Pagado</span>
                                    </div>
                                    <div class="payment-amount">
                                        $<?php echo number_format($pago['monto_pagado'], 0, ',', '.'); ?></div>
                                    <div class="payment-details">
                                        <div class="payment-detail">
                                            <div class="payment-detail-label">Fecha de Pago</div>
                                            <div class="payment-detail-value">
                                                <?php echo date('d/m/Y', strtotime($pago['fecha_pago'])); ?>
                                            </div>
                                        </div>
                                        <div class="payment-detail">
                                            <div class="payment-detail-label">Método</div>
                                            <div class="payment-detail-value">
                                                <?php echo $pago['metodo_pago'] ?: 'Transferencia'; ?>
                                            </div>
                                        </div>
                                        <div class="payment-detail">
                                            <div class="payment-detail-label">Comprobante</div>
                                            <div class="payment_detal-value">#<?php echo $pago['id']; ?></div>
                                        </div>
                                    </div>
                                    <div class="payment-actions">
                                        <button class="btn-action btn-view"
                                            onclick="viewPayment('<?php echo $meses[$pago['mes']] . ' ' . $pago['anio']; ?>')"><i
                                                class="fas fa-eye"></i> Ver Comprobante</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: #666;">
                                <i class="fas fa-history" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                                <h4>No hay pagos registrados en el historial</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="perfilSection" style="display: none;">
                <div class="header">
                    <h1 class="header-title">Mi Perfil</h1>
                    <div class="header-actions">
                        <button class="btn-header btn-primary" onclick="showSection('dashboard')"><i
                                class="fas fa-arrow-left"></i> Volver</button>
                    </div>
                </div>
                <div class="content-section">
                    <div style="padding: 40px;">
                        <div style="max-width: 600px; margin: 0 auto;">
                            <h3 style="margin-bottom: 30px; text-align: center;">Información Personal</h3>

                            <?php if ($mensaje_exito): ?>
                                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $mensaje_exito ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($mensaje_error): ?>
                                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
                                    <?= $mensaje_error ?></div>
                            <?php endif; ?>

                            <form method="POST" action="apoderado.php">
                                <input type="hidden" name="actualizar_perfil" value="1">

                                <div class="form-group">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" class="form-input" name="nombre"
                                        value="<?php echo $_SESSION['usuario']['nombre']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Apellido *</label>
                                    <input type="text" class="form-input" name="apellido"
                                        value="<?php echo $_SESSION['usuario']['apellido']; ?>" required>
                                </div>

                                <div class="form-group"
                                    title="Para modificar esta información, contacte a administración">
                                    <label class="form-label">Email <i class="fas fa-lock"
                                            style="font-size: 12px; color: #999;"></i></label>
                                    <input type="email" class="form-input" name="email"
                                        value="<?php echo $datos_usuario['correo']; ?>" readonly
                                        style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed;">
                                </div>

                                <div class="form-group"
                                    title="Para modificar esta información, contacte a administración">
                                    <label class="form-label">Teléfono <i class="fas fa-lock"
                                            style="font-size: 12px; color: #999;"></i></label>
                                    <input type="text" class="form-input" name="telefono"
                                        value="<?php echo $telefono_visual; ?>" readonly
                                        style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed;">
                                </div>

                                <div style="text-align: center; margin-top: 30px;">
                                    <button type="submit" class="btn-header btn-success"><i class="fas fa-save"></i>
                                        Guardar Cambios</button>
                                </div>
                            </form>
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <button type="submit" class="btn-header btn-success"><i class="fas fa-save"></i>
                                Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal" id="paymentInfoModal">
        <div class="modal-content">
            <div class="modal-icon"><i class="fas fa-info-circle"></i></div>
            <h3 class="modal-header">Información de Pago</h3>
            <div class="modal-message">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: left;">
                    <h4 style="margin-bottom: 15px; color: #333;"><i class="fas fa-university"
                            style="margin-right: 10px; color: #667eea;"></i>Datos para Transferencia</h4>
                    <p><strong>Banco:</strong> Banco de Chile</p>
                    <p><strong>Tipo de Cuenta:</strong> Cuenta Corriente</p>
                    <p><strong>Número de Cuenta:</strong> 1234567890</p>
                    <p><strong>RUT:</strong> 76.543.210-1</p>
                    <p><strong>Nombre:</strong> Colegio Educativo 2025 S.A.</p>
                    <p><strong>Email para envío de comprobante:</strong> tesoreria@colegio.cl</p>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-modal btn-modal-primary" onclick="closeModal('paymentInfoModal')"><i
                        class="fas fa-check"></i> Entendido</button>
            </div>
        </div>
    </div>

    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="confirmation-title">¿Confirmar acción?</div>
            <div class="confirmation-message" id="confirmationMessage">¿Estás seguro que deseas realizar esta acción?
            </div>
            <div class="confirmation-actions">
                <button class="btn-confirmation btn-cancel" onclick="closeConfirmation()">Cancelar</button>
                <button class="btn-confirmation btn-confirm" onclick="executeAction()">Confirmar</button>
            </div>
        </div>
    </div>

    <div class="notification" id="notification"><i class="fas fa-check-circle"></i> <span
            id="notificationText">Operación exitosa</span></div>

    <script>
        const userData = {
            nombre: "<?php echo $_SESSION['usuario']['nombre']; ?>",
            rut: "<?php echo $_SESSION['usuario']['rut']; ?>",
            curso: "<?php echo $apoderado['curso']; ?>"
        };
    </script>
    <script src="assets/js/apoderado.js"></script>
</body>

</html>