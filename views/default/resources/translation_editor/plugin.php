<?php
/**
 * Edit the translations of a plugin
 *
 * @uses $vars['current_language'] the language to edit
 * @uses $vars['plugin_id'] the plugin id for the plugin to edit
 */

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityNotFoundException;

// Get inputs
$current_language = elgg_extract('current_language', $vars);
$plugin = elgg_extract('plugin_id', $vars);

switch ($plugin) {
	case 'core':
		break;
	default:
		$plugin_entity = elgg_get_plugin_from_id($plugin);
		if (empty($plugin_entity)) {
			throw new EntityNotFoundException();
		}
			
		if (!$plugin_entity->isActive()) {
			throw new EntityNotFoundException(elgg_echo('translation_editor:exception:plugin_disabled'));
		}
		break;
}

$translations = elgg()->translator->getInstalledTranslations();
if (!array_key_exists($current_language, $translations)) {
	$exception = new BadRequestException(elgg_echo('translation_editor:language:unsupported'));
	$exception->setRedirectUrl(elgg_generate_url('default:translation_editor', [
		'current_language' => elgg_get_current_language(),
	]));
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor', [
	'current_language' => elgg_get_current_language(),
]));

// add current language to breadcrumb
$translated_language = $current_language;
if (elgg_language_key_exists($current_language, $current_language)) {
	$translated_language = elgg_echo($current_language, [], $current_language);
} elseif (elgg_language_key_exists($current_language)) {
	$translated_language = elgg_echo($current_language);
}

elgg_push_breadcrumb($translated_language, elgg_generate_url('default:translation_editor', [
	'current_language' => $current_language,
]));
set_input('current_language', $current_language);

// show plugin keys
elgg_push_breadcrumb($plugin, elgg_generate_url('default:translation_editor:plugin', [
	'current_language' => $current_language,
	'plugin_id' => $plugin,
]));
set_input('plugin_id', $plugin);

// build page elements
$title_text = elgg_echo('translation_editor:menu:title:plugin', [$plugin, $translated_language]);

// language selector
$body = elgg_view('translation_editor/language_selector', [
	'current_language' => $current_language,
	'plugin' => $plugin,
	'languages' => array_keys($translations),
	'site_language' => elgg_get_config('language', 'en')
]);

$body .= elgg_view('translation_editor/plugin_edit', [
	'plugin' => $plugin,
	'current_language' => $current_language,
]);

// draw page
echo elgg_view_page($title_text, [
	'content' => $body,
]);
