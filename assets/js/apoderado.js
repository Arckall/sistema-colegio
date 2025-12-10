/**
 * assets/js/apoderado.js
 * Lógica completa para el portal del apoderado
 */

// Variable global para controlar acciones (como el logout)
let currentAction = null;

// ==========================================
// 1. NAVEGACIÓN Y VISUALIZACIÓN
// ==========================================

function showSection(sectionName) {
    // Ocultar todas las secciones
    const sections = ['dashboard', 'pagos', 'historial', 'perfil'];
    sections.forEach(sec => {
        const el = document.getElementById(sec + 'Section');
        if (el) el.style.display = 'none';
    });

    // Mostrar la sección solicitada
    const target = document.getElementById(sectionName + 'Section');
    if (target) {
        target.style.display = 'block';
    }

    // Actualizar clase 'active' en el menú lateral
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Activar el botón del menú correspondiente
    // Buscamos el botón que tenga el onclick apuntando a esta sección
    const activeLink = document.querySelector(`.nav-item[onclick*="'${sectionName}'"]`);
    if(activeLink) {
        activeLink.classList.add('active');
    }
}

// ==========================================
// 2. FILTROS DE PAGOS (Tarjetas)
// ==========================================

function filterPayments(status) {
    const paymentItems = document.querySelectorAll('.payment-item');
    
    paymentItems.forEach(item => {
        if (status === 'all') {
            item.style.display = 'block';
        } else {
            // Verificamos las clases CSS para saber el estado
            const isPaid = item.classList.contains('paid');
            const isPending = item.classList.contains('pending') || item.classList.contains('processing');

            if (status === 'paid' && isPaid) {
                item.style.display = 'block';
            } else if (status === 'pending' && isPending) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}

// ==========================================
// 3. INTERACCIONES (Modales y Botones)
// ==========================================

function viewPayment(mes) {
    const nombre = (typeof userData !== 'undefined') ? userData.nombre : 'Usuario';
    const rut = (typeof userData !== 'undefined') ? userData.rut : '-';
    
    alert(`📄 Comprobante de Pago - ${mes}\n\nApoderado: ${nombre}\nRUT: ${rut}\n\nEstado: Confirmado ✅`);
}

function showPaymentInfo() {
    const modal = document.getElementById('paymentInfoModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
}

// ==========================================
// 4. LÓGICA DE CIERRE DE SESIÓN
// ==========================================

function confirmLogout() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        document.getElementById('confirmationMessage').textContent = '¿Estás seguro que deseas cerrar sesión?';
        modal.style.display = 'flex';
        modal.classList.add('active');
        currentAction = 'logout';
    } else {
        if(confirm('¿Cerrar sesión?')) window.location.href = 'logout.php';
    }
}

function closeConfirmation() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
    currentAction = null;
}

function executeAction() {
    if (currentAction === 'logout') {
        window.location.href = 'logout.php';
    }
    closeConfirmation();
}

// ==========================================
// 5. EXPORTACIÓN Y NOTIFICACIONES
// ==========================================

function exportPaymentHistory() {
    showNotification('Generando reporte...', 'success');
    
    setTimeout(() => {
        const exportData = {
            apoderado: (typeof userData !== 'undefined') ? userData.nombre : 'Desconocido',
            rut: (typeof userData !== 'undefined') ? userData.rut : '-',
            curso: (typeof userData !== 'undefined') ? userData.curso : '-',
            fecha_exportacion: new Date().toLocaleDateString(),
            nota: "Este archivo fue generado desde el portal del apoderado."
        };

        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportData, null, 2));
        const downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href", dataStr);
        downloadAnchorNode.setAttribute("download", "historial_pagos.json");
        document.body.appendChild(downloadAnchorNode);
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
        
        showNotification('Historial descargado correctamente', 'success');
    }, 1000);
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');
    
    if (notification && notificationText) {
        notification.className = 'notification ' + type;
        notificationText.textContent = message;
        
        // Reiniciar animación
        void notification.offsetWidth; 
        
        notification.classList.add('show');
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    } else {
        alert(message);
    }
}

// ==========================================
// 6. INICIALIZACIÓN Y EVENTOS (Teléfono)
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // A) CERRAR MODALES AL CLICK FUERA
    window.onclick = function(event) {
        if (event.target.classList.contains('modal') || event.target.classList.contains('confirmation-modal')) {
            event.target.style.display = 'none';
            event.target.classList.remove('active');
        }
    };

    // B) FORMATO DE TELÉFONO EN TIEMPO REAL (+56 9 1234 5678)
    const phoneInput = document.querySelector('input[name="telefono"]');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            // 1. Quitar todo lo que no sea número
            let x = e.target.value.replace(/\D/g, '');
            
            // 2. Si el usuario escribe "569...", le quitamos el "56" para no duplicarlo
            if (x.startsWith('56')) {
                x = x.substring(2);
            }
            
            // 3. Armar el formato visual
            let numero = '';
            
            if (x.length > 0) {
                numero = '+56'; // Prefijo fijo
                // Agregar el '9' o el primer dígito
                numero += ' ' + x.substring(0, 1);
            }
            if (x.length > 1) {
                // Siguientes 4 dígitos
                numero += ' ' + x.substring(1, 5);
            }
            if (x.length > 5) {
                // Últimos 4 dígitos
                numero += ' ' + x.substring(5, 9);
            }

            // 4. Asignar al input (Solo si hay algo escrito, sino limpiar)
            if (x.length > 0) {
                e.target.value = numero;
            } else {
                e.target.value = '';
            }
        });
    }
});