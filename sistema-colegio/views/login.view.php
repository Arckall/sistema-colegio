<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos - Colegio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="web-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"><i class="fas fa-school"></i></div>
                <h2 class="sidebar-title">Gestión de Pagos</h2>
                <p class="sidebar-subtitle">Colegio - 2025</p>
            </div>
        </div>

        <div class="main-content">
            <div class="login-container" id="loginSection">
                <div class="login-header">
                    <h1 class="login-title">Bienvenido al Sistema</h1>
                    <p class="login-subtitle">Ingrese sus credenciales para acceder</p>
                </div>

                <div class="login-form">
                    <form method="POST" action="login.php" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label class="form-label" for="rutInput">RUT</label>
                            <input type="text" class="form-input" name="rut" placeholder="12.345.678-9" required id="rutInput" maxlength="12">
                            <div class="error-message" id="rutError">RUT inválido.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="passwordInput">Contraseña</label>
                            <input type="password" class="form-input" name="password" placeholder="Ingrese contraseña" required id="passwordInput">
                            <div class="error-message" id="passwordError">Contraseña requerida.</div>
                        </div>

                        <button type="submit" class="btn-primary" id="loginBtn">
                            <i class="fas fa-sign-in-alt"></i> Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="notification <?php echo $error_msg ? 'error show' : ($success_msg ? 'show' : ''); ?>" id="notification">
        <i class="fas <?php echo $error_msg ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
        <span id="notificationText"><?php echo $error_msg ?: $success_msg; ?></span>
    </div>

    <script src="assets/js/login.js"></script>
</body>
</html>