<?php

// src/TechTask.php

namespace GlpiPlugin\Techtask;

use Ticket;
use TicketTask;
use Session;
use Html;

class TechTaskManager
{
    /**
     * Procesar el formulario y crear ticket + tarea
     */
    public static function processForm($post_data)
    {
        global $DB;
        
        // Validar datos requeridos
        if (empty($post_data['duration']) || empty($post_data['content']) || empty($post_data['name'])) {
            Session::addMessageAfterRedirect(__('Faltan datos obligatorios', 'techtask'), false, 3);
            return false;
        }
        
        // 1. Crear el ticket resuelto
        $ticket = new Ticket();
        
        $ticket_data = [
            'name'                => $post_data['name'],
            'content'             => $post_data['content'],
            'status'              => Ticket::SOLVED,
            'entities_id'         => Session::getActiveEntity(),
            'type'                => Ticket::INCIDENT_TYPE,
            'date'                => date('Y-m-d H:i:s'),
            'closedate'           => date('Y-m-d H:i:s'),
            'solution'            => __('Trabajo completado', 'techtask'),
            'solutiontypes_id'    => 1,
            // Asignar al técnico logueado
            '_users_id_assign'    => [Session::getLoginUserID()],
            // El técnico también es el solicitante en este caso (o podríamos dejarlo vacío)
            '_users_id_requester' => [Session::getLoginUserID()]
        ];
        
        // Añadir categoría si se seleccionó
        if (!empty($post_data['category_id'])) {
            $ticket_data['itilcategories_id'] = (int)$post_data['category_id'];
        }
        
        $ticket_id = $ticket->add($ticket_data);
        
        if (!$ticket_id) {
            Session::addMessageAfterRedirect(__('Error al crear el ticket', 'techtask'), false, 3);
            return false;
        }
        
        // 2. Añadir tarea con la duración (en segundos)
        $task = new TicketTask();
        $task_data = [
            'tickets_id'      => $ticket_id,
            'content'         => $post_data['name'], // Título de la tarea
            'users_id'        => Session::getLoginUserID(),
            'actiontime'      => (int)$post_data['duration'] * 60, // minutos a segundos
            'state'           => 2, // DONE (Completado)
            'date'            => date('Y-m-d H:i:s')
        ];

        // Aplicar la misma categoría a la tarea si existe el campo (depende de la versión/config de GLPI)
        // En GLPI estándar las tareas no suelen tener categoría propia separada del ticket de la misma forma,
        // pero algunos plugins o configuraciones lo permiten. Usaremos el campo si es posible o simplemente el del ticket.
        
        $task_id = $task->add($task_data);
        
        // 3. Guardar en nuestra tabla personalizada (opcional)
        // Verificamos si la tabla existe antes de insertar (buena práctica si no estamos seguros del estado de la DB)
        $DB->query(sprintf(
            "INSERT INTO `glpi_plugin_techtask_records` 
            (tickets_id, users_id, category_id, duration_minutes, description) 
            VALUES (%d, %d, %d, %d, '%s')",
            $ticket_id,
            Session::getLoginUserID(),
            (int)($post_data['category_id'] ?? 0),
            (int)$post_data['duration'],
            $DB->escape($post_data['content'])
        ));
        
        return $ticket_id;
    }
    
    /**
     * Obtener categorías para el desplegable
     */
    public static function getCategories()
    {
        global $DB;
        
        $categories = [];
        $query = "SELECT id, name FROM glpi_itilcategories ORDER BY name";
        $result = $DB->query($query);
        
        while ($row = $DB->fetchAssoc($result)) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    /**
     * Verificar permisos del usuario
     */
    public static function checkRights()
    {
        // CREATE es una constante global en GLPI para permisos de creación
        return Session::haveRight('ticket', CREATE);
    }
}
