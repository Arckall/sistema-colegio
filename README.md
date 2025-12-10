# 🏫 Sistema de Gestión Escolar

> Una plataforma web robusta para la administración académica y la comunicación entre el establecimiento y los apoderados.

![Estado del Proyecto](https://img.shields.io/badge/Estado-Terminado-success?style=for-the-badge)
![Licencia](https://img.shields.io/badge/Licencia-MIT-blue?style=for-the-badge)

## 📖 Descripción

Este proyecto es una aplicación web Full Stack diseñada para digitalizar procesos escolares. El sistema implementa una arquitectura basada en el patrón **MVC (Modelo-Vista-Controlador)** nativo, separando claramente la lógica de negocio, el acceso a datos y la interfaz de usuario.

El objetivo principal es proveer dos entornos de trabajo seguros y diferenciados: uno para la **Administración** del colegio y otro para los **Apoderados** (tutores), permitiendo un flujo de información eficiente.

---

## 🛠️ Stack Tecnológico

El proyecto fue construido utilizando tecnologías web estándar, priorizando el rendimiento y la estructura limpia del código sin depender de frameworks pesados.

* **Backend:** PHP (Nativo, Orientado a Objetos)
* **Base de Datos:** MySQL
* **Frontend:** HTML5, CSS3 (Diseño responsivo personalizado), JavaScript (Vanilla)
* **Arquitectura:** MVC (Model View Controller)
* **Servidor:** Apache (XAMPP/WAMP compatible)

---

## ✨ Características Principales

### 1. 🔐 Autenticación y Seguridad
* **Login Seguro:** Sistema de inicio de sesión validado contra base de datos MySQL.
* **Manejo de Sesiones:** Control de sesiones de usuario para proteger rutas privadas.
* **Gestión de Contraseñas:** Incluye scripts de utilidad (`migrar_passwords.php`) para el mantenimiento y actualización de credenciales de seguridad.
* **Logout:** Cierre de sesión seguro que destruye los datos de navegación[cite: 38].

### 2. 👤 Gestión de Roles (RBAC)
El sistema detecta automáticamente el tipo de usuario y carga el entorno correspondiente:
* **Rol Administrador:** Acceso total a la gestión del sistema a través de `admin.php` y sus controladores.
* **Rol Apoderado:** Vista dedicada para padres y tutores (`apoderado.php`), optimizada para la consulta de información del alumno.

### 3. 🏗️ Arquitectura MVC
El código está organizado profesionalmente para facilitar la escalabilidad:
* **Modelos (`/models`):** `AdminModel.php`, `ApoderadoModel.php`, `UsuarioModel.php` manejan todas las consultas SQL y lógica de datos.
* **Vistas (`/views`):** `admin.view.php`, `apoderado.view.php`, `login.view.php` separan la capa de presentación del código PHP lógico.
* **Configuración:** Archivo `conexion.php` centralizado para la gestión de la base de datos[cite: 35].

### 4. 🎨 Interfaz y Experiencia de Usuario (UI/UX)
* Estilos CSS modulares separados por contexto (`admin.css`, `apoderado.css`, `login.css`) para una carga eficiente.
* Interactividad mediante JavaScript (`admin.js`, `login.js`) para validaciones y comportamiento dinámico sin recargar la página

---

## 📂 Estructura del Proyecto

```text
sistema-colegio/
├── assets/              # Recursos estáticos
│   ├── css/             # Hojas de estilo (login.css, admin.css...)
│   ├── js/              # Lógica Frontend (validaciones, interactividad)
│   └── img/             # Imágenes y logotipos
├── config/
│   └── conexion.php     # Configuración de conexión a Base de Datos
├── models/              # Lógica de Datos (Consultas SQL)
│   ├── AdminModel.php
│   ├── ApoderadoModel.php
│   └── UsuarioModel.php
├── views/               # Interfaz de Usuario (HTML/PHP mixto)
│   ├── admin.view.php
│   ├── apoderado.view.php
│   └── login.view.php
├── admin.php            # Controlador de Administrador
├── apoderado.php        # Controlador de Apoderado
├── login.php            # Controlador de Login
├── logout.php           # Script de cierre de sesión
└── index.php            # Punto de entrada
