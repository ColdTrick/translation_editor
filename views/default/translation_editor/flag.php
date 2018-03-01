<?php
/**
 * Output a country flag
 *
 * @uses $vars['language'] hte language to find the flag for
 */

$language = elgg_extract('language', $vars);

if (empty($language)) {
	return '&nbsp;';
}

$view = "translation_editor/flags/{$language}.gif";
if (!elgg_view_exists($view)) {
	return '&nbsp;';
}

$title_alt = $language;
if (elgg_language_key_exists($language, $language)) {
	$title_alt = elgg_echo($language, [], $language);
} elseif (elgg_language_key_exists($language)) {
	$title_alt = elgg_echo($language);
}

echo elgg_view('output/img', [
	'src' => elgg_get_simplecache_url($view),
	'alt' => $title_alt,
	'title' => $title_alt,
	'class' => elgg_extract_class($vars, ['translation-editor-flag']),
]);
