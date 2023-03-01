<?php
/**
 * Create and list translation snapshots
 */

use ColdTrick\TranslationEditor\DI\Snapshot;
use Elgg\Values;

elgg_admin_gatekeeper();

$language = get_input('language', elgg_get_current_language());

// help
$content = elgg_view('output/longtext', [
	'value' => elgg_echo('translation_editor:snapshots:description'),
]);

// list snapshots
$snapshots = Snapshot::instance()->getAll();
if (!empty($snapshots)) {
	$header = [
		elgg_format_element('th', [], elgg_echo('translation_editor:snapshots:table:snapshot')),
		elgg_format_element('th', [], elgg_echo('translation_editor:snapshots:table:actions')),
	];
	$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
	$header = elgg_format_element('thead', [], $header);
	
	$rows = [];
	foreach ($snapshots as $time => $info) {
		$row = [];
		
		// show a date
		$postfix = '';
		$creator = elgg_extract('creator', $info);
		if (!empty($creator)) {
			$user = elgg_get_user_by_username($creator);
			if ($user instanceof \ElggUser) {
				$postfix = ' - ' . elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_echo('byline', [$user->getDisplayName()]));
			}
		}
		
		$date = Values::normalizeTime($time);
		$row[] = elgg_format_element('td', [], $date->formatLocale(elgg_echo('friendlytime:date_format')) . $postfix);
		
		// snapshot actions
		$compare = elgg_view('output/url', [
			'icon' => 'eye',
			'text' => elgg_echo('translation_editor:snapshots:table:actions:compare'),
			'href' => elgg_generate_url('default:translation_editor:compare', [
				'language' => $language,
				'snapshot' => $time,
			]),
			'class' => 'mrm',
		]);
		
		$delete = elgg_view('output/url', [
			'icon' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('translation_editor/snapshots/delete', [
				'snapshot' => $time,
			]),
			'confirm' => elgg_echo('deleteconfirm'),
			'class' => 'mrm',
		]);
		$row[] = elgg_format_element('td', [], $compare . $delete);
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
	}
	
	$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));
	
	$content .= elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);
}

// output
echo elgg_view_module('info', elgg_echo('translation_editor:snapshots:title'), $content, [
	'menu' => elgg_view('output/url', [
		'icon' => 'plus',
		'text' => elgg_echo('translation_editor:snapshots:create'),
		'href' => elgg_generate_action_url('translation_editor/snapshots/create'),
		'class' => ['elgg-button', 'elgg-button-action'],
	]),
]);
