<?php

namespace App\Helpers;

class PermissionHelper
{
    /**
     * Get friendly permission names for display
     *
     * @return array
     */
    public static function getFriendlyNames()
    {
        return [
            'create_user' => 'Crear Usuarios',
            'edit_user' => 'Editar Usuarios',
            'delete_user' => 'Eliminar Usuarios',
            'create_event' => 'Crear Eventos',
            'edit_event' => 'Editar Eventos',
            'delete_event' => 'Eliminar Eventos',
            'manage_participants' => 'Gestionar Participantes',
            'view_reports' => 'Ver Reportes',
            'download_reports' => 'Descargar Reportes',
        ];
    }

    /**
     * Get a friendly permission name
     *
     * @param string $permissionName
     * @return string
     */
    public static function getFriendlyName($permissionName)
    {
        $friendlyNames = self::getFriendlyNames();
        return $friendlyNames[$permissionName] ?? $permissionName;
    }
    
    /**
     * Get friendly permission descriptions
     *
     * @return array
     */
    public static function getDescriptions()
    {
        return [
            'create_user' => 'Permite crear nuevos usuarios supervisados',
            'edit_user' => 'Permite editar la información de usuarios supervisados',
            'delete_user' => 'Permite eliminar usuarios supervisados',
            'create_event' => 'Permite crear nuevos eventos',
            'edit_event' => 'Permite editar eventos existentes',
            'delete_event' => 'Permite eliminar eventos',
            'manage_participants' => 'Permite gestionar participantes en eventos',
            'view_reports' => 'Permite visualizar reportes y estadísticas',
            'download_reports' => 'Permite descargar reportes en formato Excel/PDF',
        ];
    }
    
    /**
     * Get a friendly permission description
     *
     * @param string $permissionName
     * @return string
     */
    public static function getDescription($permissionName)
    {
        $descriptions = self::getDescriptions();
        return $descriptions[$permissionName] ?? 'Permiso del sistema';
    }
} 