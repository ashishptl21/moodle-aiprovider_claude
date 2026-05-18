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

/**
 * Plugin administration pages are defined here.
 *
 * @package     aiprovider_claude
 * @copyright   2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\url;

defined('MOODLE_INTERNAL') || die();

/** @var aiprovider_claude\provider $provider */
/** @var string $section */
/** @var bool $hassiteconfig */
/** @var core\plugininfo\aiprovider $plugininfo */
if ($hassiteconfig && isset($provider)) {

    // Setting API key.
    $settings->add(new admin_setting_configpasswordunmask(
        'aiprovider_claude/apikey',
        new lang_string('apikey', 'aiprovider_claude'),
        new lang_string('apikey_help', 'aiprovider_claude'),
        ''
    ));

    // Setting organization ID.
    $settings->add(new admin_setting_configtext(
        'aiprovider_claude/apiversion',
        new lang_string('apiversion', 'aiprovider_claude'),
        new lang_string('apiversion_help', 'aiprovider_claude'),
        '2023-06-01',
        PARAM_TEXT,
        25
    ));

    // Setting to enable/disable global rate limiting.
    $settings->add(new admin_setting_configcheckbox(
        'aiprovider_claude/enableglobalratelimit',
        new lang_string('enableglobalratelimit', 'aiprovider_claude'),
        new lang_string('enableglobalratelimit_desc', 'aiprovider_claude'),
        0,
    ));

    // Setting to set how many requests per hour are allowed for the global rate limit.
    // Should only be enabled when global rate limiting is enabled.
    $settings->add(new admin_setting_configtext(
        'aiprovider_claude/globalratelimit',
        new lang_string('globalratelimit', 'aiprovider_claude'),
        new lang_string('globalratelimit_desc', 'aiprovider_claude'),
        100,
        PARAM_INT,
    ));
    $settings->hide_if('aiprovider_claude/globalratelimit', 'aiprovider_claude/enableglobalratelimit', 'eq', 0);

    // Setting to enable/disable user rate limiting.
    $settings->add(new admin_setting_configcheckbox(
        'aiprovider_claude/enableuserratelimit',
        new lang_string('enableuserratelimit', 'aiprovider_claude'),
        new lang_string('enableuserratelimit_desc', 'aiprovider_claude'),
        0,
    ));

    // Setting to set how many requests per hour are allowed for the user rate limit.
    // Should only be enabled when user rate limiting is enabled.
    $settings->add(new admin_setting_configtext(
        'aiprovider_claude/userratelimit',
        new lang_string('userratelimit', 'aiprovider_claude'),
        new lang_string('userratelimit_desc', 'aiprovider_claude'),
        10,
        PARAM_INT,
    ));
    $settings->hide_if('aiprovider_openai/userratelimit', 'aiprovider_openai/enableuserratelimit', 'eq', 0);

    // Show the save changes button between the specific settings and the actions table.
    $settings->add(new \admin_setting_savebutton("{$section}/savebutton"));

    foreach ($provider::action_list() as $actionclass) {
        $actionsettingclassname = $plugininfo->component . '_' . $actionclass::get_basename();
        $actionsetting = new admin_externalpage(
            name: $actionsettingclassname,
            visiblename: new lang_string('actionsettingprovider', 'core_ai', $actionclass::get_name()),
            url: new url('/ai/provider/claude/configure_actions.php', ['action' => $actionclass]),
            hidden: true
        );

        $ADMIN->add($parentnodename, $actionsetting);
    }

    // Provider action settings heading.
    $settings->add(new \admin_setting_heading("{$section}/generals",
        new \lang_string('provideractionsettings', 'core_ai'),
        new \lang_string('provideractionsettings_desc', 'core_ai', $provider->get_name())));
    // Load the setting table of actions that this provider supports.
    $settings->add(new \core_ai\admin\admin_setting_action_manager(
        $section,
        \core_ai\table\aiprovider_action_management_table::class,
        'manageaiproviders',
        new \lang_string('manageaiproviders', 'core_ai'),
    ));

}
