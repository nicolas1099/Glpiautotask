<?php

// install_db.php - Script para crear la tabla de TechTask manualmente vía web
// Sube este archivo a la raíz de tu GLPI o a la carpeta del plugin y ejecútalo en el navegador.

include('../../../inc/includes.php');

header("Content-Type: text/plain");

echo "--- Instalador de Base de Datos TechTask ---\n";

global $DB;

if (!isset($DB)) {
    die("ERROR: No se pudo conectar a GLPI. Asegúrate de que el archivo está en la ruta correcta.\n");
}

$tableName = 'glpi_plugin_techtask_records';

echo "Verificando tabla $tableName...\n";

$query = "CREATE TABLE IF NOT EXISTS `$tableName` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `tickets_id` INT(11) NOT NULL,
    `users_id` INT(11) NOT NULL,
    `category_id` INT(11) DEFAULT NULL,
    `duration_minutes` INT(11) NOT NULL,
    `description` TEXT,
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `tickets_id` (`tickets_id`),
    KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($DB->query($query)) {
    echo "¡TABLA CREADA O VERIFICADA CORRECTAMENTE!\n";
    echo "Ya puedes volver a GLPI e instalar/activar el plugin.\n";
} else {
    echo "ERROR al crear la tabla: " . $DB->error() . "\n";
}
