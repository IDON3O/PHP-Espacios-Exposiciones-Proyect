<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Plataforma de Reservas de Espacios para Exposiciones

Sistema web para la gestión y reserva de espacios destinados a exposiciones artísticas. Permite a los usuarios solicitar reservas de espacios y a los administradores gestionarlas.

## Características

- **Catálogo de espacios**: Visualización de espacios disponibles con fotos, descripción y capacidad
- **Sistema de reservas**: Formulario público para solicitar reservas de espacios
- **Código de seguimiento**: Cada reserva genera un código único (UUID) para consulta pública del estado
- **Panel administrativo**: Gestión de espacios, reservas, disponibilidad y bloqueos horarios
- **Estados de reserva**: Pendiente, Confirmada, Rechazada, Cancelada, Finalizada

## Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js 18+ y npm
- MySQL 8.0 o PostgreSQL 14+
- Servidor web (Apache/Nginx) o Laragon/XAMPP para desarrollo

## Instalación Local

### 1. Clonar el repositorio

```bash
git clone <https://github.com/IDON3O/PHP-Espacios-Exposiciones-Proyect.git>
cd Laravel-2026-1-main
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar el entorno

Copia el archivo `.env.example` a `.env` y configura las variables de entorno:

```bash
cp .env.example .env
```

Edita `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cursophp_reservas
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

### 7. Compilar assets

```bash
npm run build
```

O para desarrollo con hot reload:

```bash
npm run dev
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Guía de Uso

### Acceso Público

#### Ver espacios disponibles

1. Navega a la página principal
2. Explora los espacios disponibles con sus fotos y descripciones
3. Usa el filtro de fecha para ver espacios disponibles en una fecha específica

#### Reservar un espacio

1. Haz clic en "Ver disponibilidad" en el espacio deseado
2. Selecciona un horario disponible
3. Completa el formulario con tus datos (nombre, correo, notas)
4. Envía la solicitud

#### Consultar estado de reserva

1. En la barra de navegación, ingresa tu código de seguimiento en el campo "Código de reserva"
2. Haz clic en "Buscar"
3. Verás la página con el estado actual de tu reserva

**Nota**: El código de seguimiento se muestra en la pantalla de confirmación después de crear la reserva. Guárdalo para futuras consultas.

### Acceso Administrativo

#### Credenciales por defecto

```
Email: admin@example.com
Password: password
```

#### Gestión de espacios

- **Crear espacio**: Panel de administración → Espacios → Crear nuevo
- **Editar espacio**: Desde la lista de espacios, hacer clic en editar
- **Configurar disponibilidad**: Desde un espacio, configurar horarios disponibles por día
- **Bloquear horarios**: Bloquear fechas/hora específicas para mantenimiento o eventos especiales

#### Gestión de reservas

- **Ver reservas**: Panel de administración → Reservas
- **Aceptar reserva**: Click en "Aceptar" para confirmar una reserva pendiente
- **Rechazar reserva**: Click en "Rechazar" para denegar una solicitud
- **Cancelar reserva**: Click en "Cancelar" para anular una reserva confirmada

## Estructura del Proyecto

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controladores del panel administrativo
│   │   └── Public/          # Controladores públicos
│   └── Models/              # Modelos Eloquent
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Admin/       # Vistas del panel administrativo
│   │   │   └── Public/      # Vistas públicas
│   │   └── Components/      # Componentes Vue
│   └── views/               # Vistas Blade (emails)
├── routes/
│   └── web.php              # Rutas de la aplicación
└── database/
    ├── migrations/          # Migraciones de base de datos
    └── seeders/             # Seeders para datos de prueba
```

## Comandos Útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ejecutar tests
php artisan test

# Crear usuario administrador
php artisan make:filament-user

# Procesar colas (para emails)
php artisan queue:work
```

## Licencia

Este proyecto está basado en Laravel y está licenciado bajo la [MIT license](https://opensource.org/licenses/MIT).
