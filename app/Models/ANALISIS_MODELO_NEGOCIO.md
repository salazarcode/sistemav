# Análisis del Modelo de Negocio - Sistema de Gestión de Eventos

## Descripción General del Dominio

Este sistema implementa una plataforma completa de gestión de eventos orientada a organizaciones que necesitan administrar eventos, participantes y registro de asistencias. El dominio principal se centra en la **gestión integral de eventos** con capacidades multiorganizacionales y control jerárquico de usuarios.

## Entidades Principales y sus Responsabilidades

### 1. Organization / Institution
**Propósito:** Entidad raíz que representa las organizaciones que utilizan el sistema.

**Atributos:**
- `name`: Nombre de la organización
- `description`: Descripción detallada
- `location`: Ubicación física

**Responsabilidades:**
- Agrupar usuarios bajo una misma organización
- Contener todos los eventos de la organización
- Servir como contexto de aislamiento de datos (multi-tenancy)

**Nota:** Existe duplicidad entre `Organization` e `Institution` con estructura idéntica, sugiriendo una migración o refactorización en proceso.

### 2. User
**Propósito:** Usuarios del sistema con capacidades de autenticación y autorización.

**Atributos principales:**
- `email`: Correo electrónico único
- `user_name`: Nombre de usuario
- `password`: Contraseña encriptada
- `organizations_id`: Organización a la que pertenece
- `parent_id`: Referencia a usuario supervisor (jerarquía)

**Características especiales:**
- **Estructura jerárquica:** Implementa supervisión en cascada mediante `parent_id`
- **Multi-rol:** Soporta múltiples roles por usuario
- **Permisos granulares:** Control fino de accesos
- **Datos personales opcionales:** Asociación flexible con información personal

### 3. Event
**Propósito:** Entidad central que representa los eventos gestionados.

**Atributos:**
- `name`: Nombre del evento
- `description`: Descripción
- `location`: Lugar del evento
- `img`: Imagen promocional
- `start_date` / `end_date`: Fechas de inicio y fin
- `slug`: URL amigable
- `qr_code`: Código QR para acceso rápido
- `organizations_id`: Organización propietaria
- `user_id`: Usuario creador/responsable

**Características:**
- Eventos multi-categoría
- Gestión temporal completa
- Soporte para códigos QR (posiblemente para check-in)

### 4. Participant
**Propósito:** Registro completo de participantes en eventos.

**Atributos extensivos:**
- Datos básicos: `name`, `last_name`, `email`, `phone`
- Documentación: `dni`, `address`
- Demografía: `age`, `gender`, `education_level`
- Contexto profesional: `institution`, `profession`
- Gestión del evento: `ticket_type`, `seat_number`, `team`, `category`
- Control de asistencia: `attendance` (boolean)
- Referencias: `event_id`, `personal_data_id`, `assists_id`

**Características notables:**
- Modelo muy completo con campos para diversos tipos de eventos
- Flexibilidad para eventos académicos, deportivos y corporativos
- Integración opcional con datos personales centralizados

### 5. Assist
**Propósito:** Control temporal de asistencias.

**Atributos:**
- `start_date`: Inicio del periodo de asistencia
- `end_date`: Fin del periodo de asistencia

**Uso:** Gestiona ventanas temporales de registro de asistencia, útil para eventos de múltiples días o sesiones.

### 6. PersonalData
**Propósito:** Centralización de información personal reutilizable.

**Atributos:**
- Identificación: `dni`, `type_dni`
- Datos personales: `name`, `last_name`, `email`, `phone`
- Demografía: `birth_date`, `sex`, `address`
- Calculado: `age` (derivado de birth_date)

**Ventajas:**
- Evita duplicación de datos personales
- Permite actualización centralizada
- Facilita cumplimiento de normativas de protección de datos

### 7. Category
**Propósito:** Clasificación de eventos.

**Atributos:**
- `description`: Descripción de la categoría

**Uso:** Permite categorizar y filtrar eventos (ej: conferencias, talleres, seminarios).

### 8. Role
**Propósito:** Definición de roles del sistema.

**Atributos:**
- `name`: Nombre del rol

**Uso típico:** Admin, Organizador, Supervisor, Usuario básico.

### 9. Permission
**Propósito:** Control granular de accesos.

**Atributos:**
- `name`: Nombre del permiso

**Uso:** Define capacidades específicas del sistema.

### 10. PlainPassword
**Propósito:** Almacenamiento de contraseñas en texto plano.

**⚠️ ALERTA DE SEGURIDAD:** Este modelo representa un riesgo crítico de seguridad. Almacenar contraseñas en texto plano viola principios fundamentales de seguridad.

## Relaciones y Cardinalidades

### Relaciones Principales

```
Organization (1) ──────< (N) User
    │                        │
    │                        │ parent_id (auto-referencial)
    │                        ├──────< User (supervisados)
    │                        │
    └──────< (N) Event      │
                  │          │
                  │          └──────< (1) Event (creador)
                  │
                  ├──────< (N) Participant
                  │              │
                  │              ├──────> (1) PersonalData
                  │              │
                  │              └──────> (1) Assist
                  │                             │
                  │                             └──────< (N) Participant
                  │
                  └──────<>──────> (N) Category
```

### Relaciones de Autorización

```
User <>──────<> Role (muchos a muchos via users_roles)
User <>──────<> Permission (muchos a muchos via user_permissions)
User <>──────<> PersonalData (muchos a muchos via users_personal_data)
```

## Patrones de Diseño Identificados

### 1. Multi-tenancy Organizacional
- Cada organización opera de forma aislada
- Los datos están segregados por `organizations_id`

### 2. Jerarquía de Supervisión
- Estructura de árbol mediante `parent_id` en User
- Permite delegación y control en cascada
- Método `getAllSupervisedUsersAttribute` implementa recorrido recursivo

### 3. Datos Personales Centralizados
- PersonalData actúa como repositorio único
- Reduce redundancia y mejora mantenimiento
- Facilita cumplimiento GDPR/LGPD

### 4. Flexibilidad en Tipos de Eventos
- Modelo Participant altamente configurable
- Soporta múltiples contextos (académico, deportivo, corporativo)

### 5. Control Temporal de Asistencias
- Modelo Assist permite ventanas de registro
- Útil para eventos multi-sesión

## Flujos de Negocio Principales

### 1. Creación de Evento
1. Usuario autenticado de una organización crea evento
2. Se asigna el evento a la organización del usuario
3. Se pueden asociar múltiples categorías
4. Se genera slug y opcionalmente código QR

### 2. Registro de Participantes
1. Se crea registro en Participant vinculado al evento
2. Opcionalmente se vincula o crea PersonalData
3. Se asignan detalles específicos del evento (asiento, tipo ticket, etc.)

### 3. Control de Asistencia
1. Se crea ventana temporal en Assist
2. Se actualiza campo `attendance` en Participant
3. Se mantiene trazabilidad temporal

### 4. Gestión Jerárquica
1. Supervisores pueden ver usuarios bajo su cargo
2. Permisos se propagan según jerarquía
3. Acceso a eventos según organización y permisos

## Consideraciones de Seguridad y Mejoras

### 🔴 Crítico
- **PlainPassword**: Eliminar completamente este modelo. Las contraseñas NUNCA deben almacenarse en texto plano.

### 🟡 Importante
- **Duplicidad Organization/Institution**: Consolidar en un único modelo
- **Validación de datos**: Implementar validaciones en modelos (emails, DNI, fechas)
- **Soft deletes**: Considerar para mantener integridad histórica

### 🟢 Recomendaciones
- **Auditoría**: Agregar campos created_by, updated_by para trazabilidad
- **Estados**: Agregar estado a Event (borrador, publicado, cancelado, finalizado)
- **Capacidad**: Campo de capacidad máxima en Event
- **Confirmación**: Sistema de confirmación de asistencia por email

## Métricas del Dominio

- **Entidades principales**: 11 modelos
- **Relaciones many-to-many**: 4 (roles, permisos, categorías, datos personales)
- **Relaciones one-to-many**: 8
- **Relación auto-referencial**: 1 (User supervisión)
- **Complejidad**: Media-Alta (debido a la flexibilidad y jerarquías)

## Conclusión

El sistema implementa un dominio robusto y flexible para la gestión de eventos organizacionales con características empresariales como multi-tenancy, control jerárquico y gestión granular de permisos. La arquitectura soporta diversos tipos de eventos y mantiene una separación clara de responsabilidades entre las entidades.

La principal área de mejora urgente es la eliminación del modelo PlainPassword por razones de seguridad, y la consolidación de la duplicidad Organization/Institution para mantener consistencia en el modelo de datos.