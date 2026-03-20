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

namespace aiprovider_claude\aimodel;

/**
 * Shared helpers for model catalogs.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base {
    /**
     * Build one setting definition.
     *
     * @param string $labelidentifier Label lang string identifier.
     * @param mixed $type PARAM_* type.
     * @param string|null $helpidentifier Help lang string identifier.
     * @param array $helpa Help placeholders.
     * @param bool $required Whether field is required.
     * @return array
     */
    protected static function setting(
        string $labelidentifier,
        mixed $type,
        ?string $helpidentifier = null,
        array $helpa = [],
        bool $required = false,
    ): array {
        $setting = [
            'elementtype' => 'text',
            'label' => [
                'identifier' => $labelidentifier,
                'component' => 'aiprovider_claude',
            ],
            'type' => $type,
        ];

        if ($helpidentifier !== null) {
            $setting['help'] = [
                'identifier' => $helpidentifier,
                'component' => 'aiprovider_claude',
            ];
            if (!empty($helpa)) {
                $setting['help']['a'] = $helpa;
            }
        }

        if ($required) {
            $setting['required'] = true;
        }

        return $setting;
    }

    /**
     * Build a model instance.
     *
     * @param string $name Model id.
     * @param int $type Model type.
     * @param array $settings Model settings.
     * @return definition
     */
    protected static function create_model(string $name, int $type, array $settings): definition {
        return new definition($name, $type, $settings);
    }
}
