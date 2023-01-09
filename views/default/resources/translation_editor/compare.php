<?php

use ColdTrick\TranslationEditor\DI\Snapshot;
use Elgg\Values;

$snapshot = (int) elgg_extract('snapshot', $vars);
$language = elgg_extract('language', $vars);

$trans_lan = elgg_echo($language);
if (elgg_language_key_exists($language, $language)) {
	$trans_lan = elgg_echo($language, [], $language);
}

$date = Values::normalizeTime($snapshot);

$title_text = elgg_echo('translation_editor:compare', [$date->formatLocale(elgg_echo('friendlytime:date_format')), $trans_lan]);

// breadcrumb
elgg_push_breadcrumb(elgg_echo('translation_editor:menu:title'), elgg_generate_url('default:translation_editor'));
elgg_push_breadcrumb($trans_lan, elgg_generate_url('default:translation_editor', [
	'current_language' => $language,
]));

// display compare results
$results = Snapshot::instance()->compare($snapshot, $language);
if (!empty($results)) {
	$body_vars = [
		'results' => $results,
		'current_language' => $language,
	];
	$body = elgg_view('translation_editor/search_results', $body_vars);
} else {
	$body = elgg_view('output/longtext', [
		'value' => elgg_echo('translation_editor:compare:no_results')
	]);
}

// draw page
echo elgg_view_page($title_text, [
	'content' => $body,
]);
