<?php
// model/database.php

// Ruta al archivo de la base de datos SQLite
$dbFile = __DIR__ . '/../data/app.db';

// Crear o abrir la base de datos
$db = new SQLite3($dbFile);
$db->exec('PRAGMA foreign_keys = ON;');
// Crear tabla de usuarios si no existe
$createTableQuery = "
CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);";
if (!$db->exec($createTableQuery)) {
	die("Error creando la tabla: " . $db->lastErrorMsg());
}

$createTableQuery = "CREATE TABLE IF NOT EXISTS posts (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	user_id INTEGER,
	likes INTEGER,
	FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
);";
if (!$db->exec($createTableQuery)) {
	die("Error creando la tabla: " . $db->lastErrorMsg());
}
$createTableQuery = "CREATE TABLE IF NOT EXISTS comments(
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	user_id INTEGER,
	post_id INTEGER,
	comment TEXT,
    FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY(post_id) REFERENCES posts(id) ON DELETE CASCADE
);";

if (!$db->exec($createTableQuery)) {
	die("Error creando la tabla: " . $db->lastErrorMsg());
}

echo "Base de datos y tablas 'user', 'posts' y 'comments' creadas correctamente.\n";


?>