# Guía para Manejar Migraciones Desincronizadas en Laravel

## 🚨 Situación Actual
Tu base de datos tiene migraciones ejecutadas que no existen en el código fuente. Esto es crítico porque:
- Los nuevos desarrolladores no pueden replicar la BD
- Los modelos no coinciden con la estructura real
- Los deployments pueden fallar o corromper datos

## 📋 Estrategia de Resolución

### Opción 1: RECREAR MIGRACIONES (Recomendada para tu caso)
**Cuando usar:** Si la BD de producción es la fuente de verdad y necesitas mantener compatibilidad.

### Opción 2: SINCRONIZACIÓN INVERSA
**Cuando usar:** Si el código es la fuente de verdad y puedes modificar la BD.

### Opción 3: RESET COMPLETO
**Cuando usar:** Solo en desarrollo o si puedes perder datos.

## 🔧 PLAN DE ACCIÓN PARA TU CASO

### Paso 1: Documentar Estado Actual ✅
Ya completado en `estado_db_actual.md`

### Paso 2: Crear Migraciones "Fantasma"
Estas migraciones recrean los cambios ya aplicados pero permiten que nuevos entornos lleguen al mismo estado.

### Paso 3: Marcar Migraciones como Ejecutadas
En producción, deberás insertar manualmente estas migraciones en la tabla `migrations` para que Laravel no intente ejecutarlas nuevamente.

### Paso 4: Sincronizar Modelos
Actualizar los modelos para reflejar el estado real de la BD.

## 🛠️ Implementación

### 1. RECREAR MIGRACIONES FALTANTES

Las migraciones deben recrearse con las fechas originales para mantener el orden correcto.

#### Archivos a crear:
1. `2025_05_03_222229_remove_address_from_personal_data.php`
2. `2025_05_03_223944_add_birth_date_to_participants_table.php`
3. `2025_05_03_225641_update_event_slugs_to_new_format.php`
4. `2025_05_03_230406_remove_email_from_participants_table.php`

### 2. MARCAR COMO EJECUTADAS EN PRODUCCIÓN

```sql
-- Ejecutar en la BD de producción después de crear los archivos
INSERT INTO migrations (migration, batch) VALUES 
('2025_05_03_222229_remove_address_from_personal_data', 1),
('2025_05_03_223944_add_birth_date_to_participants_table', 1),
('2025_05_03_225641_update_event_slugs_to_new_format', 1),
('2025_05_03_230406_remove_email_from_participants_table', 1)
ON DUPLICATE KEY UPDATE migration=migration;
```

### 3. VERIFICACIÓN POST-IMPLEMENTACIÓN

```bash
# En desarrollo (BD limpia)
php artisan migrate:fresh --seed

# En staging (con datos de prueba)
php artisan migrate:status
php artisan migrate --pretend

# Verificar integridad
php artisan tinker
>>> Schema::hasColumn('participants', 'email')  // debe ser false
>>> Schema::hasColumn('participants', 'birth_date')  // debe ser true
>>> Schema::hasColumn('personal_data', 'address')  // debe ser false
```

## ⚠️ PREVENCIÓN FUTURA

### 1. Reglas de Oro
- **NUNCA** ejecutar SQL directo en producción sin crear migración
- **SIEMPRE** commitear migraciones antes de ejecutarlas
- **DOCUMENTAR** cualquier cambio manual de emergencia

### 2. Proceso Seguro de Migraciones
```bash
# 1. Crear migración
php artisan make:migration fix_something

# 2. Escribir y revisar
# 3. Probar en local
php artisan migrate

# 4. Commitear ANTES de producción
git add database/migrations/
git commit -m "Add migration: fix_something"
git push

# 5. Ejecutar en producción
php artisan migrate --force
```

### 3. Herramientas de Monitoreo

#### Script de Verificación (crear como comando Artisan)
```php
// app/Console/Commands/CheckDatabaseIntegrity.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDatabaseIntegrity extends Command
{
    protected $signature = 'db:check-integrity';
    protected $description = 'Verifica que los modelos coincidan con la BD';

    public function handle()
    {
        $this->info('Verificando integridad de la base de datos...');
        
        $issues = [];
        
        // Verificar participants
        if (Schema::hasColumn('participants', 'email')) {
            $issues[] = "❌ participants tiene 'email' pero no debería";
        }
        if (!Schema::hasColumn('participants', 'birth_date')) {
            $issues[] = "❌ participants NO tiene 'birth_date' pero debería";
        }
        
        // Verificar personal_data
        if (Schema::hasColumn('personal_data', 'address')) {
            $issues[] = "❌ personal_data tiene 'address' pero no debería";
        }
        if (Schema::hasColumn('personal_data', 'age')) {
            $issues[] = "❌ personal_data tiene 'age' pero no debería";
        }
        
        // Verificar migraciones huérfanas
        $dbMigrations = DB::table('migrations')->pluck('migration');
        $fileMigrations = collect(scandir(database_path('migrations')))
            ->filter(fn($f) => str_ends_with($f, '.php'))
            ->map(fn($f) => str_replace('.php', '', $f));
            
        $orphaned = $dbMigrations->diff($fileMigrations);
        if ($orphaned->count() > 0) {
            $issues[] = "❌ Migraciones en BD sin archivo: " . $orphaned->implode(', ');
        }
        
        if (empty($issues)) {
            $this->info('✅ Base de datos sincronizada correctamente');
        } else {
            $this->error('Problemas encontrados:');
            foreach ($issues as $issue) {
                $this->line($issue);
            }
        }
        
        return empty($issues) ? 0 : 1;
    }
}
```

### 4. GitHub Actions para CI/CD
```yaml
# .github/workflows/database-check.yml
name: Database Integrity Check

on: [push, pull_request]

jobs:
  check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install Dependencies
        run: composer install
      - name: Run Migrations on SQLite
        run: |
          touch database/database.sqlite
          php artisan migrate --force
      - name: Check Integrity
        run: php artisan db:check-integrity
```

## 🔴 ERRORES COMUNES A EVITAR

1. **NO ejecutar `migrate:fresh` en producción** - Perderás todos los datos
2. **NO modificar migraciones ya ejecutadas** - Crea nuevas migraciones
3. **NO ignorar `migrate:status`** - Siempre verifica antes de migrar
4. **NO confiar solo en el ORM** - Valida con queries directos
5. **NO olvidar los índices y foreign keys** - Pueden causar problemas silenciosos

## 📊 Checklist de Emergencia

Si encuentras migraciones desincronizadas:

- [ ] Hacer backup completo de la BD
- [ ] Documentar estado actual (tablas, columnas, índices)
- [ ] Identificar migraciones faltantes
- [ ] Recrear archivos de migración
- [ ] Actualizar modelos Eloquent
- [ ] Probar en entorno de desarrollo
- [ ] Marcar migraciones como ejecutadas en producción
- [ ] Verificar integridad post-deployment
- [ ] Documentar el incidente y solución

## 🚀 Comandos Útiles

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ver SQL que se ejecutaría sin ejecutar
php artisan migrate --pretend

# Rollback de última migración
php artisan migrate:rollback

# Rollback de últimas N migraciones
php artisan migrate:rollback --step=5

# Ver estructura de una tabla
php artisan db:table participants

# Comparar modelos con BD (requiere package doctrine/dbal)
composer require doctrine/dbal
php artisan schema:dump
```

## 📝 Plantilla de Documentación de Incidente

```markdown
## Incidente: Migraciones Desincronizadas
**Fecha:** [FECHA]
**Detectado por:** [NOMBRE]
**Ambiente afectado:** [PROD/STAGING/DEV]

### Descripción
[Qué migraciones faltaban y por qué]

### Impacto
[Qué funcionalidades se vieron afectadas]

### Solución Aplicada
1. [Paso 1]
2. [Paso 2]
...

### Prevención
[Qué medidas se tomaron para evitar recurrencia]

### Lecciones Aprendidas
[Qué aprendimos de este incidente]
```

---

**Recuerda:** La integridad de la base de datos es crítica. Es mejor invertir tiempo en mantener las migraciones sincronizadas que lidiar con corrupción de datos en producción.