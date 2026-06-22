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

// End of plugin_init_techtask
