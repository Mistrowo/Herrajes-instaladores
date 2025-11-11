Sistema de Gestión de Instalaciones - Ilesa
Sistema web desarrollado en Laravel para la gestión integral de instalaciones de herrajes y mobiliario. Permite administrar notas de venta, asignar instaladores, gestionar herrajes, evidencias fotográficas y checklists de instalación.
📋 Características Principales
Gestión de Usuarios

Roles diferenciados: Administrador, Supervisor e Instalador
Sistema de autenticación seguro
Gestión de instaladores (CRUD completo)
Control de estado activo/inactivo

Dashboard Inteligente

Vista personalizada según rol de usuario
Búsqueda y selección de notas de venta
Acceso rápido a:

Planos
Órdenes de Compra
Herrajes
Checklist
Evidencia Fotográfica



Módulo de Asignaciones

Asignación de hasta 4 instaladores por nota de venta
Estados: Pendiente, Aceptada, Rechazada, En Proceso, Completada
Vista dedicada "Mis Asignaciones" para instaladores
Seguimiento de fechas de asignación y aceptación

Gestión de Herrajes

Creación y gestión de ítems de herraje por nota de venta
Campos: Descripción, Cantidad
Cálculo automático de totales
Estados: En Revisión, Aprobado, Rechazado

Evidencia Fotográfica

Carga de imágenes (PNG, JPG, WEBP hasta 5MB)
Descripción opcional por imagen
Galería organizada por nota de venta
Trazabilidad: registro de instalador y fecha de subida

Checklist de Instalación

Formulario completo de verificación
Secciones:

Número Proyecto/Pedido
Errores de Proyecto
Estado de Obra
Inspección Final


Registro de observaciones y autorizaciones

🛠️ Stack Tecnológico

Backend: Laravel 10.x
Frontend:

TailwindCSS 3.x
Alpine.js 3.x
SweetAlert2


Base de Datos:

MySQL (local)
SQL Server (notas de venta)


Otros:

Blade Templates
Livewire (componentes)



📦 Requisitos

PHP >= 8.1
Composer
MySQL >= 5.7
SQL Server (para conexión a notas de venta)
Node.js y NPM (para assets)

🚀 Instalación

Clonar el repositorio

bashgit clone [url-del-repositorio]
cd [nombre-proyecto]

Instalar dependencias

bashcomposer install
npm install

Configurar entorno

bashcp .env.example .env
php artisan key:generate

Configurar base de datos en .env

env# MySQL (base principal)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=usuario
DB_PASSWORD=contraseña

# SQL Server (notas de venta)
DB_HOST_SOFT=servidor
DB_PORT_SOFT=1433
DB_DATABASE_SOFT=base_datos_soft
DB_USERNAME_SOFT=usuario
DB_PASSWORD_SOFT=contraseña

Ejecutar migraciones

bashphp artisan migrate

Seeders (datos de prueba)

bashphp artisan db:seed

Crear enlace simbólico para storage

bashphp artisan storage:link

Compilar assets

bashnpm run dev
# o para producción
npm run build

Iniciar servidor

bashphp artisan serve
```

## 👤 Usuarios de Prueba

Después de ejecutar los seeders:

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | admin@ilesa.com | admin123 |
| Supervisor | supervisor@ilesa.com | supervisor123 |
| Instalador | diego@ilesa.com | diego123 |

## 📁 Estructura del Proyecto
```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdministracionController.php
│   │   │   ├── AsignarController.php
│   │   │   ├── AuthController.php
│   │   │   ├── ChecklistController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── EvidenciaController.php
│   │   │   ├── HerrajeController.php
│   │   │   └── MisAsignacionesController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php
│   │   │   └── EnsureInstaladorIsActive.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── Asigna.php
│   │   ├── Checklist.php
│   │   ├── EvidenciaFotografica.php
│   │   ├── Herraje.php
│   │   ├── HerrajeItem.php
│   │   ├── Instalador.php
│   │   └── NotaVtaActualiza.php
│   └── Services/
│       ├── AsignarService.php
│       ├── AuthService.php
│       ├── ChecklistService.php
│       ├── EvidenciaService.php
│       ├── HerrajeService.php
│       └── InstaladorService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── administracion/
│       ├── asignar/
│       ├── auth/
│       ├── checklist/
│       ├── components/
│       ├── dashboard/
│       ├── evidencia/
│       ├── herrajes/
│       ├── layouts/
│       └── mis-asignaciones/
└── routes/
    └── modules/
🔐 Roles y Permisos
Administrador

Acceso completo al sistema
Gestión de instaladores
Asignación de trabajos
Visualización de todas las notas de venta

Supervisor

Gestión de asignaciones
Visualización de reportes
Acceso a "Mis Asignaciones"

Instalador

Vista "Mis Asignaciones"
Aceptar/rechazar trabajos
Gestión de herrajes asignados
Carga de evidencias fotográficas
Completar checklists

📊 Módulos del Sistema
1. Dashboard

Búsqueda de notas de venta
Información del proyecto
Acceso rápido a módulos

2. Asignaciones

Crear asignaciones
Asignar hasta 4 instaladores
Gestionar estados
Filtros por estado y fecha

3. Herrajes

Gestión de ítems por nota de venta
Cálculo automático de totales
Estados de aprobación

4. Evidencias Fotográficas

Carga múltiple de imágenes
Galería organizada
Descripciones por imagen

5. Checklist

Formulario acordeón
Validación de instalación
Registro de errores y observaciones

🔧 Configuración Adicional
Permisos de Storage
bashchmod -R 775 storage
chmod -R 775 bootstrap/cache
Configuración de Gates
Los Gates se definen en AuthServiceProvider.php:

admin-only: Solo administradores
supervisor-only: Solo supervisores
instalador-only: Solo instaladores
admin-or-supervisor: Administradores o supervisores

📝 Convenciones de Código

PSR-12: Estándar de codificación PHP
Blade: Templates para vistas
Alpine.js: Interactividad frontend
Tailwind CSS: Clases de utilidad para estilos

🐛 Solución de Problemas
Error de conexión a SQL Server
Verificar extensión pdo_sqlsrv instalada y configurada en PHP.
Imágenes no se visualizan
Ejecutar: php artisan storage:link
Error 403 en rutas protegidas
Verificar que el usuario tenga el rol correcto asignado.
🤝 Contribución
Este es un proyecto interno de Ilesa. Para contribuir:

Crear una rama desde develop
Realizar cambios y commits descriptivos
Crear Pull Request hacia develop
Esperar revisión del equipo

📄 Licencia
Propiedad de Ilesa. Todos los derechos reservados.
📞 Contacto
Para soporte o consultas sobre el sistema, contactar al equipo de desarrollo interno.

Versión: 1.0.0
Última actualización: Noviembre 2025
Desarrollado por: Equipo Ilesa