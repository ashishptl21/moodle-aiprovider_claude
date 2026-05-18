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

use aiprovider_claude\form\action_form;
use Psr\Http\Message\RequestInterface;

/**
 * Class provider.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider extends \core_ai\provider {

    public readonly array $config;

    public readonly array $actionconfig;

    public function __construct() {
        $this->config = (array) get_config('aiprovider_claude');
        $this->actionconfig = isset($this->config['actionconfig']) ?
            json_decode($this->config['actionconfig'], true) :
            self::initialise_action_settings();
    }

    /**
     * Get the list of actions that this provider supports.
     *
     * @return array An array of action class names.
     */
    public function get_action_list(): array {
        return self::action_list();
    }

    /**
     * Get the list of actions that this provider supports.
     *
     * @return array An array of action class names.
     */
    public static function action_list(): array {
        return [
            \core_ai\aiactions\generate_text::class,
            \core_ai\aiactions\summarise_text::class,
        ];
    }

    /**
     * Get the list of valid actions.
     *
     * @return array An array of actions.
     */
    public static function get_valid_actions(): array {
        return array_map(function ($class) {
            return basename(str_replace('\\', '/', $class));
        }, self::action_list());
    }

    /**
     * Update a request to add any headers required by the provider.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function add_authentication_headers(RequestInterface $request): RequestInterface {
        return $request->withAddedHeader('X-Api-Key', $this->config['apikey']);
    }

    /**
     * Get the default settings for an action.
     *
     * @param string $action The action class name.
     * @return array The default settings for the action.
     */
    public static function get_action_setting_defaults(string $action): array {
        if ($mform = self::get_action_setting_form($action)) {
            return $mform->get_defaults();
        }

        return [];
    }

    /**
     * Get any action settings for this provider.
     *
     * @param string $action The action class name.
     * @param array $customdata The customdata for the form.
     * @return form/action_settings_form|null The settings form for this action or false in no settings.
     */
    public static function get_action_setting_form(string $action, array $customdata = []): ?action_form {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $customdata += [
            'actionname' => $actionname,
            'action' => $action,
            'providername' => 'aiprovider_claude',
        ];
        if (in_array($actionname, self::get_valid_actions())) {
            $mform = new form\action_generate_text_form(customdata: $customdata);
            $mform->is_validated();
            return $mform;
        }
        return null;
    }

    /**
     * Check this provider has the minimal configuration to work.
     *
     * @return bool Return true if configured.
     */
    public function is_provider_configured(): bool {
        return !empty($this->config['apikey']) && !empty($this->config['apiversion']);
    }

    /**
     * Generate a user id.
     *
     * This is a hash of the site id and user id,
     * this means we can determine who made the request
     * but don't pass any personal data to the AI provider.
     *
     * @param string $userid The user id.
     * @return string The generated user id.
     */
    public function generate_userid(string $userid): string {
        global $CFG;
        return hash('sha256', $CFG->siteidentifier . $userid);
    }

    /**
     * Check if the request is allowed by the rate limiter.
     *
     * @param \core_ai\aiactions\base $action The action to check.
     * @return array|bool True on success, array of error details on failure.
     */
    public function is_request_allowed(\core_ai\aiactions\base $action): array|bool {
        $ratelimiter = \core\di::get(\core_ai\rate_limiter::class);
        $component = \core\component::get_component_from_classname(get_class($this));

        // Check the user rate limit.
        if ($this->config['enableuserratelimit']) {
            if (!$ratelimiter->check_user_rate_limit(
                component: $component,
                ratelimit: $this->config['userratelimit'],
                userid: $action->get_configuration('userid')
            )) {
                return [
                    'success' => false,
                    'errorcode' => 429,
                    'errormessage' => 'User rate limit exceeded',
                ];
            }
        }

        // Check the global rate limit.
        if ($this->config['enableglobalratelimit']) {
            if (!$ratelimiter->check_global_rate_limit(
                component: $component,
                ratelimit: $this->config['globalratelimit']
            )) {
                return [
                    'success' => false,
                    'errorcode' => 429,
                    'errormessage' => 'Global rate limit exceeded',
                ];
            }
        }

        return true;
    }

    /**
     * Initialise the action settings array.
     *
     * @return array The initialised action settings.
     */
    public static function initialise_action_settings(): array {
        $actions = self::action_list();
        $actionconfig = [];
        foreach ($actions as $action) {
            $actionconfig[$action] = [
                'enabled' => true,
                'settings' => static::get_action_setting_defaults($action),
            ];
        }
        return $actionconfig;
    }
}
