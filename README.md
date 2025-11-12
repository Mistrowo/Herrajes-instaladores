# Sistema de Gestión de Instalaciones - Ohffice

Sistema web desarrollado en Laravel para la gestión integral de instalaciones de herrajes y mobiliario. Permite administrar notas de venta, asignar instaladores, gestionar herrajes, evidencias fotográficas y checklists de instalación.

## 📋 Características Principales

### Gestión de Usuarios
- **Roles diferenciados**: Administrador, Supervisor e Instalador
- Sistema de autenticación seguro
- Gestión de instaladores (CRUD completo)
- Control de estado activo/inactivo

### Dashboard Inteligente
- Vista personalizada según rol de usuario
- Búsqueda y selección de notas de venta
- Acceso rápido a:
  - Planos
  - Órdenes de Compra
  - Herrajes
  - Checklist
  - Evidencia Fotográfica

### Módulo de Asignaciones
- Asignación de hasta 4 instaladores por nota de venta
- Estados: Pendiente, Aceptada, Rechazada, En Proceso, Completada
- Vista dedicada "Mis Asignaciones" para instaladores
- Seguimiento de fechas de asignación y aceptación

### Gestión de Herrajes
- Creación y gestión de ítems de herraje por nota de venta
- Campos: Descripción, Cantidad
- Cálculo automático de totales
- Estados: En Revisión, Aprobado, Rechazado

### Evidencia Fotográfica
- Carga de imágenes (PNG, JPG, WEBP hasta 5MB)
- Descripción opcional por imagen
- Galería organizada por nota de venta
- Trazabilidad: registro de instalador y fecha de subida

### Checklist de Instalación
- Formulario completo de verificación
- Secciones:
  - Número Proyecto/Pedido
  - Errores de Proyecto
  - Estado de Obra
  - Inspección Final
- Registro de observaciones y autorizaciones

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 10.x
- **Frontend**: 
  - TailwindCSS 3.x
  - Alpine.js 3.x
  - SweetAlert2
- **Base de Datos**: 
  - MySQL (local)
  - SQL Server (notas de venta)
- **Otros**:
  - Blade Templates
  - Alpine.js Components

## 📦 Requisitos

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- SQL Server (para conexión a notas de venta)
- Node.js y NPM (para assets)

## 🚀 Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd [nombre-proyecto]
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos en `.env`**
```env
# MySQL (base principal)
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
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
```

6. **Seeders (datos de prueba)**
```bash
php artisan db:seed
```

7. **Crear enlace simbólico para storage**
```bash
php artisan storage:link
```

8. **Compilar assets**
```bash
npm run dev
# o para producción
npm run build
```

9. **Iniciar servidor**
```bash
php artisan serve
```

## 👤 Usuarios de Prueba

Después de ejecutar los seeders:

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | admin@Ohffice.com | admin123 |
| Supervisor | supervisor@Ohffice.com | supervisor123 |
| Instalador | diego@Ohffice.com | diego123 |

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
        ├── administracion.php
        ├── asignar.php
        ├── auth.php
        ├── checklist.php
        ├── evidencia.php
        ├── herrajes.php
        └── mis_asignaciones.php
```

## 🔐 Roles y Permisos

### Administrador
- Acceso completo al sistema
- Gestión de instaladores (CRUD)
- Asignación de trabajos
- Visualización de todas las notas de venta
- Gestión de todos los módulos

### Supervisor
- Gestión de asignaciones
- Visualización de reportes
- Acceso a "Mis Asignaciones"
- Supervisión de instaladores

### Instalador
- Vista "Mis Asignaciones"
- Aceptar/rechazar trabajos
- Gestión de herrajes asignados
- Carga de evidencias fotográficas
- Completar checklists
- Actualizar estado de trabajos

## 📊 Módulos del Sistema

### 1. Dashboard
- Búsqueda de notas de venta con paginación
- Información detallada del proyecto
- Acceso rápido a módulos principales
- Visualización de datos del cliente

### 2. Asignaciones
- Crear nuevas asignaciones
- Asignar hasta 4 instaladores por trabajo
- Gestionar estados del flujo de trabajo
- Filtros por estado, fecha y nota de venta
- Visualización de equipo de trabajo

### 3. Herrajes
- Gestión de ítems por nota de venta
- Agregar/Editar/Eliminar items
- Cálculo automático de cantidades totales
- Estados: En Revisión, Aprobado, Rechazado
- Observaciones por herraje

### 4. Evidencias Fotográficas
- Carga múltiple de imágenes
- Formatos soportados: PNG, JPG, WEBP
- Tamaño máximo: 5MB por imagen
- Galería organizada por nota de venta
- Descripción opcional por imagen
- Eliminación de evidencias

### 5. Checklist
- Formulario acordeón interactivo
- Validación completa de instalación
- Registro de errores por categoría
- Observaciones detalladas
- Autorización de modificaciones
- Estado de obra al momento de instalación
- Inspección final con múltiples checkpoints

## 🔧 Configuración Adicional

### Permisos de Storage
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Configuración de Gates
Los Gates se definen en `AuthServiceProvider.php`:
- `admin-only`: Solo administradores
- `supervisor-only`: Solo supervisores
- `instalador-only`: Solo instaladores
- `admin-or-supervisor`: Administradores o supervisores

### Middleware Personalizado
- `active.instalador`: Verifica que el instalador esté activo
- `role`: Verifica roles específicos del usuario

## 🗄️ Base de Datos

### Tablas Principales

#### sh_instalador
Gestión de usuarios instaladores
- id, usuario, nombre, telefono, correo, rut
- password, activo, rol
- timestamps, soft deletes

#### sh_asigna
Asignaciones de trabajo
- id, nota_venta, solicita
- asignado1, asignado2, asignado3, asignado4
- fecha_asigna, fecha_acepta, estado
- terminado, fecha_termino
- timestamps, soft deletes

#### sh_herraje
Documentos de herraje
- id, nv_folio, asigna_id, instalador_id
- estado, items_count, total_estimado
- observaciones, created_by, updated_by
- timestamps, soft deletes

#### sh_herraje_items
Items de herraje
- id, herraje_id, codigo, descripcion
- unidad, cantidad, precio, observaciones
- timestamps, soft deletes

#### sh_evidencia_fotografica
Evidencias fotográficas
- id, asigna_id, nota_venta, instalador_id
- imagen_path, descripcion, fecha_subida
- timestamps, soft deletes

#### sh_checklist
Checklist de instalación
- id, asigna_id, nota_venta, instalador_id
- Múltiples campos de verificación
- observaciones, telefono, fecha_completado
- timestamps, soft deletes

## 📝 Convenciones de Código

- **PSR-12**: Estándar de codificación PHP
- **Blade**: Templates para vistas
- **Alpine.js**: Interactividad frontend
- **Tailwind CSS**: Clases de utilidad para estilos
- **Services**: Lógica de negocio separada de controladores
- **Requests**: Validación de formularios

## 🎨 Frontend

### Componentes Blade
- `<x-sidebar>`: Navegación lateral
- `<x-accordion-item>`: Items de acordeón
- `<x-check-item>`: Items de checkbox

### Alpine.js Components
- `dashboardData()`: Lógica del dashboard
- `herrajeForm()`: Gestión de herrajes
- `checklistAccordion()`: Acordeón del checklist

### Estilos
- TailwindCSS para todos los estilos
- Diseño responsive mobile-first
- Tema personalizado de Ohffice

## 🐛 Solución de Problemas

### Error de conexión a SQL Server
Verificar que la extensión `pdo_sqlsrv` esté instalada y configurada en PHP.
```bash
# Verificar extensión
php -m | grep sqlsrv
```

### Imágenes no se visualizan
Ejecutar el comando para crear el enlace simbólico:
```bash
php artisan storage:link
```

### Error 403 en rutas protegidas
Verificar que el usuario tenga el rol correcto asignado en la base de datos.

### Error al subir imágenes
Verificar permisos de escritura en storage:
```bash
chmod -R 775 storage/app/public
```

### Error en migraciones
Limpiar y reiniciar migraciones:
```bash
php artisan migrate:fresh --seed
```

## 🚦 Testing
```bash
# Ejecutar tests
php artisan test

# Con coverage
php artisan test --coverage
```

## 📈 Mejoras Futuras

- [ ] Módulo de reportes avanzados
- [ ] Notificaciones en tiempo real
- [ ] Aplicación móvil para instaladores
- [ ] Integración con sistema ERP
- [ ] Dashboard con gráficos estadísticos
- [ ] Exportación de reportes a PDF
- [ ] Sistema de firma digital
- [ ] Geolocalización de instalaciones

## 🤝 Contribución

Este es un proyecto interno de Ohffice. Para contribuir:

1. Crear una rama desde `develop`
2. Realizar cambios y commits descriptivos
3. Seguir las convenciones de código
4. Crear Pull Request hacia `develop`
5. Esperar revisión del equipo

### Commits
Seguir convención de commits semánticos:
- `feat:` Nueva funcionalidad
- `fix:` Corrección de bug
- `docs:` Documentación
- `style:` Cambios de formato
- `refactor:` Refactorización de código
- `test:` Añadir o modificar tests

## 📄 Licencia

Propiedad de Ohffice. Todos los derechos reservados.

## 📞 Contacto y Soporte

Para soporte o consultas sobre el sistema:
- **Email**: soporte@Ohffice.cl
- **Teléfono**: +56 X XXXX XXXX
- **Equipo de Desarrollo**: Interno Ohffice

## 🔄 Historial de Versiones

### v1.0.0 (Noviembre 2025)
- ✅ Sistema de autenticación completo
- ✅ Gestión de instaladores
- ✅ Módulo de asignaciones
- ✅ Gestión de herrajes
- ✅ Evidencias fotográficas
- ✅ Checklist de instalación
- ✅ Dashboard principal
- ✅ Mis Asignaciones para instaladores

---

**Versión**: 1.0.0  
**Última actualización**: Noviembre 2025  
**Desarrollado por**: Equipo Ohffice  
**Tecnología**: Laravel 10.x + TailwindCSS + Alpine.js