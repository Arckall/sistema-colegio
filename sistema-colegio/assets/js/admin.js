// assets/js/admin.js

let currentUserToDelete = null;
let editingUser = null;
let currentSection = 'dashboard';

// Datos simulados para pagos
const pagosData = [
    { apoderado: 'Carolina Castro Farías', mes: 'Marzo', monto: 30000, estado: 'Pagado', fecha: '2025-02-28' },
    { apoderado: 'María Verdugo Estrada', mes: 'Marzo', monto: 30000, estado: 'En Proceso', fecha: '2025-02-28' },
    { apoderado: 'Juan Pérez González', mes: 'Abril', monto: 30000, estado: 'Pendiente', fecha: '2025-03-05' },
    { apoderado: 'Roberto Gómez', mes: 'Marzo', monto: 30000, estado: 'Pagado', fecha: '2025-02-25' }
];

// --- NAVEGACIÓN ---
function showSection(section) {
    document.querySelectorAll('[id$="Section"]').forEach(sec => sec.style.display = 'none');
    document.getElementById(section + 'Section').style.display = 'block';
    currentSection = section;
    document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
    const activeLink = document.querySelector(`.nav-item[onclick="showSection('${section}')"]`);
    if(activeLink) activeLink.classList.add('active');
    if (section === 'pagos') loadPagosTable();
}

// --- GESTIÓN DE PAGOS ---
function loadPagosTable() {
    const tbody = document.getElementById('pagosTableBody');
    if(!tbody) return; 
    tbody.innerHTML = '';
    pagosData.forEach((pago, index) => {
        const row = document.createElement('tr');
        let statusClass = '';
        if (pago.estado === 'Pagado') statusClass = 'status-active';
        else if (pago.estado === 'En Proceso') statusClass = 'status-pending';
        else statusClass = 'status-inactive';
        
        // Avatar con iniciales
        const iniciales = pago.apoderado.split(' ').map(n => n[0]).join('').substring(0, 2);

        row.innerHTML = `
            <td>
                <div class="user-info">
                    <div class="user-avatar">${iniciales}</div>
                    <div class="user-details"><h4>${pago.apoderado}</h4></div>
                </div>
            </td>
            <td>${pago.mes} 2025</td>
            <td>$${pago.monto.toLocaleString()}</td>
            <td><span class="status-badge ${statusClass}">${pago.estado}</span></td>
            <td>${new Date(pago.fecha).toLocaleDateString('es-CL')}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-view" onclick="viewPago(${index})"><i class="fas fa-eye"></i> Ver</button>
                    <button class="btn-action btn-edit" onclick="editPago(${index})"><i class="fas fa-edit"></i> Editar</button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Modal Ver Pago
function viewPago(index) {
    const pago = pagosData[index];
    if (!pago) return;
    document.getElementById('viewPagoMonto').textContent = '$' + pago.monto.toLocaleString();
    document.getElementById('viewPagoMes').textContent = 'Cuota de ' + pago.mes + ' 2025';
    document.getElementById('viewPagoApoderado').textContent = pago.apoderado;
    document.getElementById('viewPagoFecha').textContent = new Date(pago.fecha).toLocaleDateString('es-CL');
    
    const badge = document.getElementById('viewPagoEstadoBadge');
    badge.textContent = pago.estado;
    badge.className = 'status-badge';
    if (pago.estado === 'Pagado') badge.classList.add('status-active');
    else if (pago.estado === 'En Proceso') badge.classList.add('status-pending');
    else badge.classList.add('status-inactive');
    
    document.getElementById('viewPagoModal').classList.add('active');
}

// Modal Editar Pago
function editPago(index) {
    const pago = pagosData[index];
    if (!pago) return;
    document.getElementById('editPagoIndex').value = index;
    document.getElementById('editPagoNombre').textContent = pago.apoderado;
    document.getElementById('editPagoSelect').value = pago.estado;
    document.getElementById('editPagoModal').classList.add('active');
}

function savePagoStatus() {
    const index = document.getElementById('editPagoIndex').value;
    const nuevoEstado = document.getElementById('editPagoSelect').value;
    if (index !== '' && pagosData[index]) {
        pagosData[index].estado = nuevoEstado;
        loadPagosTable();
        closeModal('editPagoModal');
        showNotification('Estado actualizado correctamente', 'success');
    }
}

// --- GESTIÓN DE USUARIOS (MODALES) ---

function showAddModal() {
    editingUser = null;
    document.getElementById('modalHeader').textContent = 'Agregar Nuevo Apoderado';
    document.getElementById('submitBtn').textContent = 'Agregar Apoderado';
    document.getElementById('accion').value = 'agregar';
    clearForm();
    document.getElementById('userModal').classList.add('active');
}

// Función EDITAR (Recuperada para Apoderado + Alumno)
function editUser(usuarioId, nombre, apellido, rut, curso, telefono, email, nombreAlumno, apellidoAlumno) {
    editingUser = usuarioId;
    document.getElementById('modalHeader').textContent = 'Editar Apoderado';
    document.getElementById('submitBtn').textContent = 'Guardar Cambios';
    document.getElementById('accion').value = 'editar';
    document.getElementById('usuario_id').value = usuarioId;
    
    // Datos Apoderado
    document.getElementById('userNombre').value = nombre;
    document.getElementById('userApellido').value = apellido;
    document.getElementById('userRUT').value = rut;
    document.getElementById('userPhone').value = telefono || '';
    document.getElementById('userEmail').value = email || '';
    
    // Contraseña opcional
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = false;
    document.getElementById('userPassword').placeholder = "Dejar en blanco para no cambiar";
    document.getElementById('userRUT').disabled = false;
    
    // Datos Alumno
    document.getElementById('studentName').value = nombreAlumno || '';
    document.getElementById('studentSurname').value = apellidoAlumno || '';
    document.getElementById('userCurso').value = curso;
    
    document.getElementById('userModal').classList.add('active');
}

// --- AQUÍ ESTABAN LAS FUNCIONES QUE FALTABAN (RECUPERADAS) ---

// Modal Ver Perfil Bonito
function viewUser(nombre, rut, email, telefono, alumno, curso, pagos, total) {
    document.getElementById('viewProfileName').textContent = nombre;
    document.getElementById('viewProfileRut').textContent = 'RUT: ' + rut;
    document.getElementById('viewProfileEmail').textContent = email || 'No registrado';
    document.getElementById('viewProfilePhone').textContent = telefono || 'No registrado';
    document.getElementById('viewProfileStudent').textContent = alumno;
    document.getElementById('viewProfileCourse').textContent = curso;
    document.getElementById('viewProfilePaidCount').textContent = pagos + ' Cuotas';
    
    const monto = parseInt(total) || 0;
    document.getElementById('viewProfileTotalAmount').textContent = '$' + monto.toLocaleString('es-CL');

    document.getElementById('viewProfileModal').classList.add('active');
}

// Modal Confirmar Eliminación (Rojo)
function confirmDeleteUser(id) {
    document.getElementById('deleteUserIdInput').value = id;
    document.getElementById('deleteUserModal').classList.add('active');
}

// -------------------------------------------------------------

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    if(modalId === 'userModal') clearForm();
}

function clearForm() {
    // Limpiar Apoderado
    document.getElementById('userNombre').value = '';
    document.getElementById('userApellido').value = '';
    document.getElementById('userRUT').value = '';
    document.getElementById('userPhone').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('userPassword').placeholder = "Mínimo 6 caracteres";
    
    document.getElementById('userRUT').disabled = false;
    document.getElementById('userRUT').style.borderColor = '#e0e0e0';
    
    // Limpiar Alumno
    document.getElementById('studentName').value = '';
    document.getElementById('studentSurname').value = '';
    document.getElementById('userCurso').value = '';
    
    document.getElementById('usuario_id').value = '';
}

// --- VALIDACIÓN RUT (LÓGICA) ---
function validarRUTLogica(rut) {
    const rutLimpio = rut.replace(/[^0-9kK]/g, '');
    if (rutLimpio.length < 8) return false;

    const cuerpo = rutLimpio.slice(0, -1);
    const dv = rutLimpio.slice(-1).toUpperCase();
    
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

// --- UTILIDADES ---
function filterUsers(status) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    const target = event.currentTarget || event.target;
    target.classList.add('active');
    
    const rows = document.querySelectorAll('#usersTableBody tr');
    rows.forEach(row => {
        const userStatus = row.getAttribute('data-status');
        if (status === 'all' || userStatus === status) row.style.display = '';
        else row.style.display = 'none';
    });
}

function searchUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function confirmLogout() { document.getElementById('logoutConfirmationModal').classList.add('active'); }
function closeLogoutConfirmation() { document.getElementById('logoutConfirmationModal').classList.remove('active'); }
function logout() { window.location.href = 'logout.php'; }
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');
    notification.className = 'notification ' + type + ' show';
    notificationText.textContent = message;
    setTimeout(() => notification.classList.remove('show'), 4000);
}
function refreshData() {
    showNotification('Datos actualizados', 'success');
    if (currentSection === 'pagos') loadPagosTable();
}
function exportData() { showNotification('Exportando datos...', 'success'); }


// --- INICIALIZACIÓN ---
document.addEventListener('DOMContentLoaded', function() {
    loadPagosTable();

    // LÓGICA DE FORMATEO Y VALIDACIÓN DE RUT EN TIEMPO REAL
    const rutInput = document.getElementById('userRUT');
    if (rutInput) {
        rutInput.addEventListener('input', function(e) {
            this.style.borderColor = '#e0e0e0';
            let rut = e.target.value.replace(/[^0-9kK]/g, '');
            if (rut.length > 9) rut = rut.slice(0, 9); // Límite
            
            if (rut.length > 1) {
                const cuerpo = rut.slice(0, -1);
                const dv = rut.slice(-1).toUpperCase();
                const cuerpoFormateado = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                e.target.value = cuerpoFormateado + "-" + dv;
            } else {
                e.target.value = rut;
            }
        });

        rutInput.addEventListener('blur', function(e) {
            if (this.value.length > 0) {
                if (!validarRUTLogica(this.value)) {
                    this.style.borderColor = '#dc3545';
                    showNotification('El RUT ingresado no es válido', 'error');
                } else {
                    this.style.borderColor = '#28a745';
                }
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) event.target.classList.remove('active');
    if (event.target.classList.contains('confirmation-modal')) closeLogoutConfirmation();
});