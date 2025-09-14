<?php
// index.php
ini_set('log_errors', 1);
ini_set('error_log', 'php://stderr'); // enviar logs a STDERR
ini_set('display_errors', 0); 


// Incluir la conexión / esquemas
require_once __DIR__ .'/../model/db.php'; // db.php debería crear la conexión $db

// Registrar rutas desde otros archivos
$routes = [];

// Cada archivo devuelve un array de rutas
$routes = array_merge(
    $routes,
    require __DIR__ .'/routes/login.php'
    // require 'routes/other.php', etc.
);

// Obtener la ruta solicitada
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');

// quitar prefijo /server si existe
$prefix = '/server';
if (str_starts_with($path, $prefix)) {
    $path = substr($path, strlen($prefix));
}
$method = $_SERVER['REQUEST_METHOD'];
error_log("Login attempt for user: $path");

// Verificar si la ruta existe y ejecutar su función
if (isset($routes[$path])) {
    $routes[$path]($db, $method); // Pasamos la conexión a SQLite3
} else {
    http_response_code(404);
    echo "Ruta no encontrada";
}
?>