<?php

$current_language = elgg_extract('current_language', $vars);
$translated_language = $current_language;
if (elgg_language_key_exists($current_language, $current_language)) {
	$translated_language = elgg_echo($current_language, [], $current_language);
} elseif (elgg_language_key_exists($current_language)) {
	$translated_language = elgg_echo($current_language);
}

elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor'));
elgg_push_breadcrumb($translated_language, elgg_generate_url('default:translation_editor', [
	'current_language' => $current_language,
]));

$plugins = translation_editor_get_plugins($current_language);
$exportable_plugins = [];
foreach ($plugins as $plugin_id => $plugin_stats) {
	if (empty($plugin_stats['custom'])) {
		continue;
	}
	
	if ($plugin_id == 'core') {
		$exportable_plugins[$plugin_id] = $plugin_id;
	} else {
		$plugin = elgg_get_plugin_from_id($plugin_id);
		if (!($plugin instanceof ElggPlugin)) {
			continue;
		}
		
		$exportable_plugins[$plugin->getDisplayName()] = $plugin_id;
	}
}

if (empty($exportable_plugins)) {
	$body = elgg_echo('translation_editor:export:no_plugins');
} else {
	$body_vars = [
		'current_language' => $current_language,
		'exportable_plugins' => $exportable_plugins,
	];
	$body = elgg_view_form('translation_editor/admin/export', [], $body_vars);
}

echo elgg_view_page(elgg_echo('translation_editor:export'), [
	'content' => $body,
]);
