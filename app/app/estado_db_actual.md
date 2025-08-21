# Análisis del Estado Actual de la Base de Datos

## Información General
- **Base de datos:** u180743896_laravel
- **Motor:** MariaDB 10.11.10
- **Fecha del respaldo:** 17-08-2025
- **Total de tablas:** 16 tablas (incluyendo tablas de Laravel y tablas del dominio)

## Análisis Comparativo: Modelos vs Base de Datos

### 1. Tabla `assists` ✅
**Modelo:** `Assist.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito en Eloquent |
| start_date | datetime | ✅ | Correcto |
| end_date | datetime | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 2. Tabla `categories` ✅
**Modelo:** `Category.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| description | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 3. Tabla `events` ✅
**Modelo:** `Event.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| name | varchar(255) | ✅ | Correcto |
| description | text | ✅ | Correcto |
| location | varchar(255) | ✅ | Correcto |
| img | varchar(255) | ✅ | Correcto |
| start_date | datetime | ✅ | Correcto |
| end_date | datetime | ✅ | Correcto |
| organizations_id | bigint UNSIGNED | ✅ | Correcto |
| user_id | bigint UNSIGNED | ✅ | Correcto |
| slug | varchar(255) | ✅ | Correcto |
| qr_code | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 4. Tabla `event_category` ✅
**Tabla pivote** (sin modelo dedicado)

| Campo DB | Tipo DB | Observación |
|----------|---------|-------------|
| id | bigint UNSIGNED | ID de tabla pivote |
| event_id | bigint UNSIGNED | FK a events |
| category_id | bigint UNSIGNED | FK a categories |
| created_at | timestamp | Timestamp |
| updated_at | timestamp | Timestamp |

**Estado:** Coherente - Tabla pivote para relación many-to-many

---

### 5. Tabla `organizations` ✅
**Modelo:** `Organization.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| name | varchar(255) | ✅ | Correcto |
| description | text | ✅ | Correcto |
| location | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente
**Nota:** Existe duplicidad con modelo `Institution.php` (estructura idéntica)

---

### 6. Tabla `participants` ⚠️ **DISCREPANCIAS CRÍTICAS**
**Modelo:** `Participant.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| event_id | bigint UNSIGNED | ✅ | Correcto |
| personal_data_id | bigint UNSIGNED | ✅ | Correcto |
| assists_id | bigint UNSIGNED | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |
| name | varchar(255) | ✅ | Correcto |
| last_name | varchar(255) | ✅ | Correcto |
| phone | varchar(255) | ✅ | Correcto |
| attendance | tinyint(1) | ✅ | Correcto |
| dni | varchar(255) | ✅ | Correcto |
| address | text | ✅ | Correcto |
| birth_date | date | ❌ | **⚠️ NO está en $fillable** |
| gender | varchar(255) | ✅ | Correcto |
| institution | varchar(255) | ✅ | Correcto |
| profession | varchar(255) | ✅ | Correcto |
| education_level | varchar(255) | ✅ | Correcto |
| ticket_type | varchar(255) | ✅ | Correcto |
| seat_number | varchar(255) | ✅ | Correcto |
| team | varchar(255) | ✅ | Correcto |
| category | varchar(255) | ✅ | Correcto |
| participant_type | varchar(255) | ✅ | Correcto |
| age | int | ✅ | Correcto |
| **email** | ❌ | ✅ | **🔴 Campo en modelo pero NO en DB** |

**🔴 DISCREPANCIAS ENCONTRADAS:**
1. **Campo `email`:** Existe en el modelo pero NO en la base de datos
2. **Campo `birth_date`:** Existe en la DB pero NO está en $fillable del modelo
3. **Migración inconsistente:** La migración `2025_05_03_230406_remove_email_from_participants_table` indica que el email fue eliminado, pero el modelo aún lo incluye

---

### 7. Tabla `personal_data` ⚠️ **DISCREPANCIA**
**Modelo:** `PersonalData.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| name | varchar(255) | ✅ | Correcto |
| last_name | varchar(255) | ✅ | Correcto |
| phone | varchar(255) | ✅ | Correcto |
| email | varchar(255) | ✅ | Correcto |
| sex | varchar(255) | ✅ | Correcto |
| birth_date | date | ✅ | Correcto |
| dni | varchar(255) | ✅ | Correcto |
| type_dni | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |
| **address** | ❌ | ✅ | **🔴 Campo en modelo pero NO en DB** |

**🔴 DISCREPANCIA:**
- **Campo `address`:** Existe en el modelo pero NO en la base de datos
- **Migración inconsistente:** La migración `2025_05_03_222229_remove_address_from_personal_data` indica que fue eliminado, pero el modelo aún lo incluye

---

### 8. Tabla `plain_passwords` ⚠️ **SEGURIDAD CRÍTICA**
**Modelo:** `PlainPassword.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| user_id | bigint UNSIGNED | ✅ | Correcto |
| plain_password | varchar(255) | ✅ | **🔴 RIESGO DE SEGURIDAD** |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**🔴 ALERTA DE SEGURIDAD:** Almacenamiento de contraseñas en texto plano

---

### 9. Tabla `permissions` ✅
**Modelo:** `Permission.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| name | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 10. Tabla `roles` ✅
**Modelo:** `Role.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| name | varchar(255) | ✅ | Correcto |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 11. Tabla `users` ✅
**Modelo:** `User.php`

| Campo DB | Tipo DB | En Modelo | Observación |
|----------|---------|-----------|-------------|
| id | bigint UNSIGNED | ❌ | ID implícito |
| email | varchar(255) | ✅ | Correcto |
| user_name | varchar(255) | ✅ | Correcto |
| email_verified_at | timestamp | ✅ | Correcto (en $casts) |
| password | varchar(255) | ✅ | Correcto |
| organizations_id | bigint UNSIGNED | ✅ | Correcto |
| parent_id | bigint UNSIGNED | ✅ | Correcto |
| preferences | longtext JSON | ✅ | Correcto (en $casts) |
| remember_token | varchar(100) | ✅ | Correcto (en $hidden) |
| created_at | timestamp | ❌ | Timestamp implícito |
| updated_at | timestamp | ❌ | Timestamp implícito |

**Estado:** Coherente

---

### 12. Tabla `users_personal_data` ✅
**Tabla pivote** (sin modelo dedicado)

| Campo DB | Tipo DB | Observación |
|----------|---------|-------------|
| id | bigint UNSIGNED | ID de tabla pivote |
| user_id | bigint UNSIGNED | FK a users |
| personal_data_id | bigint UNSIGNED | FK a personal_data |
| active | tinyint(1) | Estado activo |
| created_at | timestamp | Timestamp |
| updated_at | timestamp | Timestamp |

**Estado:** Coherente - Tabla pivote con campo adicional `active`

---

### 13. Tabla `users_roles` ✅
**Tabla pivote** (sin modelo dedicado)

| Campo DB | Tipo DB | Observación |
|----------|---------|-------------|
| id | bigint UNSIGNED | ID de tabla pivote |
| user_id | bigint UNSIGNED | FK a users |
| roles_id | bigint UNSIGNED | FK a roles |
| created_at | timestamp | Timestamp |
| updated_at | timestamp | Timestamp |

**Estado:** Coherente - Tabla pivote

---

### 14. Tabla `user_permissions` ✅
**Tabla pivote** (sin modelo dedicado)

| Campo DB | Tipo DB | Observación |
|----------|---------|-------------|
| id | bigint UNSIGNED | ID de tabla pivote |
| user_id | bigint UNSIGNED | FK a users |
| permissions_id | bigint UNSIGNED | FK a permissions |
| created_at | timestamp | Timestamp |
| updated_at | timestamp | Timestamp |

**Estado:** Coherente - Tabla pivote

---

## Tablas de Sistema Laravel ✅

### Tablas sin modelos (correctamente):
- `failed_jobs` - Sistema de colas de Laravel
- `migrations` - Control de migraciones
- `password_reset_tokens` - Tokens de recuperación de contraseña
- `personal_access_tokens` - Laravel Sanctum para API tokens

---

## 🔴 RESUMEN DE DISCREPANCIAS CRÍTICAS

### 1. **DUPLICACIÓN DE CAMPOS** 
**NO SE ENCONTRARON** columnas duplicadas en las tablas.

### 2. **INCONSISTENCIAS MODELO-DB**

#### Tabla `participants`:
- **Campo `email`**: Presente en modelo, AUSENTE en DB
- **Campo `birth_date`**: Presente en DB, NO en $fillable del modelo
- **Acción requerida**: Eliminar `email` del modelo `Participant.php`, agregar `birth_date` a $fillable

#### Tabla `personal_data`:
- **Campo `address`**: Presente en modelo, AUSENTE en DB  
- **Acción requerida**: Eliminar `address` del modelo `PersonalData.php`

### 3. **PROBLEMAS DE SEGURIDAD**
- **Tabla `plain_passwords`**: Almacena contraseñas en texto plano
- **Acción urgente**: Eliminar completamente esta tabla y su modelo

### 4. **DUPLICIDAD DE MODELOS**
- **`Institution.php` y `Organization.php`**: Modelos idénticos
- **Acción requerida**: Eliminar `Institution.php` y actualizar referencias

## 📊 Estadísticas de la Base de Datos

- **Tablas del dominio:** 11
- **Tablas pivote:** 3
- **Tablas de sistema Laravel:** 4
- **Total de registros en producción:**
  - Organizations: 16
  - Users: 1
  - Events: 6
  - Categories: 11
  - Participants: 3
  - Assists: 3
  - Personal Data: 4
  - Roles: 3
  - Permissions: 7

## 🚨 Acciones Prioritarias Recomendadas

1. **CRÍTICO - Seguridad:**
   - Eliminar tabla `plain_passwords` y modelo `PlainPassword.php`
   - Auditar si existen contraseñas expuestas

2. **ALTO - Coherencia de Datos:**
   - Actualizar modelo `Participant.php`: eliminar `email`, agregar `birth_date` a fillable
   - Actualizar modelo `PersonalData.php`: eliminar `address`

3. **MEDIO - Limpieza:**
   - Eliminar modelo `Institution.php` 
   - Verificar y actualizar todas las referencias a Institution

4. **BAJO - Mantenimiento:**
   - Crear migración para sincronizar cambios pendientes
   - Documentar las tablas pivote en el código

## Historial de Migraciones Relevantes

Las siguientes migraciones indican cambios que no se reflejan en los modelos:
- `2025_05_03_222229_remove_address_from_personal_data` - Campo aún en modelo
- `2025_05_03_230406_remove_email_from_participants_table` - Campo aún en modelo
- `2025_05_03_223944_add_birth_date_to_participants_table` - Campo no en fillable

## Análisis de Migraciones vs Estado de Base de Datos

### Migraciones Presentes en el Proyecto (26 archivos)

| Migración | Estado en BD | Observación |
|-----------|--------------|-------------|
| 2014_10_12_000000_create_users_table | ✅ Aplicada | Tabla users creada |
| 2014_10_12_100000_create_password_reset_tokens_table | ✅ Aplicada | Tabla password_reset_tokens creada |
| 2019_08_19_000000_create_failed_jobs_table | ✅ Aplicada | Tabla failed_jobs creada |
| 2019_12_14_000001_create_personal_access_tokens_table | ✅ Aplicada | Tabla personal_access_tokens creada |
| 2024_01_01_000000_create_institutions_table | ✅ Aplicada | Tabla creada y luego renombrada |
| 2024_01_01_000001_create_roles_table | ✅ Aplicada | Tabla roles creada |
| 2024_01_01_000002_create_users_roles_table | ✅ Aplicada | Tabla pivote creada |
| 2024_01_01_000003_create_permissions_table | ✅ Aplicada | Tabla permissions creada |
| 2024_01_01_000004_create_user_permissions_table | ✅ Aplicada | Tabla pivote creada |
| 2024_01_01_000005_create_personal_data_table | ✅ Aplicada | **⚠️ DISCREPANCIA: Crea `address` y `age`** |
| 2024_01_01_000006_create_users_personal_data_table | ✅ Aplicada | Tabla pivote creada |
| 2024_01_01_000008_create_events_table | ✅ Aplicada | Tabla events creada (sin FKs) |
| 2024_01_01_000009_create_categories_table | ✅ Aplicada | Tabla categories creada |
| 2024_01_01_000010_create_event_category_table | ✅ Aplicada | Tabla pivote creada |
| 2024_01_01_000011_create_assists_table | ✅ Aplicada | Tabla assists creada |
| 2024_01_01_000012_create_participants_table | ✅ Aplicada | Tabla participants creada (básica) |
| 2024_01_01_000100_add_foreign_keys_to_users | ✅ Aplicada | FKs agregadas a users |
| 2024_01_01_000101_add_foreign_keys_to_events | ✅ Aplicada | FKs agregadas a events |
| 2024_07_01_000000_rename_institutions_to_organizations | ✅ Aplicada | Renombrado a organizations |
| 2025_03_08_170637_add_slug_and_qr_code_to_events_table | ✅ Aplicada | Campos slug y qr_code agregados |
| 2025_03_08_205337_add_preferences_to_users_table | ✅ Aplicada | Campo preferences agregado |
| 2025_03_08_215155_add_fields_to_participants_table | ✅ Aplicada | **⚠️ DISCREPANCIA: Agrega `email` a participants** |
| 2025_03_16_203447_create_plain_passwords_table | ✅ Aplicada | Tabla plain_passwords creada |
| 2025_03_23_144035_add_email_to_personal_data_table | ✅ Aplicada | Campo email agregado |
| 2025_04_27_172940_modify_personal_data_add_birth_date | ✅ Aplicada | **⚠️ DISCREPANCIA: Elimina `age`, agrega `birth_date`** |

### 🔴 MIGRACIONES FALTANTES EN EL PROYECTO

El respaldo SQL muestra 4 migraciones adicionales que **NO EXISTEN** en el proyecto actual:

| Migración Faltante | Batch | Descripción Probable |
|-------------------|-------|----------------------|
| **2025_05_03_222229_remove_address_from_personal_data** | 1 | Elimina `address` de personal_data |
| **2025_05_03_223944_add_birth_date_to_participants_table** | 1 | Agrega `birth_date` a participants |
| **2025_05_03_225641_update_event_slugs_to_new_format** | 1 | Actualiza formato de slugs |
| **2025_05_03_230406_remove_email_from_participants_table** | 1 | Elimina `email` de participants |

### 📊 ANÁLISIS DETALLADO DE DISCREPANCIAS

#### 1. Tabla `personal_data` - Evolución por Migraciones

**Secuencia esperada según migraciones existentes:**
1. `2024_01_01_000005`: Crea tabla con `address` y `age` (sin `email`, sin `birth_date`)
2. `2025_03_23_144035`: Agrega `email`
3. `2025_04_27_172940`: Elimina `age`, agrega `birth_date`

**Estado actual en BD (con migraciones faltantes aplicadas):**
- ✅ Tiene: `name`, `last_name`, `phone`, `email`, `sex`, `birth_date`, `dni`, `type_dni`
- ❌ NO tiene: `address` (eliminado por migración faltante)
- ❌ NO tiene: `age` (eliminado por migración existente)

**Problema:** El modelo `PersonalData.php` aún tiene `address` en $fillable

#### 2. Tabla `participants` - Evolución por Migraciones

**Secuencia esperada según migraciones existentes:**
1. `2024_01_01_000012`: Crea tabla básica (solo IDs y timestamps)
2. `2025_03_08_215155`: Agrega todos los campos incluyendo `email` y `age`

**Estado actual en BD (con migraciones faltantes aplicadas):**
- ✅ Tiene todos los campos EXCEPTO `email`
- ✅ Tiene `birth_date` (agregado por migración faltante)
- ✅ Tiene `age` como int con default 0

**Problemas:**
- Modelo tiene `email` en $fillable pero no existe en BD
- Modelo NO tiene `birth_date` en $fillable pero SÍ existe en BD

#### 3. Inconsistencia de Campos `age` vs `birth_date`

**En `personal_data`:**
- Migración existente convierte `age` → `birth_date`
- BD actual: solo `birth_date`
- Modelo: correcto

**En `participants`:**
- Migración existente agrega `age` como integer
- Migración faltante agrega `birth_date`
- BD actual: tiene AMBOS campos (`age` y `birth_date`)
- Modelo: solo tiene `age` en $fillable

### 🚨 PROBLEMA CRÍTICO: MIGRACIONES PERDIDAS

Las 4 migraciones faltantes (todas del 2025_05_03) fueron ejecutadas en producción pero **no están en el código fuente**. Esto sugiere:

1. **Posible pérdida de código:** Las migraciones se ejecutaron pero no se committearon
2. **Desincronización ambiente:** El respaldo proviene de un ambiente con migraciones diferentes
3. **Hotfix no documentado:** Alguien ejecutó cambios directos en producción

### 📋 ACCIONES CORRECTIVAS REQUERIDAS

#### URGENTE - Recrear Migraciones Faltantes:

1. **Crear migración `2025_05_03_222229_remove_address_from_personal_data.php`:**
   - Eliminar columna `address` de `personal_data`
   
2. **Crear migración `2025_05_03_223944_add_birth_date_to_participants_table.php`:**
   - Agregar columna `birth_date` tipo date a `participants`
   
3. **Crear migración `2025_05_03_230406_remove_email_from_participants_table.php`:**
   - Eliminar columna `email` de `participants`

4. **Investigar migración `2025_05_03_225641_update_event_slugs_to_new_format.php`:**
   - Verificar qué cambios realizó en los slugs

#### INMEDIATO - Sincronizar Modelos:

1. **`PersonalData.php`:** Eliminar `address` del array $fillable
2. **`Participant.php`:** 
   - Eliminar `email` del array $fillable
   - Agregar `birth_date` al array $fillable

## Conclusión

El sistema presenta una desincronización grave entre el código fuente y la base de datos de producción. Existen 4 migraciones que fueron ejecutadas en producción pero no están presentes en el repositorio. Esto ha causado que los modelos Eloquent no reflejen la estructura real de la base de datos. 

**Riesgos identificados:**
1. **Pérdida de datos:** Intentar escribir en campos que no existen causará errores
2. **Fallos en producción:** Los modelos esperan campos que fueron eliminados
3. **Inconsistencia de datos:** Campos `age` y `birth_date` coexisten sin lógica clara
4. **Seguridad:** La tabla `plain_passwords` sigue siendo un riesgo crítico

Es imperativo recrear las migraciones faltantes y sincronizar los modelos antes de cualquier nuevo despliegue.