// assets/js/login.js

function validarRUT(rut) {
    const rutLimpio = rut.replace(/[^0-9kK]/g, '');
    const cuerpo = rutLimpio.slice(0, -1);
    const dv = rutLimpio.slice(-1).toUpperCase();
    
    if (cuerpo.length < 7 || cuerpo.length > 9) return false;
    
    let suma = 0;
    let multiplo = 2;
    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo.charAt(i)) * multiplo;
        multiplo = multiplo === 7 ? 2 : multiplo + 1;
    }
    
    const dvCalculado = 11 - (suma % 11);
    let dvEsperado = '';
    if (dvCalculado === 11) dvEsperado = '0';
    else if (dvCalculado === 10) dvEsperado = 'K';
    else dvEsperado = dvCalculado.toString();
    
    return dv === dvEsperado;
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');
    
    notification.className = 'notification';
    notification.classList.add(type);
    notificationText.textContent = message;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 4000);
}

function validateForm() {
    const rut = document.getElementById('rutInput').value;
    const password = document.getElementById('passwordInput').value;
    let hasError = false;
    
    if (!rut) {
        document.getElementById('rutError').textContent = 'El RUT es obligatorio';
        document.getElementById('rutError').classList.add('show');
        document.getElementById('rutInput').classList.add('error');
        hasError = true;
    } else if (!validarRUT(rut)) {
        document.getElementById('rutError').textContent = 'RUT inválido. Verifique el formato';
        document.getElementById('rutError').classList.add('show');
        document.getElementById('rutInput').classList.add('error');
        hasError = true;
    }
    
    if (!password) {
        document.getElementById('passwordError').textContent = 'La contraseña es obligatoria';
        document.getElementById('passwordError').classList.add('show');
        document.getElementById('passwordInput').classList.add('error');
        hasError = true;
    }
    
    if (hasError) {
        showNotification('Por favor, corrija los errores en el formulario', 'error');
        return false;
    }
    
    const loginBtn = document.getElementById('loginBtn');
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    return true;
}

function showRecoverPassword() {
    const rut = prompt('Ingrese su RUT para recuperar contraseña:');
    if (rut && validarRUT(rut)) {
        showNotification('Se ha enviado un correo con instrucciones', 'success');
    } else if (rut) {
        showNotification('RUT inválido. Verifique el formato', 'error');
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const rutInput = document.getElementById('rutInput');
    const passwordInput = document.getElementById('passwordInput');

    // Formatear RUT en tiempo real
    rutInput.addEventListener('input', function(e) {
        let rut = e.target.value.replace(/[^0-9kK]/g, '');
        if (rut.length > 1) {
            const cuerpo = rut.slice(0, -1);
            const dv = rut.slice(-1);
            let cuerpoFormateado = cuerpo.length > 6 
                ? cuerpo.slice(0, -6) + '.' + cuerpo.slice(-6, -3) + '.' + cuerpo.slice(-3)
                : cuerpo.length > 3 
                    ? cuerpo.slice(0, -3) + '.' + cuerpo.slice(-3) 
                    : cuerpo;
            e.target.value = cuerpoFormateado + '-' + dv;
        } else {
            e.target.value = rut;
        }
        document.getElementById('rutError').classList.remove('show');
        e.target.classList.remove('error');
    });

    // Validar RUT al perder el foco
    rutInput.addEventListener('blur', function(e) {
        if (e.target.value && !validarRUT(e.target.value)) {
            document.getElementById('rutError').textContent = 'RUT inválido. Verifique el formato';
            document.getElementById('rutError').classList.add('show');
            e.target.classList.add('error');
        }
    });

    // Limpiar errores de contraseña
    passwordInput.addEventListener('input', function(e) {
        document.getElementById('passwordError').classList.remove('show');
        e.target.classList.remove('error');
    });

    // Manejo de errores por URL
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const success = urlParams.get('success');
    
    if (error) {
        let message = '';
        switch(error) {
            case 'login_failed': message = 'RUT o contraseña incorrectos'; break;
            case 'invalid_rut': message = 'RUT inválido'; break;
            case 'user_not_found': message = 'Usuario no encontrado'; break;
            case 'database_error': message = 'Error de conexión a la base de datos'; break;
            case 'empty_fields': message = 'Complete todos los campos'; break;
            default: message = 'Error al iniciar sesión';
        }
        showNotification(message, 'error');
    }
    if (success === 'logout') {
        showNotification('Sesión cerrada exitosamente', 'success');
    }
});