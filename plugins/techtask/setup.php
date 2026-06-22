<?php

// setup.php
define('PLUGIN_TECHTASK_VERSION', '1.0.0');

function plugin_version_techtask() {
    return [
        'name'           => 'TechTask',
        'version'        => PLUGIN_TECHTASK_VERSION,
        'author'         => 'Tu Nombre',
        'license'        => 'MIT',
        'homepage'       => 'https://tu-sitio.com',
        'requirements'   => [
            'glpi' => [
                'min' => '10.0.0',
                'max' => '10.0.99'
            ]
        ]
    ];
}

function plugin_init_techtask() {
    global $PLUGIN_HOOKS;
    
    // Registrar el menú principal en el grupo "Soporte" (helpdesk)
    $PLUGIN_HOOKS['csrf_compliant']['techtask'] = true;
    $PLUGIN_HOOKS['menu_toadd']['techtask'] = ['helpdesk' => 'GlpiPlugin\Techtask\Menu'];
}

function plugin_techtask_install() {
    global $DB;

    // Crear tabla de registros si no existe
    if (!$DB->tableExists('glpi_plugin_techtask_records')) {
        $query = "CREATE TABLE `glpi_plugin_techtask_records` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `tickets_id` INT(11) NOT NULL,
            `users_id` INT(11) NOT NULL,
            `category_id` INT(11) DEFAULT NULL,
            `duration_minutes` INT(11) NOT NULL,
            `description` TEXT,
            `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `tickets_id` (`tickets_id`),
            KEY `users_id` (`users_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $DB->query($query);
    }
    return true;
}

function plugin_techtask_uninstall() {
    global $DB;
    // Opcional: Borrar tabla al desinstalar
    // $DB->query("DROP TABLE IF EXISTS `glpi_plugin_techtask_records` ");
    return true;
}
