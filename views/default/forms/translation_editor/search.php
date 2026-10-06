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
			'#type' => 'fieldset',
			'#class' => 'elgg-field-stretch',
			'fields' => [
				[
					'#type' => 'text',
					'name' => 'q',
					'value' => elgg_extract('query', $vars),
					'placeholder' => elgg_echo('translation_editor:forms:search:default'),
				],
				[
					'#type' => 'switch',
					'#label' => elgg_echo('translation_editor:forms:search:keys'),
					'name' => 'search_keys',
					'value' => (bool) elgg_extract('search_keys', $vars, true),
				],
			],
		],
		[
			'#type' => 'submit',
			'text' => elgg_echo('search'),
		],
	],
	'align' => 'horizontal',
]);
