-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-08-2025 a las 18:27:27
-- Versión del servidor: 10.11.10-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u180743896_laravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `assists`
--

CREATE TABLE `assists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `assists`
--

INSERT INTO `assists` (`id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, '2025-07-22 14:00:50', '2025-07-22 14:00:50', '2025-07-22 14:00:50', '2025-07-22 14:00:50'),
(2, '2025-07-30 13:53:47', '2025-07-30 13:53:47', '2025-07-30 13:53:47', '2025-07-30 13:53:47'),
(3, '2025-07-30 14:04:18', '2025-07-30 14:04:18', '2025-07-30 14:04:18', '2025-07-30 14:04:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Musical', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(2, 'Teatral', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(3, 'Político', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(4, 'Deportivo', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(5, 'Educativo', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(6, 'Cultural', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(7, 'Empresarial', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(8, 'Social', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(9, 'Religioso', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(10, 'Tecnológico', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(11, 'Elecciones', '2025-07-20 21:17:42', '2025-07-20 21:17:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `organizations_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `location`, `img`, `start_date`, `end_date`, `organizations_id`, `user_id`, `slug`, `qr_code`, `created_at`, `updated_at`) VALUES
(1, 'Evento 1', 'Descripción', 'Carcasa', 'events/vhkQfNSNUrxIDQsNyIKMAZy5Pv75bI84GvGihiHR.jpg', '2025-07-23 09:59:00', '2025-07-24 09:59:00', 1, 1, 'sv000001-evento-1', 'events/qr/sv000001-evento-1.svg', '2025-07-22 13:59:42', '2025-07-22 13:59:42'),
(4, 'Arena', 'Vdbdjd', 'Parque central', 'events/Eh8c1csdEn5s7ZA4rKGnOAtgpvz6HsyaMfT1Bwmw.jpg', '2025-07-30 09:46:00', '2025-07-30 10:46:00', 1, 1, 'sv000004-arena', 'events/qr/sv000004-arena.svg', '2025-07-30 13:47:02', '2025-07-30 13:47:02'),
(5, 'Arena rebreyne', 'Vdhdjdbs', 'Parque central', 'events/eVO4GcjWFlEapGYckb0cDMiFAix8pd4Ks9Rt2nou.jpg', '2025-07-30 10:01:00', '2025-07-30 12:01:00', 1, 1, 'sv000005-arena-rebreyne', 'events/qr/sv000005-arena-rebreyne.svg', '2025-07-30 14:02:51', '2025-07-30 14:02:51'),
(6, 'evento en curso prueba', 'asdasdasdasdasdasdasd', 'asdasdasd', 'events/pLdYrc8NcE9SeXtMQsJ7RSQMFq4QVeluCcRPgfU3.png', '2025-08-04 16:10:00', '2025-08-19 16:10:00', 1, 1, 'sv000006-evento-en-curso-prueba', 'events/qr/sv000006-evento-en-curso-prueba.svg', '2025-08-05 20:10:30', '2025-08-05 20:10:30'),
(7, 'prueba 2 evento en curso', 'asdasdasdasd', 'asdasd', 'events/IMl5h3VKML7s9BQdqKvyDbtUKMFUTbKskK6JCaWD.png', '2025-08-01 16:12:00', '2025-08-30 16:12:00', 1, 1, 'sv000007-prueba-2-evento-en-curso', 'events/qr/sv000007-prueba-2-evento-en-curso.svg', '2025-08-05 20:12:34', '2025-08-05 20:12:34'),
(9, 'asdasd asdasd', 'asdasd', 'sss', 'events/qonJ3IYaLBCEsT9PwNRgKQvSAe5ZKwnnZyWHhByY.png', '2025-08-23 14:42:00', '2025-08-28 14:43:00', 1, 1, 'asdasd-asdasd-UtDfecPe', 'events/qr/asdasd-asdasd-UtDfecPe.svg', '2025-08-12 18:43:02', '2025-08-12 18:43:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event_category`
--

CREATE TABLE `event_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `event_category`
--

INSERT INTO `event_category` (`id`, `event_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(24, 4, 1, NULL, NULL),
(25, 4, 2, NULL, NULL),
(26, 4, 3, NULL, NULL),
(27, 4, 4, NULL, NULL),
(28, 4, 5, NULL, NULL),
(29, 4, 6, NULL, NULL),
(30, 4, 7, NULL, NULL),
(31, 4, 8, NULL, NULL),
(32, 4, 9, NULL, NULL),
(33, 4, 10, NULL, NULL),
(34, 4, 11, NULL, NULL),
(35, 5, 1, NULL, NULL),
(36, 5, 2, NULL, NULL),
(37, 5, 3, NULL, NULL),
(38, 5, 4, NULL, NULL),
(39, 5, 5, NULL, NULL),
(40, 5, 6, NULL, NULL),
(41, 5, 7, NULL, NULL),
(42, 5, 8, NULL, NULL),
(43, 5, 10, NULL, NULL),
(44, 6, 2, NULL, NULL),
(45, 6, 6, NULL, NULL),
(46, 7, 2, NULL, NULL),
(47, 7, 6, NULL, NULL),
(49, 9, 3, NULL, NULL),
(50, 9, 7, NULL, NULL),
(51, 9, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000000_create_institutions_table', 1),
(6, '2024_01_01_000001_create_roles_table', 1),
(7, '2024_01_01_000002_create_users_roles_table', 1),
(8, '2024_01_01_000003_create_permissions_table', 1),
(9, '2024_01_01_000004_create_user_permissions_table', 1),
(10, '2024_01_01_000005_create_personal_data_table', 1),
(11, '2024_01_01_000006_create_users_personal_data_table', 1),
(12, '2024_01_01_000008_create_events_table', 1),
(13, '2024_01_01_000009_create_categories_table', 1),
(14, '2024_01_01_000010_create_event_category_table', 1),
(15, '2024_01_01_000011_create_assists_table', 1),
(16, '2024_01_01_000012_create_participants_table', 1),
(17, '2024_01_01_000100_add_foreign_keys_to_users', 1),
(18, '2024_01_01_000101_add_foreign_keys_to_events', 1),
(19, '2024_07_01_000000_rename_institutions_to_organizations', 1),
(20, '2025_03_08_170637_add_slug_and_qr_code_to_events_table', 1),
(21, '2025_03_08_205337_add_preferences_to_users_table', 1),
(22, '2025_03_08_215155_add_fields_to_participants_table', 1),
(23, '2025_03_16_203447_create_plain_passwords_table', 1),
(24, '2025_03_23_144035_add_email_to_personal_data_table', 1),
(25, '2025_04_27_172940_modify_personal_data_add_birth_date', 1),
(26, '2025_05_03_222229_remove_address_from_personal_data', 1),
(27, '2025_05_03_223944_add_birth_date_to_participants_table', 1),
(28, '2025_05_03_225641_update_event_slugs_to_new_format', 1),
(29, '2025_05_03_230406_remove_email_from_participants_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `description`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Master Organization', 'Default organization for the Master user', 'Main Office', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(2, 'Universidad Central', 'Universidad Central de Venezuela', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(3, 'Universidad Simón Bolívar', 'Universidad Simón Bolívar', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(4, 'Universidad de Los Andes', 'Universidad de Los Andes', 'Mérida, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(5, 'Ministerio de Educación', 'Ministerio de Educación de Venezuela', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(6, 'Ministerio de Salud', 'Ministerio del Poder Popular para la Salud', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(7, 'Ministerio de Ciencia y Tecnología', 'Ministerio del Poder Popular para Ciencia y Tecnología', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(8, 'PDVSA', 'Petróleos de Venezuela S.A.', 'Caracas, Venezuela', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(9, 'CANTV', 'Compañía Anónima Nacional Teléfonos de Venezuela', 'Caracas, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(10, 'Gobernación de Miranda', 'Gobierno del Estado Miranda', 'Los Teques, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(11, 'Gobernación de Zulia', 'Gobierno del Estado Zulia', 'Maracaibo, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(12, 'Gobernación de Carabobo', 'Gobierno del Estado Carabobo', 'Valencia, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(13, 'Gobernación de Aragua', 'Gobierno del Estado Aragua', 'Maracay, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(14, 'Gobernación de Lara', 'Gobierno del Estado Lara', 'Barquisimeto, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(15, 'Hospital Universitario', 'Hospital Universitario de Caracas', 'Caracas, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42'),
(16, 'IVSS', 'Instituto Venezolano de los Seguros Sociales', 'Caracas, Venezuela', '2025-07-20 21:17:42', '2025-07-20 21:17:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participants`
--

CREATE TABLE `participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `personal_data_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assists_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `attendance` tinyint(1) NOT NULL DEFAULT 0,
  `dni` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `ticket_type` varchar(255) DEFAULT NULL,
  `seat_number` varchar(255) DEFAULT NULL,
  `team` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `participant_type` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `participants`
--

INSERT INTO `participants` (`id`, `event_id`, `personal_data_id`, `assists_id`, `created_at`, `updated_at`, `name`, `last_name`, `phone`, `attendance`, `dni`, `address`, `birth_date`, `gender`, `institution`, `profession`, `education_level`, `ticket_type`, `seat_number`, `team`, `category`, `participant_type`, `age`) VALUES
(1, 1, 2, 1, '2025-07-22 14:00:34', '2025-07-22 14:00:50', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 4, 4, 2, '2025-07-30 13:53:23', '2025-07-30 13:53:47', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 5, 3, 3, '2025-07-30 14:04:00', '2025-07-30 14:04:18', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'create_user', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(2, 'edit_user', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(3, 'delete_user', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(4, 'create_event', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(5, 'edit_event', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(6, 'delete_event', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(7, 'download_reports', '2025-07-20 21:17:41', '2025-07-20 21:17:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_data`
--

CREATE TABLE `personal_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sex` varchar(255) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `dni` varchar(255) NOT NULL,
  `type_dni` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal_data`
--

INSERT INTO `personal_data` (`id`, `name`, `last_name`, `phone`, `email`, `sex`, `birth_date`, `dni`, `type_dni`, `created_at`, `updated_at`) VALUES
(1, 'Santiago Isaac Salazar Martínez', 'Salazar Martínez', '+584123275955', NULL, 'M', '2022-07-05', '27783053', 'ID', '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(2, 'Mengano', 'Fulano', '04123275955', NULL, 'M', '2025-07-01', '111111', 'E', '2025-07-22 14:00:34', '2025-07-22 14:00:34'),
(3, 'rebreyne', 'espinoza', '04141826792', NULL, 'M', '2006-09-12', '31777132', 'V', '2025-07-30 13:39:37', '2025-07-30 13:39:37'),
(4, 'ADRIANY', 'GONZALES', '04140191397', NULL, 'F', '1998-07-08', '30430356', 'V', '2025-07-30 13:40:24', '2025-07-30 13:40:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plain_passwords`
--

CREATE TABLE `plain_passwords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plain_password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Master', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(2, 'Admin', '2025-07-20 21:17:41', '2025-07-20 21:17:41'),
(3, 'User', '2025-07-20 21:17:41', '2025-07-20 21:17:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `organizations_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `user_name`, `email_verified_at`, `password`, `organizations_id`, `parent_id`, `preferences`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'santiagosalazar.dev@gmail.com', 'santiagosalazar.dev@gmail.com', NULL, '$2y$10$sqKiVRcscoDVvESDlDjvm.VUSvw0RC90VrjloPr5T69rkkTK7Oine', 1, NULL, NULL, NULL, '2025-07-22 13:59:01', '2025-07-22 13:59:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_personal_data`
--

CREATE TABLE `users_personal_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `personal_data_id` bigint(20) UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users_personal_data`
--

INSERT INTO `users_personal_data` (`id`, `user_id`, `personal_data_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-07-22 13:59:01', '2025-07-22 13:59:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_roles`
--

CREATE TABLE `users_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `roles_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users_roles`
--

INSERT INTO `users_roles` (`id`, `user_id`, `roles_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-07-22 13:59:01', '2025-07-22 13:59:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permissions_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permissions_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(2, 1, 2, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(3, 1, 3, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(4, 1, 4, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(5, 1, 5, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(6, 1, 6, '2025-07-22 13:59:01', '2025-07-22 13:59:01'),
(7, 1, 7, '2025-07-22 13:59:01', '2025-07-22 13:59:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `assists`
--
ALTER TABLE `assists`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
  ADD KEY `events_organizations_id_foreign` (`organizations_id`),
  ADD KEY `events_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `event_category`
--
ALTER TABLE `event_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_category_event_id_foreign` (`event_id`),
  ADD KEY `event_category_category_id_foreign` (`category_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participants_event_id_foreign` (`event_id`),
  ADD KEY `participants_personal_data_id_foreign` (`personal_data_id`),
  ADD KEY `participants_assists_id_foreign` (`assists_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `personal_data`
--
ALTER TABLE `personal_data`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plain_passwords`
--
ALTER TABLE `plain_passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plain_passwords_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_organizations_id_foreign` (`organizations_id`),
  ADD KEY `users_parent_id_foreign` (`parent_id`);

--
-- Indices de la tabla `users_personal_data`
--
ALTER TABLE `users_personal_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_personal_data_user_id_foreign` (`user_id`),
  ADD KEY `users_personal_data_personal_data_id_foreign` (`personal_data_id`);

--
-- Indices de la tabla `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_roles_user_id_foreign` (`user_id`),
  ADD KEY `users_roles_roles_id_foreign` (`roles_id`);

--
-- Indices de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_permissions_user_id_foreign` (`user_id`),
  ADD KEY `user_permissions_permissions_id_foreign` (`permissions_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `assists`
--
ALTER TABLE `assists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `event_category`
--
ALTER TABLE `event_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `participants`
--
ALTER TABLE `participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `plain_passwords`
--
ALTER TABLE `plain_passwords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users_personal_data`
--
ALTER TABLE `users_personal_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_organizations_id_foreign` FOREIGN KEY (`organizations_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `event_category`
--
ALTER TABLE `event_category`
  ADD CONSTRAINT `event_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_category_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_assists_id_foreign` FOREIGN KEY (`assists_id`) REFERENCES `assists` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participants_personal_data_id_foreign` FOREIGN KEY (`personal_data_id`) REFERENCES `personal_data` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plain_passwords`
--
ALTER TABLE `plain_passwords`
  ADD CONSTRAINT `plain_passwords_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_organizations_id_foreign` FOREIGN KEY (`organizations_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `users_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `users_personal_data`
--
ALTER TABLE `users_personal_data`
  ADD CONSTRAINT `users_personal_data_personal_data_id_foreign` FOREIGN KEY (`personal_data_id`) REFERENCES `personal_data` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_personal_data_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `users_roles_roles_id_foreign` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_permissions_id_foreign` FOREIGN KEY (`permissions_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
