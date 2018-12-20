<?php
/**
 * Show a listing off all the available plugins to translate
 */

$plugins = elgg_extract('plugins', $vars);
$current_language = elgg_extract('current_language', $vars);

if (empty($plugins)) {
	return;
}

$total = 0;
$exists = 0;
$custom = 0;

$table_attributes = [
	'id' => 'translation-editor-plugin-list',
	'class' => 'elgg-table',
];

$list = '<table ' . elgg_format_attributes($table_attributes) . '>';

// header
$list .= '<thead>';
$list .= '<tr>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:plugin') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:total') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:exists') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:custom') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:percentage') . '</th>';
$list .= '<th>&nbsp;</th>';
$list .= '</tr>';
$list .= '</thead>';

// table body
$tbody_attributes = [
	'title' => elgg_echo('translation_editor:plugin_list:title'),
];

$list .= '<tbody ' . elgg_format_attributes($tbody_attributes) . '>';
foreach ($plugins as $plugin_id => $plugin_stats) {
	$plugin_title = '';
	
	$plugin = elgg_get_plugin_from_id($plugin_id);
	if ($plugin instanceof ElggPlugin) {
		$plugin_title = $plugin->getDisplayName();
	}
	
	$total += $plugin_stats['total'];
	$exists += $plugin_stats['exists'];
	$custom += $plugin_stats['custom'];
	
	if (!empty($plugin_stats['total'])) {
		$percentage = round(($plugin_stats['exists'] / $plugin_stats['total']) * 100);
	} else {
		$percentage = 100;
	}
	
	$complete_class = [];
	
	if ($percentage == 100) {
		$complete_class[] = 'translation-editor-translation-complete';
	} elseif ($percentage == 0) {
		$complete_class[] = 'translation-editor-translation-needed';
	}
	
	$list .= '<tr>';
	$list .= '<td>';
	$list .= elgg_view('output/url', [
		'text' => $plugin_id,
		'title' => $plugin_title,
		'href' => elgg_generate_url('default:translation_editor:plugin', [
			'current_language' => $current_language,
			'plugin_id' => $plugin_id,
		]),
	]);
	if (!empty($plugin_title)) {
		$list .= elgg_format_element('span', ['class' => 'elgg-subtext mls'], $plugin_title);
	}
	$list .= '</td>';
	$list .= '<td>' . $plugin_stats['total'] . '</td>';
	$list .= '<td>' . $plugin_stats['exists'] . '</td>';
	
	if ($plugin_stats['custom'] > 0) {
		$list .= '<td>' . $plugin_stats['custom'] . '</td>';
	} else {
		$list .= '<td>&nbsp;</td>';
	}
	
	$list .= elgg_format_element('td', ['class' => $complete_class], "{$percentage}%");
	if (!empty($plugin_stats['custom'])) {
		$list .= '<td class="translation-editor-plugin-actions">';
		$list .= elgg_view('output/url', [
			'href' => elgg_generate_action_url('translation_editor/merge', [
				'current_language' => $current_language,
				'plugin' => $plugin_id,
			]),
			'title' => elgg_echo('translation_editor:plugin_list:merge'),
			'text' => elgg_echo('translation_editor:plugin_list:merge'),
			'icon' => 'download',
		]);
		if (elgg_is_admin_logged_in()) {
			$list .= elgg_view('output/url', [
				'href' => elgg_generate_action_url('translation_editor/admin/delete', [
					'current_language' => $current_language,
					'plugin' => $plugin_id,
				]),
				'title' => elgg_echo('delete'),
				'text' => elgg_echo('delete'),
				'icon' => 'delete-alt',
				'confirm' => elgg_echo('deleteconfirm'),
				'class' => 'mlm',
			]);
		}
		$list .= '</td>';
	} else {
		$list .= '<td>&nbsp;</td>';
	}
	$list .= '</tr>';
}

$list .= '</tbody>';

// footer
$list .= '<tfoot>';
$list .= '<tr>';
$list .= '<th>&nbsp;</td>';
$list .= '<th>' . $total . '</th>';
$list .= '<th>' . $exists . '</th>';
$list .= '<th>' . $custom . '</th>';
$list .= '<th>' . round(($exists / $total) * 100, 2) . '%</th>';
$list .= '<th>&nbsp;</th>';
$list .= '</tr>';
$list .= '</tfoot>';

$list .= '</table>';

echo $list;
