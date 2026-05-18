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

use aiprovider_claude\provider as Aiprovider_claudeProvider;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Test Claude provider methods.
 *
 * @package    aiprovider_claude
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[CoversClass(provider::class)]
final class provider_test extends \advanced_testcase {
    /** @var \core_ai\manager */
    private $manager;

    /** @var \core_ai\provider */
    private $provider;

    /** @var array ai component strings */
    protected array $aicomponentstrings;

    /**
     * Overriding setUp() function to always reset after tests.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();

        // Create the provider instance.
        $this->manager = \core\di::get(\core_ai\manager::class);
        $this->provider = $this->create_provider();
        $this->aicomponentstrings = get_string_manager()->load_component_strings('core_ai', 'lang');
    }

    /**
     * Test get_action_list
     */
    public function test_get_action_list(): void {
        $actionlist = $this->provider->get_action_list();
        $this->assertIsArray($actionlist);
        $this->assertCount(2, $actionlist);
        $this->assertContains(\core_ai\aiactions\generate_text::class, $actionlist);
        $this->assertContains(\core_ai\aiactions\summarise_text::class, $actionlist);
    }

    /**
     * Test generate_userid.
     */
    public function test_generate_userid(): void {
        $userid = $this->provider->generate_userid(1);

        // Assert that the generated userid is a string of proper length.
        $this->assertIsString($userid);
        $this->assertEquals(64, strlen($userid));
    }

    /**
     * Test is_request_allowed.
     */
    public function test_is_request_allowed(): void {
        // Create the provider instance.
        $config = [
            'enableuserratelimit' => true,
            'userratelimit' => 3,
            'enableglobalratelimit' => true,
            'globalratelimit' => 5,
        ];
        $provider = $this->create_provider(overrideconfig: $config);

        $contextid = 1;
        $userid = 1;
        $prompttext = 'Describe moodle in brief';
        $action = new \core_ai\aiactions\generate_text(
            contextid: $contextid,
            userid: $userid,
            prompttext: $prompttext,
        );

        // Make 3 requests, all should be allowed.
        for ($i = 0; $i < 3; $i++) {
            $this->assertTrue($provider->is_request_allowed($action));
        }

        // The 4th request for the same user should be denied.
        $result = $provider->is_request_allowed($action);
        $this->assertFalse($result['success']);
        $this->assertEquals(
            $this->aicomponentstrings['error:429:internaluser'] ?? 'User rate limit exceeded',
            $result['errormessage']
        );

        // Change user id to make a request for a different user, should pass (4 requests for global rate).
        $action = new \core_ai\aiactions\generate_text(
            contextid: $contextid,
            userid: 2,
            prompttext: $prompttext,
        );
        $this->assertTrue($provider->is_request_allowed($action));

        // Make a 5th request for the global rate limit, it should be allowed.
        $this->assertTrue($provider->is_request_allowed($action));

        // The 6th request should be denied.
        $result = $provider->is_request_allowed($action);
        $this->assertFalse($result['success']);
        $this->assertEquals(
            $this->aicomponentstrings['error:429:internalsitewide'] ?? 'Global rate limit exceeded',
            $result['errormessage']
        );
    }

    /**
     * Test is_provider_configured.
     */
    public function test_is_provider_configured(): void {
        global $CFG;

        // No configured values.
        $this->assertTrue($this->provider->is_provider_configured());

        $updatedprovider = $this->create_provider(overrideconfig: [
            'apikey' => '',
            'apiversion' => '2023-06-01',
        ]);

        $this->assertFalse($updatedprovider->is_provider_configured());
    }

    /**
     * Create the provider object.
     *
     * @param string $actionclass The action class to use.
     * @param array $actionconfig The action configuration to use.
     * @param array $overrideconfig The config array to override defaults.
     */
    public function create_provider(
        string $actionclass = \core_ai\aiactions\generate_text::class,
        array $actionconfig = [],
        array $overrideconfig = [],
    ): \core_ai\provider {
        global $CFG;
        $manager = \core\di::get(\core_ai\manager::class);
        $config = [
            'apikey' => 'dummy123xyz',
            'apiversion' => '2023-06-01',
            'enableuserratelimit' => true,
            'userratelimit' => 1,
            'enableglobalratelimit' => true,
            'globalratelimit' => 1,
        ];
        $defaultactionconfig = [
            $actionclass => [
                'settings' => [
                    'model' => 'claude-sonnet-4-5-20250929',
                    'endpoint' => 'https://api.anthropic.com/v1/messages',
                ],
            ],
        ];
        foreach ($actionconfig as $key => $value) {
            $defaultactionconfig[$actionclass]['settings'][$key] = $value;
        }

        $config['actionconfig'] = json_encode($defaultactionconfig);
        $config = array_merge($config, $overrideconfig);

        $CFG->forced_plugin_settings['aiprovider_claude'] = $config;

        return new Aiprovider_claudeProvider();
    }
}
