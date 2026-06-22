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
    
    // Registrar el menú principal (GLPI 10 estilo)
    $PLUGIN_HOOKS['csrf_compliant']['techtask'] = true;
    $PLUGIN_HOOKS['menu_toadd']['techtask'] = ['tools' => 'GlpiPlugin\Techtask\Menu'];
    
    // También puedes añadir un enlace en el menú "Tools" o "Admin"
    $PLUGIN_HOOKS['post_install']['techtask'] = 'plugin_techtask_post_install';
    $PLUGIN_HOOKS['post_uninstall']['techtask'] = 'plugin_techtask_post_uninstall';
}

function plugin_techtask_post_install() {
    // Acciones después de instalar
    return true;
}

function plugin_techtask_post_uninstall() {
    // Acciones después de desinstalar
    return true;
}
