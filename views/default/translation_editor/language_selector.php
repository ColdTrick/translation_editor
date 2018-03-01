<?php
/**
 * show the list of available languages to translate
 */

$languages = elgg_extract('languages', $vars);
$current_language = elgg_extract('current_language', $vars);
$plugin = elgg_extract('plugin', $vars);
$disabled_languages = elgg_extract('disabled_languages', $vars);
$site_language = elgg_extract('site_language', $vars);

$language_route = 'default:translation_editor';
if (!empty($plugin)) {
	$language_route = 'default:translation_editor:plugin';
}
$language_route_params = [
	'current_language' => $current_language,
	'plugin_id' => $plugin,
];

$content = '';

if (!empty($languages)) {
	$table_attributes = [
		'id' => 'translation-editor-language-table',
		'class' => 'elgg-table mbm',
		'title' => elgg_echo('translation_editor:language_selector:title'),
	];
	
	$table_content = '';
	
	$header = [];
	$header[] = elgg_format_element('th', ['class' => 'translation-editor-flag'], '&nbsp;');
	$header[] = elgg_format_element('th', [], elgg_echo('translation_editor:language'));
	if (elgg_is_admin_logged_in()) {
		$header[] = elgg_format_element('th', ['class' => 'translation-editor-disable'], elgg_echo('disable'));
		$header[] = elgg_format_element('th', ['class' => 'translation-editor-delete'], elgg_echo('delete'));
	}
	$header = elgg_format_element('tr', [], implode(PHP_EOL, $header));
	$table_content .= elgg_format_element('thead', [], $header);
	
	$rows = [];
	foreach ($languages as $language) {
		$row = [];
		
		// flag
		$row[] = elgg_format_element('td', ['class' => 'translation-editor-flag'], elgg_view('translation_editor/flag', [
			'language' => $language,
		]));
		
		// language
		$translated_language = $language;
		if (elgg_language_key_exists($language, $language)) {
			// display language in own language
			$translated_language = elgg_echo($language, [], $language);
		} elseif (elgg_language_key_exists($language)) {
			// fallback to English
			$translated_language = elgg_echo($language);
		}
		
		$completeness = '';
		$allow_delete = false;
		if ($language !== 'en') {
			$completeness = translation_editor_get_language_completeness($language);
			if (empty($completeness)) {
				$allow_delete = true;
			}
			
			$completeness = elgg_format_element('span', ['class' => 'mls'], "({$completeness}%)");
		}
		
		$site_notice = '';
		if ($site_language === $language) {
			$site_notice = elgg_format_element('span', ['class' => 'elgg-quiet mls'], elgg_echo('translation_editor:language_selector:site_language'));
		}
		
		$language_url = '';
		if ($language !== $current_language) {
			$allow_delete = false;
			
			$language_route_params['current_language'] = $language;
			$language_url = elgg_generate_url($language_route, $language_route_params);
		}
		
		if (empty($language_url)) {
			$row[] = elgg_format_element('td', [], $translated_language . $completeness . $site_notice);
		} else {
			$row[] = elgg_format_element('td', [], elgg_view('output/url', [
				'text' => $translated_language . $completeness,
				'href' => $language_url,
				'is_trusted' => true,
			]) . $site_notice);
		}
		
		if (elgg_is_admin_logged_in()) {
			elgg_require_js('translation_editor/disable_language');
			
			// disable language
			if ($language !== 'en') {
				$row[] = elgg_format_element('td', ['class' => 'translation-editor-disable'], elgg_view('input/checkbox', [
					'name' => 'disabled_languages[]',
					'value' => $language,
					'default' => false,
					'checked' => in_array($language, $disabled_languages),
				]));
			} else {
				$row[] = elgg_format_element('td', ['class' => 'translation-editor-disable'], '&nbsp;');
			}
			
			// delete
			if ($allow_delete) {
				$row[] = elgg_format_element('td', ['class' => 'translation-editor-delete'], elgg_view('output/url', [
					'href' => elgg_generate_action_url('translation_editor/admin/delete_language', [
						'language' => $language,
					]),
					'confirm' => elgg_echo('translation_editor:language_selector:remove_language:confirm'),
					'text' => elgg_echo('delete'),
					'title' => elgg_echo('delete'),
					'icon' => 'delete-alt',
				]));
			} else {
				$row[] = elgg_format_element('td', ['class' => 'translation-editor-delete'], '&nbsp;');
			}
		}
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
	}
	$table_content .= elgg_format_element('tbody', [], implode(PHP_EOL, $rows));
	
	$content .= elgg_format_element('table', $table_attributes, $table_content);
}

if (elgg_is_admin_logged_in()) {
	// add a new language
	$content .= elgg_view('translation_editor/add_language');
}

if (empty($content)) {
	return;
}

elgg_register_plugin_hook_handler('register', 'menu:title', '\ColdTrick\TranslationEditor\TitleMenu::registerLanguageSelector');

echo elgg_format_element('div', ['id' => 'translation-editor-language-selection', 'class' => 'hidden'], $content);
