<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
die("CONEXION ESTABLECIDA CON FORM.PHP");

// front/form.php

include('../../../inc/includes.php');

use GlpiPlugin\Techtask\TechTaskManager;
use Glpi\Application\View\TemplateRenderer;

// Verificar permisos
if (!TechTaskManager::checkRights()) {
    Html::displayRightError();
    exit();
}

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar CSRF
    Session::checkCSRF($_POST);
    
    $ticket_id = TechTaskManager::processForm($_POST);
    
    if ($ticket_id) {
        Session::addMessageAfterRedirect(
            sprintf(__('Tarea registrada correctamente. Ticket #%d creado y resuelto.', 'techtask'), $ticket_id),
            true,
            1 // INFO
        );
        Html::redirect(Plugin::getWebDir('techtask') . '/front/form.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error al procesar el formulario', 'techtask'),
            false,
            3 // ERROR
        );
    }
}

// Cargar categorías
$categories = TechTaskManager::getCategories() ?? [];

// Renderizar usando Twig - GLPI 10
TemplateRenderer::getInstance()->display('@techtask/form.html.twig', [
    'categories' => $categories,
    'page_title' => __('Autotask - Registro de tiempo', 'techtask')
]);

