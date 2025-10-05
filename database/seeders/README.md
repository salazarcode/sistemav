# Seeders

Este directorio contiene los seeders disponibles para el proyecto.

## Seeders Disponibles

### Seeders Básicos
- **RoleSeeder**: Crea los roles básicos del sistema.
- **PermissionSeeder**: Crea los permisos básicos del sistema.
- **OrganizationSeeder**: Crea un conjunto completo de organizaciones.
- **InitialOrganizationSeeder**: Crea solo las organizaciones iniciales básicas.
- **CategorySeeder**: Crea las categorías para eventos.
- **MasterUserSeeder**: Crea el usuario administrador principal.

### Seeders de Datos de Prueba
- **TestDataSeeder**: Crea datos adicionales para pruebas y desarrollo.

## Cómo Usar

### Usar los Seeders con Artisan

```bash
# Ejecutar todos los seeders configurados en DatabaseSeeder
php artisan db:seed

# Ejecutar un seeder específico
php artisan db:seed --class=Database\\Seeders\\OrganizationSeeder

# Ejecutar solo el seeder de organizaciones iniciales
php artisan db:seed-initial-organizations
```

### Configurar DatabaseSeeder

Para personalizar qué seeders se ejecutan al usar `php artisan db:seed`, edita el archivo `DatabaseSeeder.php`:

```php
$this->call([
    // Seeders básicos
    RoleSeeder::class,
    PermissionSeeder::class,
    
    // Para usar solo organizaciones iniciales básicas, descomentar:
    // InitialOrganizationSeeder::class,
    
    // O para usar el conjunto completo de organizaciones, descomentar:
    OrganizationSeeder::class,
    
    CategorySeeder::class,
    MasterUserSeeder::class,
    
    // Descomentar para generar datos de prueba adicionales
    // TestDataSeeder::class,
]);
``` 