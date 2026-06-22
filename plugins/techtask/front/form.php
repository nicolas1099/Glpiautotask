<?php

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
    // TEMPORAL: Deshabilitar checkCSRF para depuración
    // Session::checkCSRF($_POST);
    
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

Html::header(__('Autotask', 'techtask'), $_SERVER['PHP_SELF'], 'helpdesk', 'GlpiPlugin\Techtask\Menu');

echo '<div class="row m-4">';
echo '  <div class="col-12 col-md-8 offset-md-2">';
echo '    <div class="card card-primary">';
echo '      <div class="card-header"><h3 class="card-title">' . __('Registrar trabajo realizado', 'techtask') . '</h3></div>';
echo '      <form method="post" action="' . Plugin::getWebDir('techtask') . '/front/form.php">';
echo Html::hidden('_glpi_csrf_token', ['value' => Session::getNewCSRFToken()]);
echo '        <div class="card-body">';

// Campo Título
echo '          <div class="mb-3">';
echo '            <label for="name" class="form-label">' . __('Título', 'techtask') . '</label>';
echo '            <input type="text" name="name" id="name" class="form-control" required placeholder="' . __('Resumen breve del trabajo', 'techtask') . '">';
echo '          </div>';

// Campo Categoría
echo '          <div class="mb-3">';
echo '            <label for="category_id" class="form-label">' . __('Categoría', 'techtask') . '</label>';
echo '            <select name="category_id" id="category_id" class="form-select">';
echo '              <option value="">-- ' . __('Selecciona una categoría', 'techtask') . ' --</option>';
foreach ($categories as $category) {
    echo '              <option value="' . $category['id'] . '">' . $category['name'] . '</option>';
}
echo '            </select>';
echo '          </div>';

// Campo Tiempo
echo '          <div class="mb-3">';
echo '            <label for="duration" class="form-label">' . __('Tiempo (minutos)', 'techtask') . '</label>';
echo '            <input type="number" name="duration" id="duration" class="form-control" min="1" required placeholder="Ej: 30">';
echo '          </div>';

// Campo Descripción
echo '          <div class="mb-3">';
echo '            <label for="content" class="form-label">' . __('Descripción', 'techtask') . '</label>';
echo '            <textarea name="content" id="content" class="form-control" rows="5" required placeholder="' . __('Explicación detallada...', 'techtask') . '"></textarea>';
echo '          </div>';

echo '        </div>';
echo '        <div class="card-footer d-flex justify-content-between">';
echo '          <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> ' . __('Crear y Cerrar Ticket', 'techtask') . '</button>';
echo '          <button type="reset" class="btn btn-outline-secondary">' . __('Limpiar', 'techtask') . '</button>';
echo '        </div>';
echo '      </form>';
echo '    </div>';
echo '  </div>';
echo '</div>';

Html::footer();

