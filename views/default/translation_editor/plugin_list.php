<?php
/**
 * Show a listing off all the available plugins to translate
 */

$plugins = elgg_extract('plugins', $vars);
$current_language = elgg_extract('current_language', $vars);

if (empty($plugins)) {
	return;
}

$running_total = 0;
$running_exists = 0;
$running_invalid = 0;
$running_custom = 0;

$table_attributes = [
	'id' => 'translation-editor-plugin-list',
	'class' => 'elgg-table',
];

$list = '<table ' . elgg()->html_formatter->formatAttributes($table_attributes) . '>';

// header
$list .= '<thead>';
$list .= '<tr>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:plugin') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:total') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:exists') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:custom') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:invalid') . '</th>';
$list .= '<th>' . elgg_echo('translation_editor:plugin_list:percentage') . '</th>';
$list .= '<th>&nbsp;</th>';
$list .= '</tr>';
$list .= '</thead>';

$list .= '<tbody>';
foreach ($plugins as $plugin_id => $plugin_stats) {
	$plugin_title = '';
	
	$plugin = elgg_get_plugin_from_id($plugin_id);
	if ($plugin instanceof ElggPlugin) {
		$plugin_title = $plugin->getDisplayName();
	}
	
	$running_total += $plugin_stats['total'];
	$running_exists += $plugin_stats['exists'];
	$running_invalid += $plugin_stats['invalid'];
	$running_custom += $plugin_stats['custom'];
	
	if (!empty($plugin_stats['total'])) {
		$percentage = (int) round(($plugin_stats['exists'] / $plugin_stats['total']) * 100);
		if ($percentage === 100 && $plugin_stats['exists'] !== $plugin_stats['total']) {
			// rounding up to 100 when not complete
			$percentage = 99;
		}
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
		'title' => elgg_echo('translation_editor:plugin_list:title'),
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
	
	$exists = $plugin_stats['exists'];
	if ($plugin_stats['garbage']) {
		$exists = elgg_format_element('span', [
			'class' => ['elgg-state', 'elgg-state-danger'],
			'title' => elgg_echo('translation_editor:plugin_list:garbage'),
		], $exists);
	}
	
	$list .= '<td>' . $exists . '</td>';
	
	if ($plugin_stats['custom'] > 0) {
		$custom_url = elgg_generate_url('default:translation_editor:plugin', [
			'current_language' => $current_language,
			'plugin_id' => $plugin_id,
			'view_mode' => 'custom',
		]);
		$list .= '<td>' . elgg_view_url($custom_url, $plugin_stats['custom']) . '</td>';
	} else {
		$list .= '<td>&nbsp;</td>';
	}
	
	if ($plugin_stats['invalid'] > 0) {
		$invalid_url = elgg_generate_url('default:translation_editor:plugin', [
			'current_language' => $current_language,
			'plugin_id' => $plugin_id,
			'view_mode' => 'params',
		]);
		$list .= '<td>' . elgg_view_url($invalid_url, $plugin_stats['invalid']) . '</td>';
	} else {
		$list .= '<td>&nbsp;</td>';
	}
	
	$list .= elgg_format_element('td', ['class' => $complete_class], "{$percentage}%");
	if (!empty($plugin_stats['custom']) || !empty($plugin_stats['garbage'])) {
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
		if (elgg_is_admin_logged_in() && !empty($plugin_stats['translated'])) {
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
$list .= '<th>' . $running_total . '</th>';
$list .= '<th>' . $running_exists . '</th>';
$list .= '<th>' . $running_custom . '</th>';
$list .= '<th>' . $running_invalid . '</th>';
$list .= '<th>' . round(($running_exists / $running_total) * 100, 2) . '%</th>';
$list .= '<th>&nbsp;</th>';
$list .= '</tr>';
$list .= '</tfoot>';

$list .= '</table>';

echo $list;
