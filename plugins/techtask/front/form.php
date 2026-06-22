<?php

// front/form.php

include('../../../inc/includes.php');

use GlpiPlugin\Techtask\TechTaskManager;

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
        // Redirigir al mismo formulario para limpiar
        Html::redirect(Plugin::getWebDir('techtask') . '/front/form.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error al procesar el formulario', 'techtask'),
            false,
            3 // ERROR
        );
    }
}

// Cargar categorías para el formulario
$categories = TechTaskManager::getCategories();

// Renderizar usando Twig
$twig = Glpi\Application\View\TemplateRenderer::getInstance();
$twig->display('@techtask/form.html.twig', [
    'categories' => $categories,
    'page_title' => __('TechTask - Registro manual de tiempo', 'techtask')
]);

Html::footer();

