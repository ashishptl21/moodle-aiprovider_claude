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
 * Configure provider instance action settings.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__, 4) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');

$provider = 'aiprovider_claude';
$action = required_param('action', PARAM_TEXT);
$basename = basename(str_replace('\\', '/', $action));

admin_externalpage_setup($provider . '_' . $basename);

$returnurl = optional_param('returnurl', null, PARAM_LOCALURL);
$customdata = [];

// Handle return URL.
if (empty($returnurl)) {
    $returnurl = admin_get_root()->locate($provider)->get_settings_page_url();
} else {
    $returnurl = new moodle_url($returnurl);
}
$customdata['returnurl'] = $returnurl;

$manager = \core\di::get(\core_ai\manager::class);
$providerclass = "\\$provider\\provider";
/** @var \aiprovider_claude\provider $providerclass */
$providerclass = new $providerclass();

$actionconfig = is_array($providerclass->actionconfig) ? $providerclass->actionconfig :
    json_decode($providerclass->actionconfig, true, 512, JSON_THROW_ON_ERROR);
$thisactionconfig = $actionconfig[$action] ?? [];

$customdata['actionconfig'] = $thisactionconfig;
$customdata['providername'] = $provider;

$urlparams = [
    'provider' => $provider,
    'action' => $action,
];

$mform = $providerclass->get_action_setting_form($action, $customdata);
if (!$mform) {
    throw new coding_exception('Invaid action recieved {$a}', $action);
}

if ($mform->is_cancelled()) {
    $data = $mform->get_data();
    if (isset($data->returnurl)) {
        redirect($data->returnurl);
    } else {
        redirect($returnurl);
    }
}

if ($data = $mform->get_data()) {
    $component = $data->provider;
    unset($data->provider, $data->id, $data->action, $data->returnurl, $data->submitbutton);
    $actionconfig[$action]['settings'] = (array)$data;

    $oldvalue = get_config($component, 'actionconfig');
    $newvalue = json_encode($actionconfig, JSON_PRETTY_PRINT);
    set_config(
        'actionconfig',
        $newvalue,
        $component
    );
    add_to_config_log(
        'actionconfig',
        $oldvalue,
        $newvalue,
        $component
    );

    \core\notification::add(
        get_string('providerinstanceactionupdated', $provider, $action::get_name()),
        \core\notification::SUCCESS
    );

    redirect($returnurl);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
