# Orbit Webform

Orbit Webform is a custom Drupal 11 module that provides a curated Webform setup for Orbit projects.

The module installs an example Webform, limits which Webform element types site builders can add, and applies default submission purge settings to newly created Webforms.

## Requirements

- Drupal 11
- PHP 8.4 or later
- Webform module
- CAPTCHA module

## Provided Webform

When the module is installed, it creates a Webform with the machine name `form_elements`.

This Webform demonstrates each element type allowed by the module, including basic input fields, option fields, file upload, markup, CAPTCHA, and submit actions.

## Allowed Webform Elements

Orbit Webform restricts the Webform element library to a selected list of approved element types:

- `textfield`
- `textarea`
- `email`
- `tel`
- `number`
- `select`
- `checkbox`
- `checkboxes`
- `radios`
- `date`
- `captcha`
- `webform_actions`
- `webform_markup`
- `managed_file`

The restriction is applied when the module is installed by updating Webform's `element.excluded_elements` setting.

## Default Webform Settings

For newly created Webforms, the module sets default purge behavior:

- Purge authenticated and anonymous submissions.
- Purge submissions older than 30 days.

## Composer

The module includes its own `composer.json` for future packaging and dependency management.

## License

This module is licensed under the MIT License.
