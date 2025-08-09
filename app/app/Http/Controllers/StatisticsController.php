<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Institution;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Participant;
use App\Exports\EventsExport;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    /**
     * Display the statistics dashboard.
     */
    public function index(Request $request)
    {
        $activeSection = $request->input('active_section', 'general-filters');
        $period = $request->input('period', 'quarter');
        
        // Si es una solicitud de "limpiar filtros", forzar el período a 'quarter' y limpiar caché
        if (!$request->filled('period') && !$request->has('refresh') && !$request->has('force_refresh') && 
            count($request->all()) <= 1 && 
            (!isset($request->all()['active_section']) || $request->all()['active_section'] === 'general-filters')) {
            // Es una solicitud de "limpiar filtros" o primera vista
            $period = 'quarter';
            $forceRefresh = true;
        } else {
            $forceRefresh = $request->has('refresh') || $request->has('active_section') || $request->has('force_refresh');
        }
        
        // Verificar acceso
        $user = auth()->user();
        $isMaster = $user->hasRole('master');
        $hasReportPermission = $isMaster || $user->can('download_reports');
        
        // Preparar filtros de fechas
        $dateFrom = null;
        $dateTo = null;
        
        if ($period === 'custom' && $request->filled(['date_from', 'date_to'])) {
            $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
            $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
        } else {
            // Periodos predefinidos
            $dateTo = Carbon::now();
            
            switch ($period) {
                case 'month':
                    $dateFrom = Carbon::now()->subMonth();
                    break;
                case 'quarter':
                    $dateFrom = Carbon::now()->subMonths(3);
                    break;
                case 'year':
                    $dateFrom = Carbon::now()->subYear();
                    break;
                case 'all':
                default:
                    $dateFrom = Carbon::parse('2000-01-01');
                    break;
            }
        }
        
        // Crear clave única para caché basada en los filtros
        $filterParams = $request->except(['_token', 'page', 'refresh', 'force_refresh']);
        $filterParams['period'] = $period; // Asegurar que el periodo esté en los parámetros de caché
        $cacheKey = 'statistics_' . md5(json_encode($filterParams));
        
        if (!$forceRefresh && cache()->has($cacheKey)) {
            $cachedData = cache()->get($cacheKey);
            
            return view('statistics.index', array_merge([
                'hasReportPermission' => $hasReportPermission,
                'isMaster' => $isMaster,
                'categories' => Category::orderBy('description')->get(),
                'organizations' => Organization::orderBy('name')->get(),
                'supervisors' => User::whereHas('roles', function($query) {
                    $query->where('name', 'supervisor');
                })->orderBy('user_name')->get(),
                'recentEvents' => Event::orderBy('created_at', 'desc')->limit(100)->get(),
                'period' => $period,
                'activeSection' => $activeSection,
                'fromCache' => true
            ], $cachedData));
        }
        
        // Get events based on user role
        $eventsQuery = Event::query();
        
        // Si se especificaron eventos específicos, no restringir por usuario (para mostrar los eventos supervisados)
        $hasSpecificEvents = $request->filled('specific_events') && !empty($request->input('specific_events'));
        
        if (!$isMaster && !$hasSpecificEvents) {
            // Si no es master y no hay eventos específicos, obtener eventos del usuario y sus supervisados
            $supervisedUsers = $user->all_supervised_users ?? collect();
            $userIds = $supervisedUsers->pluck('id')->push($user->id)->toArray();
            $eventsQuery->whereIn('user_id', $userIds);
        }
        
        // Aplicar filtros de fecha si existen
        if ($dateFrom && $dateTo) {
            $eventsQuery->whereBetween('start_date', [$dateFrom, $dateTo]);
        }
        
        // Filtrar por categoría
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $eventsQuery->whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }
        
        // Filtrar por organización
        if ($request->filled('organization')) {
            $organizationId = $request->input('organization');
            $eventsQuery->where('organizations_id', $organizationId);
        }
        
        // Si hay organizaciones específicas seleccionadas
        if ($request->filled('organizations')) {
            $organizationIds = $request->input('organizations');
            if (!empty($organizationIds)) {
                $eventsQuery->whereIn('organizations_id', $organizationIds);
            }
        }
        
        // Buscar por nombre de evento
        if ($request->filled('event_search')) {
            $searchTerm = $request->input('event_search');
            $eventsQuery->where('name', 'like', "%{$searchTerm}%");
        }
        
        // Filtrar por estado del evento
        if ($request->filled('event_status')) {
            $status = $request->input('event_status');
            $now = Carbon::now();
            
            switch ($status) {
                case 'upcoming':
                    $eventsQuery->where('start_date', '>', $now);
                    break;
                case 'ongoing':
                    $eventsQuery->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now);
                    break;
                case 'past':
                    $eventsQuery->where('end_date', '<', $now);
                    break;
            }
        }
        
        // Filtrar por participantes mínimos y máximos
        if ($request->filled('min_participants')) {
            $minParticipants = $request->input('min_participants');
            $eventsQuery->has('participants', '>=', $minParticipants);
        }
        
        if ($request->filled('max_participants')) {
            $maxParticipants = $request->input('max_participants');
            $eventsQuery->has('participants', '<=', $maxParticipants);
        }
        
        // Filtrar por eventos específicos
        if ($hasSpecificEvents) {
            $specificEvents = $request->input('specific_events');
            $eventsQuery->whereIn('id', $specificEvents);
        }
        
        $events = $eventsQuery->get();
        
        // Calculate statistics
        $totalEvents = $events->count();
        $upcomingEvents = $events->where('start_date', '>', now())->count();
        $pastEvents = $events->where('end_date', '<', now())->count();
        $ongoingEvents = $events->where('start_date', '<=', now())
                               ->where('end_date', '>=', now())
                               ->count();
        
        // Get participants from these events
        $eventIds = $events->pluck('id')->toArray();
        $participants = Participant::whereIn('event_id', $eventIds)->get();
        $totalParticipants = $participants->count();
        
        // Get data for each chart
        $categories = Category::all();
        $eventsByCategory = $this->getEventsByCategory($categories, $eventIds);
        $eventsByMonth = $this->getEventsByMonth($events);
        $organizations = Organization::all();
        $eventsByOrganization = $this->getEventsByOrganization($organizations, $events);
        $eventsByParticipants = $this->getEventsByParticipants($events);
        
        $participantsByGender = $this->getParticipantsByGender($eventIds);
        $participantsByAge = $this->getParticipantsByAge($eventIds);
        $participantsByOrganization = $this->getParticipantsByOrganization($eventIds);
        $participantsByEducation = $this->getParticipantsByEducation($eventIds);
        $attendanceRateByCategory = $this->getAttendanceRateByCategory($eventIds);
        $attendanceRate = $this->calculateAttendanceRate($eventIds);
        
        // Nuevos datos de participantes
        $participantsByCategory = $this->getParticipantsByCategory($eventIds);
        $participantsByMonth = $this->getParticipantsByMonth($eventIds);
        
        // Guardar datos en caché (válidos por 1 hora)
        if (!$forceRefresh) {
            cache()->put($cacheKey, [
                'totalEvents' => $totalEvents,
                'upcomingEvents' => $upcomingEvents,
                'pastEvents' => $pastEvents,
                'ongoingEvents' => $ongoingEvents,
                'totalParticipants' => $totalParticipants,
                'eventsByCategory' => $eventsByCategory,
                'eventsByOrganization' => $eventsByOrganization,
                'eventsByMonth' => $eventsByMonth,
                'eventsByParticipants' => $eventsByParticipants,
                'participantsByGender' => $participantsByGender,
                'participantsByAge' => $participantsByAge,
                'participantsByOrganization' => $participantsByOrganization,
                'participantsByEducation' => $participantsByEducation,
                'attendanceRateByCategory' => $attendanceRateByCategory,
                'attendanceRate' => $attendanceRate,
                'participantsByCategory' => $participantsByCategory,
                'participantsByMonth' => $participantsByMonth
            ], 3600);
        }
        
        return view('statistics.index', [
            'hasReportPermission' => $hasReportPermission,
            'isMaster' => $isMaster,
            'categories' => Category::orderBy('description')->get(),
            'organizations' => Organization::orderBy('name')->get(),
            'supervisors' => User::whereHas('roles', function($query) {
                $query->where('name', 'supervisor');
            })->orderBy('user_name')->get(),
            'recentEvents' => Event::orderBy('created_at', 'desc')->limit(100)->get(),
            'period' => $period,
            'activeSection' => $activeSection,
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'ongoingEvents' => $ongoingEvents,
            'totalParticipants' => $totalParticipants,
            'eventsByCategory' => $eventsByCategory,
            'eventsByOrganization' => $eventsByOrganization,
            'eventsByMonth' => $eventsByMonth,
            'eventsByParticipants' => $eventsByParticipants,
            'participantsByGender' => $participantsByGender,
            'participantsByAge' => $participantsByAge,
            'participantsByOrganization' => $participantsByOrganization,
            'participantsByEducation' => $participantsByEducation,
            'attendanceRateByCategory' => $attendanceRateByCategory,
            'attendanceRate' => $attendanceRate,
            'participantsByCategory' => $participantsByCategory,
            'participantsByMonth' => $participantsByMonth,
            'fromCache' => false
        ]);
    }
    
    /**
     * Get all users in the hierarchy under a given user
     */
    private function getUserHierarchy($user)
    {
        // Start with the current user
        $userIds = [$user->id];
        
        // Get direct children
        $directChildren = User::where('parent_id', $user->id)->get();
        
        // For each direct child, get their hierarchy
        foreach ($directChildren as $child) {
            $childHierarchy = $this->getUserHierarchy($child);
            $userIds = array_merge($userIds, $childHierarchy);
        }
        
        return $userIds;
    }
    
    /**
     * Download statistics as an Excel file
     */
    public function downloadExcel(Request $request)
    {
        $user = Auth::user();
        $isMaster = $user->hasRole('master');
        $hasReportPermission = $isMaster || $user->can('download_reports');
        
        if (!$hasReportPermission) {
            abort(403, 'No tienes permiso para descargar reportes.');
        }
        
        $period = $request->input('period', 'quarter');
        
        // Preparar filtros de fechas
        $dateFrom = null;
        $dateTo = null;
        
        if ($period === 'custom' && $request->filled(['date_from', 'date_to'])) {
            $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
            $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
        } else {
            // Periodos predefinidos
            $dateTo = Carbon::now();
            
            switch ($period) {
                case 'month':
                    $dateFrom = Carbon::now()->subMonth();
                    break;
                case 'quarter':
                    $dateFrom = Carbon::now()->subMonths(3);
                    break;
                case 'year':
                    $dateFrom = Carbon::now()->subYear();
                    break;
                case 'all':
                default:
                    $dateFrom = Carbon::parse('2000-01-01');
                    break;
            }
        }
        
        // Consulta base para eventos
        $events = Event::query();
        
        // Aplicar filtros de fecha
        $events->whereBetween('start_date', [$dateFrom, $dateTo]);
        
        // Filtrar por categoría
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $events = $events->whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }
        
        // Filtrar por organización
        if ($request->filled('organization')) {
            $organizationId = $request->input('organization');
            $events = $events->where('organizations_id', $organizationId);
        }
        
        // Si hay organizaciones específicas seleccionadas
        if ($request->filled('organizations')) {
            $organizationIds = $request->input('organizations');
            if (!empty($organizationIds)) {
                $events = $events->whereIn('organizations_id', $organizationIds);
            }
        }
        
        // Filtrar por supervisor
        $filteredUserIds = [];
        if ($request->filled('supervisors')) {
            $supervisorIds = $request->input('supervisors');
            if (!empty($supervisorIds)) {
                $filteredUserIds = $supervisorIds;
                $events = $events->whereIn('user_id', $filteredUserIds);
            }
        }
        
        // Buscar por nombre de evento
        if ($request->filled('event_search')) {
            $searchTerm = $request->input('event_search');
            $events = $events->where('name', 'like', "%{$searchTerm}%");
        }
        
        // Filtrar por estado del evento
        if ($request->filled('event_status')) {
            $status = $request->input('event_status');
            $now = Carbon::now();
            
            switch ($status) {
                case 'upcoming':
                    $events = $events->where('start_date', '>', $now);
                    break;
                case 'ongoing':
                    $events = $events->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now);
                    break;
                case 'past':
                    $events = $events->where('end_date', '<', $now);
                    break;
            }
        }
        
        // Filtrar por eventos específicos
        if ($request->filled('specific_events')) {
            $specificEvents = $request->input('specific_events');
            if (!empty($specificEvents)) {
                $events = $events->whereIn('id', $specificEvents);
            }
        }
        
        // Ejecutar la consulta y obtener IDs de eventos filtrados
        $eventIds = $events->pluck('id')->toArray();
        
        // Contar eventos para estadísticas básicas
        $totalEvents = count($eventIds);
        
        // Estadísticas básicas
        $upcomingEvents = (clone $events)->where('start_date', '>', Carbon::now())->count();
        $pastEvents = (clone $events)->where('end_date', '<', Carbon::now())->count();
        $ongoingEvents = (clone $events)->where('start_date', '<=', Carbon::now())
                                      ->where('end_date', '>=', Carbon::now())
                                      ->count();
        
        $data = [
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'ongoingEvents' => $ongoingEvents,
            'pastEvents' => $pastEvents,
        ];
        
        // Añadir más datos estadísticos si están disponibles
        // Obtener eventos con datos adicionales para los gráficos
        $eventsWithData = Event::whereIn('id', $eventIds)
            ->with(['categories', 'participants' => function($query) {
                $query->select('id', 'event_id', 'attendance', 'gender', 'age', 'institution');
            }])
            ->get();
            
        // Obtener datos para cada gráfico
        $categories = Category::all();
        $data['eventsByCategory'] = $this->getEventsByCategory($categories, $eventIds);
        $data['eventsByMonth'] = $this->getEventsByMonth($eventsWithData);
        $organizations = Organization::all();
        $data['eventsByOrganization'] = $this->getEventsByOrganization($organizations, $eventsWithData);
        $data['eventsByParticipants'] = $this->getEventsByParticipants($eventsWithData);
        
        // Datos de participantes
        $data['participantsByGender'] = $this->getParticipantsByGender($eventIds);
        $data['participantsByAge'] = $this->getParticipantsByAge($eventIds);
        $data['participantsByOrganization'] = $this->getParticipantsByOrganization($eventIds);
        $data['participantsByEducation'] = $this->getParticipantsByEducation($eventIds);
        
        // Nuevos datos de participantes
        $data['participantsByCategory'] = $this->getParticipantsByCategory($eventIds);
        $data['participantsByMonth'] = $this->getParticipantsByMonth($eventIds);
        
        // Datos de asistencia
        $data['attendanceRateByCategory'] = $this->getAttendanceRateByCategory($eventIds);
        $data['attendanceRate'] = $this->calculateAttendanceRate($eventIds);
        
        // Obtener las imágenes de los gráficos enviadas desde el cliente
        if ($request->filled('chart_images')) {
            $chartImagesJson = $request->input('chart_images');
            $data['chartImages'] = json_decode($chartImagesJson, true);
        }
        
        // Obtener lista detallada de eventos incluidos en las estadísticas
        $data['eventsList'] = $this->getEventsList($eventIds);
        
        // Generar nombre de archivo con fecha y hora (usando zona horaria local y ajustando 2 horas)
        $now = Carbon::now()->setTimezone('America/Mexico_City')->addHours(2);
        $fileName = 'estadisticas_eventos_' . $now->format('d-m-Y_H-i') . '.xlsx';
        
        // Crear y descargar Excel
        return Excel::download(
            new EventsExport($data), 
            $fileName
        );
    }
    
    /**
     * Generate and download PDF with statistics
     */
    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        $isMaster = $user->hasRole('master');
        $hasReportPermission = $isMaster || $user->can('download_reports');
        
        if (!$hasReportPermission) {
            abort(403, 'No tienes permiso para descargar reportes.');
        }
        
        // Usar la misma lógica de filtrado que en el método index
        $period = $request->input('period', 'year');
        
        // Preparar filtros de fechas
        $dateFrom = null;
        $dateTo = null;
        
        if ($period === 'custom' && $request->filled(['date_from', 'date_to'])) {
            $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
            $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
        } else {
            // Periodos predefinidos
            $dateTo = Carbon::now();
            
            switch ($period) {
                case 'month':
                    $dateFrom = Carbon::now()->subMonth();
                    break;
                case 'quarter':
                    $dateFrom = Carbon::now()->subMonths(3);
                    break;
                case 'year':
                    $dateFrom = Carbon::now()->subYear();
                    break;
                case 'all':
                default:
                    $dateFrom = Carbon::parse('2000-01-01');
                    break;
            }
        }
        
        // Consulta base para eventos
        $events = Event::query();
        
        // Aplicar filtros de fecha
        $events->whereBetween('start_date', [$dateFrom, $dateTo]);
        
        // Filtrar por categoría
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $events = $events->whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }
        
        // Filtrar por organización
        if ($request->filled('organization')) {
            $organizationId = $request->input('organization');
            $events = $events->where('organizations_id', $organizationId);
        }
        
        // Si hay organizaciones específicas seleccionadas
        if ($request->filled('organizations')) {
            $organizationIds = $request->input('organizations');
            if (!empty($organizationIds)) {
                $events = $events->whereIn('organizations_id', $organizationIds);
            }
        }
        
        // Filtrar por supervisor
        $filteredUserIds = [];
        if ($request->filled('supervisors')) {
            $supervisorIds = $request->input('supervisors');
            if (!empty($supervisorIds)) {
                $filteredUserIds = $supervisorIds;
                $events = $events->whereIn('user_id', $filteredUserIds);
            }
        }
        
        // Buscar por nombre de evento
        if ($request->filled('event_search')) {
            $searchTerm = $request->input('event_search');
            $events = $events->where('name', 'like', "%{$searchTerm}%");
        }
        
        // Filtrar por estado del evento
        if ($request->filled('event_status')) {
            $status = $request->input('event_status');
            $now = Carbon::now();
            
            switch ($status) {
                case 'upcoming':
                    $events = $events->where('start_date', '>', $now);
                    break;
                case 'ongoing':
                    $events = $events->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now);
                    break;
                case 'past':
                    $events = $events->where('end_date', '<', $now);
                    break;
            }
        }
        
        // Filtrar por eventos específicos
        if ($request->filled('specific_events')) {
            $specificEvents = $request->input('specific_events');
            if (!empty($specificEvents)) {
                $events = $events->whereIn('id', $specificEvents);
            }
        }
        
        // Ejecutar la consulta y obtener IDs de eventos filtrados
        $eventIds = $events->pluck('id')->toArray();
        
        // Contar eventos para estadísticas básicas
        $totalEvents = count($eventIds);
        
        // Estadísticas básicas
        $upcomingEvents = (clone $events)->where('start_date', '>', Carbon::now())->count();
        $pastEvents = (clone $events)->where('end_date', '<', Carbon::now())->count();
        $ongoingEvents = (clone $events)->where('start_date', '<=', Carbon::now())
                                           ->where('end_date', '>=', Carbon::now())
                                           ->count();
        
        // Obtener eventos para gráficos
        $eventsWithData = Event::whereIn('id', $eventIds)
            ->with(['categories', 'participants' => function($query) {
                $query->select('id', 'event_id', 'attendance');
            }])
            ->get();
        
        // Contar participantes de manera eficiente
        $totalParticipants = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->count();
        
        $attendedParticipants = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->where('attendance', true)
            ->count();
        
        $attendanceRate = $totalParticipants > 0 ? round(($attendedParticipants / $totalParticipants) * 100, 1) : 0;
        
        // Obtener datos para cada gráfico
        $categories = Category::all();
        $eventsByCategory = $this->getEventsByCategory($categories, $eventIds);
        $eventsByMonth = $this->getEventsByMonth($eventsWithData);
        $organizations = Organization::all();
        $eventsByOrganization = $this->getEventsByOrganization($organizations, $eventsWithData);
        $eventsByParticipants = $this->getEventsByParticipants($eventsWithData);
        
        $participantsByGender = $this->getParticipantsByGender($eventIds);
        $participantsByAge = $this->getParticipantsByAge($eventIds);
        $participantsByOrganization = $this->getParticipantsByOrganization($eventIds);
        $participantsByEducation = $this->getParticipantsByEducation($eventIds);
        $attendanceRateByCategory = $this->getAttendanceRateByCategory($eventIds);
        
        // Nuevos datos para las gráficas adicionales
        $participantsByCategory = $this->getParticipantsByCategory($eventIds);
        $participantsByMonth = $this->getParticipantsByMonth($eventIds);
        
        // Obtener lista detallada de eventos incluidos en estas estadísticas
        $eventsList = $this->getEventsList($eventIds);
        
        // Obtener las imágenes de los gráficos enviadas desde el cliente
        $chartImages = [];
        if ($request->filled('chart_images')) {
            $chartImagesJson = $request->input('chart_images');
            $chartImages = json_decode($chartImagesJson, true);
        }
        
        // Generar datos para incluir en el PDF
        $data = [
            'date' => Carbon::now()->format('d/m/Y'),
            'user' => $user->user_name,
            'period' => $this->getPeriodText($period, $dateFrom, $dateTo),
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'ongoingEvents' => $ongoingEvents,
            'totalParticipants' => $totalParticipants,
            'attendanceRate' => $attendanceRate,
            'eventsByCategory' => $eventsByCategory,
            'eventsByOrganization' => $eventsByOrganization,
            'eventsByMonth' => $eventsByMonth,
            'eventsByParticipants' => $eventsByParticipants,
            'participantsByGender' => $participantsByGender,
            'participantsByAge' => $participantsByAge,
            'participantsByOrganization' => $participantsByOrganization,
            'participantsByEducation' => $participantsByEducation,
            'attendanceRateByCategory' => $attendanceRateByCategory,
            'participantsByCategory' => $participantsByCategory,
            'participantsByMonth' => $participantsByMonth,
            'chartImages' => $chartImages, // Agregar las imágenes de los gráficos
            'eventsList' => $eventsList // Agregar la lista detallada de eventos
        ];
        
        // Generar PDF
        $pdf = \PDF::loadView('statistics.pdf', $data);
        
        // Descargar PDF con formato más descriptivo y zona horaria local ajustada
        $now = Carbon::now()->setTimezone('America/Mexico_City')->addHours(2);
        return $pdf->download('estadisticas_eventos_' . $now->format('d-m-Y_H-i') . '.pdf');
    }
    
    /**
     * Get human-readable period text
     */
    private function getPeriodText($period, $dateFrom, $dateTo)
    {
        switch ($period) {
            case 'month':
                return 'Último mes (' . $dateFrom->format('d/m/Y') . ' - ' . $dateTo->format('d/m/Y') . ')';
            case 'quarter':
                return 'Último trimestre (' . $dateFrom->format('d/m/Y') . ' - ' . $dateTo->format('d/m/Y') . ')';
            case 'year':
                return 'Último año (' . $dateFrom->format('d/m/Y') . ' - ' . $dateTo->format('d/m/Y') . ')';
            case 'all':
                return 'Todo el tiempo';
            case 'custom':
                return 'Período personalizado (' . $dateFrom->format('d/m/Y') . ' - ' . $dateTo->format('d/m/Y') . ')';
            default:
                return 'Período desconocido';
        }
    }
    
    /**
     * Get events by category
     */
    private function getEventsByCategory($categories, $eventIds)
    {
        $eventsByCategory = [];
        
        foreach ($categories as $category) {
            $count = DB::table('event_category')
                ->join('events', 'event_category.event_id', '=', 'events.id')
                ->whereIn('events.id', $eventIds)
                ->where('event_category.category_id', $category->id)
                ->count();
            
            if ($count > 0) {
                $eventsByCategory[] = [
                    'name' => $category->description,
                    'count' => $count
                ];
            }
        }
        
        return $eventsByCategory;
    }
    
    /**
     * Get events by month
     */
    private function getEventsByMonth($events)
    {
        // Array de traducción de meses
        $mesesEspanol = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        $eventsByMonth = [];
        
        // Crear array para cada mes
        foreach ($mesesEspanol as $month => $monthName) {
            $count = $events->filter(function ($event) use ($month) {
                return $event->start_date->month == $month;
            })->count();
            
            $eventsByMonth[] = [
                'month' => $monthName,
                'count' => $count
            ];
        }
        
        return $eventsByMonth;
    }
    
    /**
     * Get events by participants count
     */
    private function getEventsByParticipants($events)
    {
        return $events->map(function ($event) {
            return [
                'name' => $event->name,
                'count' => $event->participants->count()
            ];
        })->sortByDesc('count')->take(10)->values()->all();
    }
    
    /**
     * Get participants by gender
     */
    private function getParticipantsByGender($eventIds)
    {
        // Primero intentar obtener datos de la tabla personal_data
        $maleCount = DB::table('participants')
            ->leftJoin('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->where(function($query) {
                $query->where('personal_data.sex', 'M')
                      ->orWhere('participants.gender', 'M');
            })
            ->count();
        
        $femaleCount = DB::table('participants')
            ->leftJoin('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->where(function($query) {
                $query->where('personal_data.sex', 'F')
                      ->orWhere('participants.gender', 'F');
            })
            ->count();
        
        $otherCount = DB::table('participants')
            ->leftJoin('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->where(function($query) {
                $query->where('personal_data.sex', 'O')
                      ->orWhere('participants.gender', 'O');
            })
            ->count();
        
        // Contar participantes sin datos de género
        $totalParticipants = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->count();
        
        $unspecifiedCount = $totalParticipants - ($maleCount + $femaleCount + $otherCount);
        
        // Asegurar que no haya valores negativos
        $unspecifiedCount = max(0, $unspecifiedCount);
        
        return [
            ['name' => 'Masculino', 'count' => $maleCount],
            ['name' => 'Femenino', 'count' => $femaleCount],
            ['name' => 'Otro', 'count' => $otherCount],
            ['name' => 'No especificado', 'count' => $unspecifiedCount]
        ];
    }
    
    /**
     * Get participants by age
     */
    private function getParticipantsByAge($eventIds)
    {
        $currentDate = now();
        
        // Obtener conteo de participantes por rango de edad desde la tabla personal_data usando birth_date
        // Menores de 18 años
        $under18Count = DB::table('participants')
            ->join('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->whereNotNull('personal_data.birth_date')
            ->whereRaw("TIMESTAMPDIFF(YEAR, personal_data.birth_date, CURDATE()) < 18")
            ->count();
        
        // 18-25 años
        $age18to25Count = DB::table('participants')
            ->join('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->whereNotNull('personal_data.birth_date')
            ->whereRaw("TIMESTAMPDIFF(YEAR, personal_data.birth_date, CURDATE()) BETWEEN 18 AND 25")
            ->count();
        
        // 26-35 años
        $age26to35Count = DB::table('participants')
            ->join('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->whereNotNull('personal_data.birth_date')
            ->whereRaw("TIMESTAMPDIFF(YEAR, personal_data.birth_date, CURDATE()) BETWEEN 26 AND 35")
            ->count();
        
        // 36-50 años
        $age36to50Count = DB::table('participants')
            ->join('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->whereNotNull('personal_data.birth_date')
            ->whereRaw("TIMESTAMPDIFF(YEAR, personal_data.birth_date, CURDATE()) BETWEEN 36 AND 50")
            ->count();
        
        // Mayores de 50 años
        $over50Count = DB::table('participants')
            ->join('personal_data', 'participants.personal_data_id', '=', 'personal_data.id')
            ->whereIn('participants.event_id', $eventIds)
            ->whereNotNull('personal_data.birth_date')
            ->whereRaw("TIMESTAMPDIFF(YEAR, personal_data.birth_date, CURDATE()) > 50")
            ->count();
        
        // Contar participantes sin datos personales o con fecha de nacimiento no especificada
        $unspecifiedCount = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->where(function($query) {
                $query->whereNull('personal_data_id')
                      ->orWhereExists(function($subquery) {
                          $subquery->select(DB::raw(1))
                                  ->from('personal_data')
                                  ->whereColumn('personal_data.id', 'participants.personal_data_id')
                                  ->whereNull('personal_data.birth_date');
                      });
            })
            ->count();
        
        // Si no hay datos, agregar valores por defecto
        if (($under18Count + $age18to25Count + $age26to35Count + $age36to50Count + $over50Count + $unspecifiedCount) == 0) {
            // Verificar si hay participantes
            $totalParticipants = DB::table('participants')
                ->whereIn('event_id', $eventIds)
                ->count();
            
            if ($totalParticipants > 0) {
                $unspecifiedCount = $totalParticipants;
            }
        }
        
        return [
            ['name' => 'Menores de 18', 'count' => $under18Count],
            ['name' => '18-25', 'count' => $age18to25Count],
            ['name' => '26-35', 'count' => $age26to35Count],
            ['name' => '36-50', 'count' => $age36to50Count],
            ['name' => 'Mayores de 50', 'count' => $over50Count],
            ['name' => 'No especificado', 'count' => $unspecifiedCount]
        ];
    }
    
    /**
     * Get participants by education level
     */
    private function getParticipantsByEducation($eventIds)
    {
        $educationLevels = ['Primaria', 'Secundaria', 'Universitaria', 'Postgrado'];
        $participantsByEducation = [];
        
        foreach ($educationLevels as $level) {
            $count = DB::table('participants')
                ->whereIn('event_id', $eventIds)
                ->where('education_level', $level)
                ->count();
            
            if ($count > 0) {
                $participantsByEducation[] = [
                    'name' => $level,
                    'count' => $count
                ];
            }
        }
        
        // Añadir "No especificado" si hay participantes sin nivel educativo
        $noEducationCount = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->whereNull('education_level')
            ->count();
        
        if ($noEducationCount > 0) {
            $participantsByEducation[] = [
                'name' => 'No especificado',
                'count' => $noEducationCount
            ];
        }
        
        return $participantsByEducation;
    }
    
    /**
     * Get attendance rate by category
     */
    private function getAttendanceRateByCategory($eventIds)
    {
        $attendanceRateByCategory = [];
        
        $totalCategoryParticipants = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->count();
        
        if ($totalCategoryParticipants > 0) {
            $attendedCount = DB::table('participants')
                ->whereIn('event_id', $eventIds)
                ->where('attendance', true)
                ->count();
            
            $attendanceRate = round(($attendedCount / $totalCategoryParticipants) * 100, 2);
            
            $attendanceRateByCategory[] = [
                'name' => 'General',
                'rate' => $attendanceRate
            ];
        }
        
        return $attendanceRateByCategory;
    }
    
    /**
     * Calculate attendance rate
     */
    private function calculateAttendanceRate($eventIds)
    {
        $totalParticipants = DB::table('participants')
            ->whereIn('event_id', $eventIds)
            ->count();
        
        if ($totalParticipants > 0) {
            $attendedCount = DB::table('participants')
                ->whereIn('event_id', $eventIds)
                ->where('attendance', true)
                ->count();
            
            return round(($attendedCount / $totalParticipants) * 100, 1);
        }
        
        return 0;
    }
    
    /**
     * Clear statistics cache
     */
    public function clearCache(Request $request)
    {
        // Limpiar la caché específica del usuario actual
        $user = Auth::user();
        $cacheKeyPattern = 'statistics_' . md5(json_encode(['user_id' => $user->id]));
        
        // Simplemente olvidar la clave de caché actual
        cache()->forget($cacheKeyPattern);
        
        // Forzar actualización de datos y establecer periodo a 'quarter'
        return redirect()->route('statistics.index', ['force_refresh' => true, 'period' => 'quarter'])
                         ->with('success', 'Caché de estadísticas limpiada correctamente.');
    }
    
    /**
     * Get participants by organization
     */
    private function getParticipantsByOrganization($eventIds)
    {
        // Obtener los participantes por organización (Top 10)
        $participantsByOrganization = DB::table('participants')
            ->join('events', 'participants.event_id', '=', 'events.id')
            ->join('organizations', 'events.organizations_id', '=', 'organizations.id')
            ->whereIn('participants.event_id', $eventIds)
            ->select('organizations.name', DB::raw('COUNT(participants.id) as count'))
            ->groupBy('organizations.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'count' => $item->count
                ];
            })
            ->toArray();
        
        return $participantsByOrganization;
    }
    
    /**
     * Get events by category for API
     */
    public function getEventsByApi($categoryId = null)
    {
        if ($categoryId) {
            $events = Event::whereHas('categories', function($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })->orderBy('created_at', 'desc')->limit(100)->get(['id', 'name', 'start_date']);
        } else {
            $events = Event::orderBy('created_at', 'desc')->limit(100)->get(['id', 'name', 'start_date']);
        }
        
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }
    
    /**
     * Get events by organization for API
     */
    public function getEventsByOrganizationApi($organizationId = null)
    {
        if ($organizationId) {
            $events = Event::where('organizations_id', $organizationId)
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get(['id', 'name', 'start_date']);
        } else {
            $events = Event::orderBy('created_at', 'desc')->limit(100)->get(['id', 'name', 'start_date']);
        }
        
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }
    
    /**
     * Get participants by category
     */
    private function getParticipantsByCategory($eventIds)
    {
        // Obtener los participantes por categoría
        $participantsByCategory = DB::table('participants')
            ->join('events', 'participants.event_id', '=', 'events.id')
            ->join('event_category', 'events.id', '=', 'event_category.event_id')
            ->join('categories', 'event_category.category_id', '=', 'categories.id')
            ->whereIn('participants.event_id', $eventIds)
            ->select('categories.description as name', DB::raw('COUNT(participants.id) as count'))
            ->groupBy('categories.description')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'count' => $item->count
                ];
            })
            ->toArray();
        
        return $participantsByCategory;
    }
    
    /**
     * Get participants by month
     */
    private function getParticipantsByMonth($eventIds)
    {
        // Array de traducción de meses
        $mesesEspanol = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        $currentYear = Carbon::now()->year;
        $participantsByMonth = [];
        
        // Crear array para cada mes
        foreach ($mesesEspanol as $month => $monthName) {
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            
            // Contar participantes de este mes
            $count = DB::table('participants')
                ->join('events', 'participants.event_id', '=', 'events.id')
                ->whereIn('participants.event_id', $eventIds)
                ->whereBetween('events.start_date', [$startDate, $endDate])
                ->count();
            
            $participantsByMonth[] = [
                'month' => $monthName,
                'count' => $count
            ];
        }
        
        return $participantsByMonth;
    }
    
    /**
     * Get events by organization
     */
    private function getEventsByOrganization($organizations, $events)
    {
        $eventsByOrganization = [];
        $organizationsMap = [];
        
        // Crear un mapa de organizaciones para acceso rápido
        foreach ($organizations as $organization) {
            $organizationsMap[$organization->id] = $organization->name;
        }
        
        // Agrupar eventos por organización
        $eventsByOrganizationId = $events->groupBy('organizations_id');
        
        // Procesar cada grupo
        foreach ($eventsByOrganizationId as $organizationId => $organizationEvents) {
            $name = isset($organizationsMap[$organizationId]) ? $organizationsMap[$organizationId] : 'Sin organización';
            $count = $organizationEvents->count();
            
            if ($count > 0) {
                $eventsByOrganization[] = [
                    'name' => $name,
                    'count' => $count
                ];
            }
        }
        
        // Si no hay datos, agregar un valor por defecto
        if (empty($eventsByOrganization) && $events->count() > 0) {
            $eventsByOrganization[] = [
                'name' => 'Sin organización',
                'count' => $events->count()
            ];
        }
        
        // Asegurar que se muestren todas las organizaciones que tienen eventos
        if (!empty($eventsByOrganization)) {
            // Ordenar por cantidad de eventos (descendente)
            usort($eventsByOrganization, function($a, $b) {
                return $b['count'] - $a['count'];
            });
        }
        
        return $eventsByOrganization;
    }
    
    /**
     * Get detailed list of events included in the statistics
     */
    private function getEventsList($eventIds)
    {
        // Get events with all needed data
        $events = Event::whereIn('id', $eventIds)
            ->with(['categories', 'organization'])
            ->withCount('participants')
            ->orderBy('start_date', 'desc')
            ->get();
        
        $eventsList = [];
        foreach ($events as $event) {
            $eventsList[] = [
                'name' => $event->name,
                'organization' => $event->organization->name ?? 'No definida',
                'participants' => $event->participants_count,
                'location' => $event->location,
                'categories' => $event->categories->pluck('description')->implode(', '),
                'dates' => 'Inicio: ' . Carbon::parse($event->start_date)->format('d/m/Y') . 
                         ' - Fin: ' . Carbon::parse($event->end_date)->format('d/m/Y')
            ];
        }
        
        return $eventsList;
    }
} 