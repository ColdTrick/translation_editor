<?php
/**
 * Show the search form
 */

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'language',
	'value' => elgg_extract('current_language', $vars),
]);

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'text',
			'name' => 'q',
			'value' => elgg_extract('query', $vars),
			'placeholder' => elgg_echo('translation_editor:forms:search:default'),
		],
		[
			'#type' => 'submit',
			'value' => elgg_echo('search'),
		],
	],
	'align' => 'horizontal',
]);
