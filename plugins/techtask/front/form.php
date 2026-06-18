<?php

// front/form.php

include('../../../inc/includes.php');

use GlpiPlugin\Techtask\TechTask;

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
            'INFO'
        );
        // Redirigir al mismo formulario para limpiar
        Html::redirect(Plugin::getWebDir('techtask') . '/front/form.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error al procesar el formulario', 'techtask'),
            false,
            'ERROR'
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
?><?php

// front/form.php

include('../../../inc/includes.php');

use GlpiPlugin\Techtask\TechTask;

// Verificar permisos
if (!TechTask::checkRights()) {
    Html::displayRightError();
    exit();
}

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verificar CSRF
    Session::checkCSRF($_POST);
    
    $ticket_id = TechTask::processForm($_POST);
    
    if ($ticket_id) {
        Session::addMessageAfterRedirect(
            sprintf(__('Tarea registrada correctamente. Ticket #%d creado y resuelto.', 'techtask'), $ticket_id),
            true,
            'INFO'
        );
        // Redirigir al mismo formulario para limpiar
        Html::redirect(Plugin::getWebDir('techtask') . '/front/form.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error al procesar el formulario', 'techtask'),
            false,
            'ERROR'
        );
    }
}

// Cargar categorías para el formulario
$categories = TechTask::getCategories();

// Renderizar usando Twig
$twig = Glpi\Application\View\TemplateRenderer::getInstance();
$twig->display('@techtask/form.html.twig', [
    'categories' => $categories,
    'page_title' => __('TechTask - Registro manual de tiempo', 'techtask')
]);

Html::footer();
?>
