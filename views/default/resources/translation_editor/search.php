<?php
use Elgg\Exceptions\Http\BadRequestException;

/**
 * display the search results
 */

// get inputs
$q = get_input('q');
if (empty($q)) {
	$exception = new BadRequestException(elgg_echo('error:missing_data'));
	$exception->setRedirectUrl(REFERER);
	
	throw $exception;
}

$language = get_input('language', 'en');

$found = translation_editor_search_translation($q, $language);
$trans = elgg()->translator->getInstalledTranslations();

if (!array_key_exists($language, $trans)) {
	$exception = new BadRequestException(elgg_echo('translation_editor:language:unsupported'));
	$exception->setRedirectUrl(elgg_generate_url('default:translation_editor', [
		'current_language' => get_current_language(),
	]));
	
	throw $exception;
}

$trans_lan = elgg_echo($language);
if (elgg_language_key_exists($language, $language)) {
	$trans_lan = elgg_echo($language, [], $language);
}

$title_text = elgg_echo('translation_editor:search', [$q, $trans_lan]);

// breadcrumb
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

// draw page
echo elgg_view_page($title_text, [
	'content' => $body,
]);
