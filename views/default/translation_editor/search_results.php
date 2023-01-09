<?php
/**
 * Show the edit form for the search result translation keys
 */

$search_results = elgg_extract('results', $vars);
$current_language = elgg_extract('current_language', $vars);

$list = '';
foreach ($search_results as $plugin => $data) {
	$translated_language = elgg_extract('current_language', $data);
	$original_language = elgg_extract('original_language', $data);
	$snapshot_language = elgg_extract('snapshot_language', $data);
	
	$list .= '<table class="elgg-table translation-editor-translation-table mbl">';
	$list .= '<col class="first_col" />';
	$list .= '<tr class="first_row"><th colspan="2">';
	$list .= elgg_view_url("translation_editor/{$current_language}/{$plugin}", $plugin);
	$list .= '</th></tr>';
	
	foreach ($data['en'] as $key => $value) {
		
		$list .= elgg_view('translation_editor/key_edit', [
			'english' => [
				'key' => $key,
				'value' => $value,
			],
			'translation' => [
				'key' => $key,
				'value' => elgg_extract($key, $translated_language),
			],
			'original' => [
				'key' => $key,
				'value' => elgg_extract($key, $original_language),
			],
			'snapshot' => [
				'key' => $key,
				'value' => elgg_extract($key, $snapshot_language),
			],
			'plugin' => $plugin,
			'language' => $current_language,
		]);
	}
	
	$list .= '</table>';
}

echo $list;
