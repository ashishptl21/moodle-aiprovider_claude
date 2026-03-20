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
 * Anthropic model catalog.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class anthropic extends base {
    /**
     * Get Anthropic models.
     *
     * @return array<string, \aiprovider_claude\definition>
     */
    public static function get_models(): array {
        return [
            'claude-opus-4-6' => self::create_model(
                'claude-opus-4-6',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(128000),
            ),
            'claude-opus-4-5-20251101' => self::create_model(
                'claude-opus-4-5-20251101',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(64000),
            ),
            'claude-opus-4-1-20250805' => self::create_model(
                'claude-opus-4-1-20250805',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(32000),
            ),
            'claude-opus-4-20250514' => self::create_model(
                'claude-opus-4-20250514',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(32000),
            ),
            'claude-sonnet-4-6' => self::create_model(
                'claude-sonnet-4-6',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(64000),
            ),
            'claude-sonnet-4-5-20250929' => self::create_model(
                'claude-sonnet-4-5-20250929',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(64000),
            ),
            'claude-sonnet-4-20250514' => self::create_model(
                'claude-sonnet-4-20250514',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(64000),
            ),
            'claude-haiku-4-5-20251001' => self::create_model(
                'claude-haiku-4-5-20251001',
                definition::MODEL_TYPE_TEXT,
                self::get_settings(64000),
            ),
        ];
    }

    /**
     * Anthropic settings.
     *
     * @param int $maxtokensmax Max allowed max_tokens value.
     * @return array
     */
    private static function get_settings(int $maxtokensmax = 4096): array {
        return [
            // Temperature – Use a lower value to decrease randomness in responses.
            'temperature' => self::setting(
                'settings_temperature',
                PARAM_FLOAT,
                'settings_temperature',
                ['min' => 0, 'max' => 1, 'default' => 1],
                true
            ),
            // Max token – The maximum number of tokens to generate in the response. Maximum token limits are strictly enforced.
            'max_tokens' => self::setting(
                'settings_max_tokens',
                PARAM_INT,
                'settings_max_tokens',
                ['min' => 1, 'max' => $maxtokensmax, 'default' => 4096],
                true,
            ),
            // Stop Sequences – Specify a character sequence to indicate where the model should stop.
            'stop_sequences' => self::setting(
                'settings_stop_sequences',
                PARAM_TEXT,
                'settings_stop_sequences',
                [],
                false
            ),
        ];
    }
}
