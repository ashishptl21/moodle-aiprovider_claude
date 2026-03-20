<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace aiprovider_claude;

use aiprovider_claude\aimodel\base;
use aiprovider_claude\aimodel\definition;
use core\component;

/**
 * Helper class for the provider.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    /**
     * Get all model classes.
     *
     * @return array Array of model classes.
     */
    public static function get_models(): array {
        $models = [];
        $modelclasses = component::get_component_classes_in_namespace('aiprovider_claude', 'aimodel');
        foreach (array_keys($modelclasses) as $class) {
            if (class_exists($class) && is_subclass_of($class, base::class)) {
                $models = array_merge(
                    $models,
                    $class::get_models(),
                );
            }
        }
        return $models;
    }

    /**
     * Get model class by name.
     *
     * @param string $modelname Model name.
     * @return definition|null
     */
    public static function get_model_class(string $modelname): ?definition {
        foreach (static::get_models() as $model) {
            if ($model->get_model_name() === $modelname) {
                return $model;
            }
        }
        return null;
    }
}
