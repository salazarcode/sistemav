# Sistema de Gestión de Eventos

Un sistema completo de gestión de eventos desarrollado con Laravel que permite a los usuarios crear, administrar eventos y realizar seguimiento de participantes. El sistema soporta una estructura jerárquica de usuarios donde los supervisores pueden gestionar a sus usuarios supervisados y sus eventos.

## Características Principales

- **Jerarquía de Usuarios**: Usuarios maestros pueden crear y gestionar usuarios supervisados
- **Gestión de Eventos**: Crear, editar y eliminar eventos con detalles como nombre, descripción, ubicación, fechas y categorías
- **Generación de Códigos QR**: Genera automáticamente códigos QR para eventos para facilitar su compartición
- **Registro de Participantes**: Registrar participantes para eventos y controlar su asistencia
- **Páginas Públicas de Eventos**: Páginas públicas para eventos donde los participantes pueden registrarse
- **Panel de Control**: Panel completo que muestra eventos del usuario, eventos de usuarios supervisados y eventos destacados
- **Funcionalidad de Búsqueda**: Buscar eventos por nombre, categoría, institución o usuario supervisado
- **Filtros Avanzados**: Filtrar eventos por institución, fecha, ubicación y número de participantes
- **Autorización**: Control de acceso basado en roles para garantizar que los usuarios solo puedan acceder a recursos apropiados
- **Diseño Responsivo**: Interfaz adaptable para dispositivos móviles construida con Tailwind CSS
- **Estadísticas**: Sistema completo de estadísticas con gráficos interactivos y opciones de filtrado
- **Exportación de Datos**: Exportación de informes en formatos PDF y Excel
- **Caché de Estadísticas**: Sistema de caché para optimizar el rendimiento de las consultas estadísticas
- **Filtros Personalizados**: Guardar y aplicar filtros personalizados para búsquedas frecuentes
- **Perfil de Usuario**: Gestión completa de datos personales y preferencias
- **Manejo Automático de Imágenes**: Corrección automática de URLs de imágenes y uso de placeholders para evitar errores 404

## Documentación de Relaciones entre Modelos

Esta sección describe las relaciones entre los diferentes modelos en la aplicación. Entender estas relaciones es esencial para el desarrollo y mantenimiento del sistema.

### Modelo Usuario (User)

El modelo `User` representa a los usuarios del sistema, incluyendo usuarios regulares, supervisores y usuarios supervisados.

#### Relaciones:

1. **roles()** - Relación muchos a muchos con el modelo `Role`
   - Un usuario puede tener múltiples roles
   - Tabla pivote: `users_roles`
   - Uso: `$user->roles` para obtener todos los roles de un usuario

2. **parent()** - Relación auto-referencial (pertenece a)
   - Un usuario puede tener un supervisor (padre)
   - Uso: `$user->parent` para obtener el supervisor directo de un usuario

3. **supervisedUsers()** - Relación auto-referencial (tiene muchos)
   - Un usuario puede supervisar a múltiples usuarios
   - Uso: `$user->supervisedUsers` para obtener los usuarios directamente supervisados

4. **institution()** - Relación pertenece a
   - Un usuario pertenece a una institución
   - Uso: `$user->institution` para obtener la institución del usuario

5. **permissions()** - Relación muchos a muchos
   - Un usuario puede tener múltiples permisos
   - Tabla pivote: `user_permissions`
   - Uso: `$user->permissions` para obtener todos los permisos de un usuario

6. **personalData()** - Relación muchos a muchos
   - Un usuario puede tener múltiples registros de datos personales (históricos)
   - Tabla pivote: `users_personal_data` con un campo `active`
   - Uso: `$user->personalData()->where('active', true)->first()` para obtener los datos personales activos

7. **events()** - Relación tiene muchos
   - Un usuario puede crear múltiples eventos
   - Uso: `$user->events` para obtener todos los eventos creados por el usuario

### Modelo Evento (Event)

El modelo `Event` representa eventos creados en el sistema.

#### Relaciones:

1. **institution()** - Relación pertenece a
   - Un evento pertenece a una institución
   - Uso: `$event->institution` para obtener la institución del evento

2. **user()** - Relación pertenece a
   - Un evento pertenece a un usuario (creador)
   - Uso: `$event->user` para obtener el creador del evento

3. **categories()** - Relación muchos a muchos
   - Un evento puede pertenecer a múltiples categorías
   - Tabla pivote: `event_category`
   - Uso: `$event->categories` para obtener todas las categorías del evento

4. **participants()** - Relación tiene muchos
   - Un evento puede tener múltiples participantes
   - Uso: `$event->participants` para obtener todos los participantes del evento

### Modelo Institución (Institution)

El modelo `Institution` representa organizaciones a las que pueden pertenecer usuarios y eventos.

#### Relaciones:

1. **users()** - Relación tiene muchos
   - Una institución puede tener múltiples usuarios
   - Uso: `$institution->users` para obtener todos los usuarios de la institución

2. **events()** - Relación tiene muchos
   - Una institución puede tener múltiples eventos
   - Uso: `$institution->events` para obtener todos los eventos de la institución

### Modelo Rol (Role)

El modelo `Role` representa roles que los usuarios pueden tener en el sistema.

#### Relaciones:

1. **users()** - Relación muchos a muchos
   - Un rol puede ser asignado a múltiples usuarios
   - Tabla pivote: `users_roles`
   - Uso: `$role->users` para obtener todos los usuarios con ese rol

### Modelo Permiso (Permission)

El modelo `Permission` representa permisos que los usuarios pueden tener en el sistema.

#### Relaciones:

1. **users()** - Relación muchos a muchos
   - Un permiso puede ser asignado a múltiples usuarios
   - Tabla pivote: `user_permissions`
   - Uso: `$permission->users` para obtener todos los usuarios con ese permiso

### Modelo Categoría (Category)

El modelo `Category` representa categorías a las que pueden pertenecer los eventos.

#### Relaciones:

1. **events()** - Relación muchos a muchos
   - Una categoría puede tener múltiples eventos
   - Tabla pivote: `event_category`
   - Uso: `$category->events` para obtener todos los eventos en esa categoría

### Modelo Participante (Participant)

El modelo `Participant` representa participantes de eventos.

#### Relaciones:

1. **event()** - Relación pertenece a
   - Un participante pertenece a un evento
   - Uso: `$participant->event` para obtener el evento al que pertenece el participante

2. **personalData()** - Relación pertenece a
   - Un participante tiene datos personales asociados
   - Uso: `$participant->personalData` para obtener los datos personales del participante

3. **assist()** - Relación pertenece a
   - Un participante puede tener un registro de asistencia
   - Uso: `$participant->assist` para obtener el registro de asistencia del participante

### Modelo Datos Personales (PersonalData)

El modelo `PersonalData` representa los datos personales de los usuarios.

#### Relaciones:

1. **users()** - Relación muchos a muchos
   - Un registro de datos personales puede estar asociado con múltiples usuarios (aunque típicamente solo uno)
   - Tabla pivote: `users_personal_data` con un campo `active`
   - Uso: `$personalData->users` para obtener usuarios asociados con esos datos personales

2. **participants()** - Relación tiene muchos
   - Un registro de datos personales puede estar asociado con múltiples participantes
   - Uso: `$personalData->participants` para obtener todos los participantes con esos datos personales

### Métodos Auxiliares para la Jerarquía de Supervisión

El modelo `User` incluye métodos auxiliares para trabajar con la jerarquía de supervisión:

1. **isSupervisorOf(User $user)** - Verifica si un usuario es supervisor (directo o indirecto) de otro usuario
   - Uso: `$user->isSupervisorOf($otherUser)` para verificar si `$user` supervisa a `$otherUser`

2. **getAllSupervisedUsers()** - Obtiene todos los usuarios supervisados (directos e indirectos)
   - Uso: `$user->getAllSupervisedUsers()` para obtener todos los usuarios en la jerarquía de supervisión

### Consideraciones Importantes

1. **Nombres de Relaciones**: Siempre use el nombre exacto de la relación al cargar de manera anticipada con `with()`. Por ejemplo, use `with('categories')` en lugar de `with('category')`.

2. **Carga Anticipada vs. Carga Diferida**: 
   - Carga anticipada: `User::with('roles', 'permissions')->get()`
   - Carga diferida: `$user->roles`
   - Siempre prefiera la carga anticipada para evitar el problema N+1.

3. **Restricciones de Supervisión**: 
   - Un usuario puede ver todos los usuarios en su jerarquía de supervisión.
   - Un usuario solo puede editar o eliminar a sus usuarios supervisados directos.
   - Estas restricciones están implementadas en las políticas de autorización.

### Diagrama de Relaciones

```
Usuario (User)
├── roles (M:M)
├── parent (1:M inverso)
├── supervisedUsers (1:M)
├── institution (M:1)
├── permissions (M:M)
├── personalData (M:M)
└── events (1:M)

Evento (Event)
├── institution (M:1)
├── user (M:1)
├── categories (M:M)
└── participants (1:M)

Institución (Institution)
├── users (1:M)
└── events (1:M)

Rol (Role)
└── users (M:M)

Permiso (Permission)
└── users (M:M)

Categoría (Category)
└── events (M:M)

Participante (Participant)
├── event (M:1)
├── personalData (M:1)
└── assist (M:1)

Datos Personales (PersonalData)
├── users (M:M)
└── participants (1:M)
```

## Módulo de Estadísticas

El sistema cuenta con un completo módulo de estadísticas que permite:

- Visualizar datos mediante gráficos interactivos (torta, barras)
- Filtrar información por período, categoría, institución y más
- Exportar reportes en formato PDF y Excel con gráficos incluidos
- Mostrar valores y porcentajes en los gráficos
- Visualizar estadísticas de eventos y participantes
- Analizar datos demográficos (género, edad, nivel educativo)
- Almacenar en caché los resultados para mayor rendimiento
- Gestión de permisos para visualización y descarga de reportes
- Captura automática de gráficos para inclusión en reportes PDF y Excel

## Requisitos

- PHP 8.1 o superior
- Composer
- MySQL o base de datos compatible
- Node.js y NPM (para los activos frontend)

## Instalación para Desarrollo Local

1. **Clonar el repositorio**

```bash
git clone https://github.com/username/sistema-gestion-eventos.git
cd sistema-gestion-eventos
```

2. **Instalar dependencias PHP**

```bash
composer install
```

3. **Instalar dependencias JavaScript**

```bash
npm install
```

4. **Crear archivo de entorno**

```bash
cp .env.example .env
```

5. **Generar clave de aplicación**

```bash
php artisan key:generate
```

6. **Configurar la base de datos en el archivo .env**

7. **Ejecutar las migraciones y seeders**

```bash
php artisan migrate --seed
```

8. **Compilar los activos frontend**

```bash
npm run dev
```

9. **Crear enlace simbólico para el almacenamiento**

```bash
php artisan storage:link
```

10. **Iniciar el servidor de desarrollo**

```bash
php artisan serve
```

## Solución de problemas comunes

### Imágenes no se muestran (Error 404)

El sistema incluye un script automático (`public/js/image-fix.js`) que corrige errores 404 en imágenes y proporciona placeholders para imágenes no disponibles. Si experimentas problemas con imágenes:

- Verifica que el archivo `public/js/image-fix.js` exista
- Asegúrate de que el enlace simbólico al almacenamiento se haya creado correctamente
- Comprueba que las rutas de las imágenes en la base de datos sean correctas

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - vea el archivo LICENSE para más detalles.
