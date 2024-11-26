<?php

use ColdTrick\TranslationEditor\PluginTranslation;
use ColdTrick\TranslationEditor\Rest;
use Elgg\WebServices\ElggApiClient;

$language = get_input('language');
$plugins = (array) get_input('plugins');

if (empty($language) || empty($plugins)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

set_time_limit(0);

$client = Rest::getClient('translation_editor.get_translations', [
	'language' => $language,
	'plugins' => $plugins,
]);
if (!$client instanceof ElggApiClient) {
	return elgg_error_response(elgg_echo('translation_editor:action:remote:error:client'));
}

try {
	$remote_result = $client->executeRequest();
} catch (\Exception $e) {
	elgg_log($e, \Psr\Log\LogLevel::ERROR);
	return elgg_error_response(elgg_echo('translation_editor:action:remote:error:request'));
}

if (empty($remote_result)) {
	return elgg_error_response(elgg_echo('translation_editor:action:remote:error:result'));
}

$remote_result = json_decode($remote_result, true);
if (!is_array($remote_result)) {
	return elgg_error_response(elgg_echo('translation_editor:action:remote:error:result'));
}

if (elgg_extract('status', $remote_result) !== 0) {
	return elgg_error_response(elgg_extract('message', $remote_result));
}

$plugin_results = elgg_extract('result', $remote_result);
foreach ($plugin_results as $plugin_id => $values) {
	$plugin_translations = new PluginTranslation($plugin_id, $language);
	if (empty($values)) {
		$plugin_translations->removeTranslations();
	} else {
		$plugin_translations->saveTranslations($values);
	}
}

translation_editor_merge_translations($language);
translation_editor_log_last_import($language);

return elgg_ok_response('', elgg_echo('translation_editor:action:remote:success'));
