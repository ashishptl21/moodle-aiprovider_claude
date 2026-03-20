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
 * English language pack for Claude
 *
 * @package    aiprovider_claude
 * @category   string
 * @copyright  2026 Treesha Infotech <dev@treeshainfotech.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['action:explain_text:endpoint'] = 'API endpoint';
$string['action:explain_text:model'] = 'AI model';
$string['action:explain_text:model_help'] = 'The model used to explain the provided text.';
$string['action:explain_text:systeminstruction'] = 'System instruction';
$string['action:explain_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['action:generate_text:endpoint'] = 'API endpoint';
$string['action:generate_text:model'] = 'AI model';
$string['action:generate_text:model_help'] = 'The model used to generate the text response.';
$string['action:generate_text:systeminstruction'] = 'System instruction';
$string['action:generate_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['action:summarise_text:endpoint'] = 'API endpoint';
$string['action:summarise_text:model'] = 'AI model';
$string['action:summarise_text:model_help'] = 'The model used to summarise the provided text.';
$string['action:summarise_text:systeminstruction'] = 'System instruction';
$string['action:summarise_text:systeminstruction_help'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['apikey'] = 'API key';
$string['apikey_help'] = 'Get a key from your <a href="https://platform.claude.com/settings/keys" target="_blank">API keys</a>.';
$string['apiversion'] = 'Api version';
$string['apiversion_help'] = 'Api version to used in api call in headers';
$string['custom_model_name'] = 'Custom model name';
$string['extraparams'] = 'Extra parameters';
$string['extraparams_help'] = 'Extra parameters can be configured here. We support JSON format. For example:
<pre>
{
    "temperature": 0.5,
    "max_completion_tokens": 100
}
</pre>';
$string['invalidjson'] = 'Invalid JSON string';
$string['model_claude-haiku-4-5-20251001'] = 'claude-haiku-4-5-20251001';
$string['model_claude-opus-4-1-20250805'] = 'claude-opus-4-1-20250805';
$string['model_claude-opus-4-20250514'] = 'claude-opus-4-20250514';
$string['model_claude-opus-4-5-20251101'] = 'claude-opus-4-5-20251101';
$string['model_claude-opus-4-6'] = 'claude-opus-4-6';
$string['model_claude-sonnet-4-20250514'] = 'claude-sonnet-4-20250514';
$string['model_claude-sonnet-4-5-20250929'] = 'claude-sonnet-4-5-20250929';
$string['model_claude-sonnet-4-6'] = 'claude-sonnet-4-6';
$string['pluginname'] = 'Claude';
$string['privacy:metadata'] = 'The Claude API provider plugin does not store any personal data.';
$string['privacy:metadata:aiprovider_claude:externalpurpose'] = 'This information is sent to the Claude API in order for a response to be generated. Your Claude account settings may change how Claude stores and retains this data. No user data is explicitly sent to Claude or stored in Moodle LMS by this plugin.';
$string['privacy:metadata:aiprovider_claude:model'] = 'The model used to generate the response.';
$string['privacy:metadata:aiprovider_claude:numberimages'] = 'When generating images the number of images used in the response.';
$string['privacy:metadata:aiprovider_claude:prompttext'] = 'The user entered text prompt used to generate the response.';
$string['privacy:metadata:aiprovider_claude:responseformat'] = 'The format of the response. When generating images.';
$string['settings'] = 'Settings';
$string['settings_help'] = 'Adjust the settings below to customise how requests are sent to Claude.';
$string['settings_max_tokens'] = 'Max Tokens';
$string['settings_max_tokens_help'] = 'The maximum number of tokens to generate in the response. Min: {$a->min}, Max: {$a->max}, Default: {$a->default}.';
$string['settings_presence_penalty'] = 'presence_penalty';
$string['settings_presence_penalty_help'] = 'The presence penalty encourages the model to use new words by increasing the likelihood of choosing words it hasn\'t used before. A higher value makes the generated text more diverse, while a lower value allows more repetition.';
$string['settings_stop_sequences'] = 'Stop Sequence';
$string['settings_stop_sequences_help'] = 'Specify a character sequence to indicate where the model should stop';
$string['settings_temperature'] = 'Temperature';
$string['settings_temperature_help'] = 'Use a lower value to decrease randomness in responses. Min: {$a->min}, Max: {$a->max}, Default: {$a->default}.';
