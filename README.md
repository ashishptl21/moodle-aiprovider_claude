# Claude AI Provider for Moodle

A Moodle AI provider plugin that integrates [Claude AI](https://platform.claude.com/docs/en/home) into Moodle's AI subsystem, enabling text generation, text summarisation, and text explanation

## Features

- Text generation
- Text summarisation
- Text explanation

## Requirements

- Moodle 5.0 or later
- PHP 8.2 or later
- A valid [Claude API key](https://platform.claude.com/settings/keys)

## Configuration

### Provider settings

| Setting         | Description                                                                |
| --------------- | -------------------------------------------------------------------------- |
| API key         | Your Claude API key from [platform.claude.com](https://platform.claude.com) |
| API version     | Pre-filled with the correct default |

### Action settings

Each action (generate text, summarise text, explain text) can be configured independently with:

| Setting            | Description                                                   |
| ------------------ | ------------------------------------------------------------- |
| Model              | The Claude model to use                 |
| Endpoint           | The Claude API endpoint (pre-filled with the correct default) |
| System instruction | Custom system prompt (text actions only)                      |
| Extra parameters   | Additional model parameters        |

## Architecture

### Text actions

Text generation, summarisation, and explanation all use the Claude messages API (`/v1/messages`).


## License

This plugin is licensed under the [GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html).

## Installation

Install by downloading a zip. Log in as an administrator and visit **Site administration → Plugins → Install plugin ** and upload the zip.

### Download the zip

1. Visit the Moodle plugins directory and download the version that matches your Moodle release:
   - <https://moodle.org/plugins/aiprovider_claude>
2. Extract the zip.
3. Copy the extracted `claude` folder into your Moodle `ai/provider/` directory so the path becomes:
   - `moodle/ai/provider/claude`
4. Log in as an administrator and visit **Site administration → Notifications**.
