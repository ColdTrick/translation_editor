<?php
/**
 * Show information about the last import of custom translation files
 *
 * @uses $vars['language'] the language to display information for
 */

$language = elgg_extract('language', $vars);
if (empty($language)) {
	return;
}

$info = translation_editor_get_last_import($language);
if (empty($info)) {
	return;
}

$user = elgg_extract('user', $info);
if ($user instanceof \ElggUser) {
	$user_link = elgg_view_entity_url($user);
} else {
	$user_link = elgg_extract('actor', $info);
}

$content = elgg_format_element('span', ['class' => 'elgg-subtext mrs'], elgg_echo('translation_editor:last_import:actor', [$user_link]));
$content .= elgg_view_friendly_time(elgg_extract('time', $info));

echo elgg_format_element('div', ['class' => ['translation-editor-last-import', 'mbs']], $content);
