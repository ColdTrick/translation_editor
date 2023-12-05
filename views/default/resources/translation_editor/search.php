<?php
/**
 * display the search results
 */

use Elgg\Exceptions\Http\BadRequestException;

$q = get_input('q');
if (empty($q)) {
	$exception = new BadRequestException(elgg_echo('error:missing_data'));
	$exception->setRedirectUrl(REFERRER);
	
	throw $exception;
}

$search_keys = (bool) get_input('search_keys', true);

$language = get_input('language', 'en');

$found = translation_editor_search_translation($q, $language, $search_keys);
$trans = elgg()->translator->getInstalledTranslations();

if (!array_key_exists($language, $trans)) {
	$exception = new BadRequestException(elgg_echo('translation_editor:language:unsupported'));
	$exception->setRedirectUrl(elgg_generate_url('default:translation_editor', [
		'current_language' => elgg_get_current_language(),
	]));
	
	throw $exception;
}

$trans_lan = elgg_echo($language);
if (elgg_language_key_exists($language, $language)) {
	$trans_lan = elgg_echo($language, [], $language);
}

elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor'));
elgg_push_breadcrumb($trans_lan, elgg_generate_url('default:translation_editor', [
	'current_language' => $language,
]));

// build page elements

// build search form
$form_vars = [
	'id' => 'translation_editor_search_form',
	'action' => 'translation_editor/search',
	'disable_security' => true,
	'class' => 'mbl',
	'method' => 'GET',
];
$body_vars = [
	'current_language' => $language,
	'query' => $q,
	'search_keys' => $search_keys,
];
$body = elgg_view_form('translation_editor/search', $form_vars, $body_vars);

// display search results
if (!empty($found)) {
	$body_vars = [
		'results' => $found,
		'current_language' => $language,
	];
	$body .= elgg_view('translation_editor/search_results', $body_vars);
} else {
	$body .= elgg_view('output/longtext', [
		'value' => elgg_echo('translation_editor:search_results:no_results')
	]);
}

echo elgg_view_page(elgg_echo('translation_editor:search', [$q, $trans_lan]), [
	'content' => $body,
]);
