<?php
/**
 * Get an overview of the translations, eighter a listing off all plugins or an overview of the available keys in a plugin
 */

use Elgg\Exceptions\Http\BadRequestException;

$current_language = elgg_extract('current_language', $vars, elgg_get_current_language(), false);

$translations = elgg()->translator->getInstalledTranslations();
if (!array_key_exists($current_language, $translations)) {
	$exception = new BadRequestException(elgg_echo('translation_editor:language:unsupported'));
	$exception->setRedirectUrl(elgg_generate_url('default:translation_editor', [
		'current_language' => elgg_get_current_language(),
	]));
}

$translated_language = $current_language;
if (elgg_language_key_exists($current_language, $current_language)) {
	$translated_language = elgg_echo($current_language, [], $current_language);
} elseif (elgg_language_key_exists($current_language)) {
	$translated_language = elgg_echo($current_language);
}

set_input('current_language', $current_language);

// build page elements
$body = elgg_view('translation_editor/language_selector', [
	'current_language' => $current_language,
	'languages' => array_keys($translations),
	'site_language' => elgg_get_config('language', 'en'),
]);

// show plugin list
$plugins = translation_editor_get_plugins($current_language);

$form_vars = [
	'id' => 'translation_editor_search_form',
	'action' => 'translation_editor/search',
	'disable_security' => true,
	'class' => 'mbl',
	'method' => 'GET',
];

$body .= elgg_view_form('translation_editor/search', $form_vars, ['current_language' => $current_language]);

$body .= elgg_view('translation_editor/cleanup', ['current_language' => $current_language]);

$body .= elgg_view('translation_editor/plugin_list', [
	'plugins' => $plugins,
	'current_language' => $current_language,
]);

$title_text = elgg_echo('translation_editor:menu:title');

echo elgg_view_page("{$title_text} - {$translated_language}", [
	'content' => $body,
]);
