<?php

/**
 * -------------------------------------------------------------------------
 * techtask plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2026 by the techtask plugin team.
 * @license   MIT https://opensource.org/licenses/mit-license.php
 * @link      https://github.com/pluginsGLPI/techtask
 * -------------------------------------------------------------------------
 */

/**
 * Plugin install process
 */
function plugin_techtask_install(): bool
{
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

/**
 * Plugin uninstall process
 */
function plugin_techtask_uninstall(): bool
{
    return true;
}
