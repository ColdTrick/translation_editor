<?php
/**
 * All helper function for this plugin are bundled here
 */

use Elgg\Project\Paths;
use Elgg\Includer;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * Returns array of all available plugins and their individual language keys
 *
 * @param string $current_language which language to use
 *
 * @return array
 */
function translation_editor_get_plugins(string $current_language): array {
	if (empty($current_language)) {
		return [];
	}
	
	$translator = elgg()->translator;
	
	$translator->reloadAllTranslations();
	$translator->loadTranslations($current_language);
	
	translation_editor_load_translations($current_language);

	$loaded_translations = $translator->getLoadedTranslations();
	
	// Core translation
	$core = [
		'core' => translation_editor_get_core_statistics($loaded_translations, $current_language),
	];
	
	// Plugin translations
	$plugins = elgg_get_plugins();
	$plugins_result = [];
	
	foreach ($plugins as $plugin) {
		$plugin_stats = translation_editor_get_plugin_statistics($plugin, $loaded_translations, $current_language);
		if (empty($plugin_stats)) {
			continue;
		}
		
		$plugins_result[$plugin->getID()] = $plugin_stats;
	}
	
	ksort($plugins_result);
	
	return $core + $plugins_result;
}

/**
 * Returns (cached) stats for core translations
 *
 * @param array  $loaded_translations currently loaded translations
 * @param string $language            language to get the stats for
 *
 * @return array
 */
function translation_editor_get_core_statistics(array $loaded_translations, string $language): array {
	$language_file = Paths::elgg() . 'languages' . DIRECTORY_SEPARATOR . 'en.php';
	if (!file_exists($language_file)) {
		return [];
	}
	
	$core_keys = Includer::includeFile($language_file);
	
	$core_translations = [];
	$garbage_count = 0;
	$core_language_file = Paths::elgg() . 'languages' . DIRECTORY_SEPARATOR . $language . '.php';
	if (file_exists($core_language_file)) {
		$core_translations = Includer::includeFile($core_language_file);
		
		// cleanup core translations
		$garbage_count = array_diff_key($core_translations, $core_keys);
		$core_translations = array_intersect_key($core_translations, $core_keys);
	}
	
	$missing_translations = array_diff_key($core_keys, $loaded_translations[$language]);
	$custom_translations = translation_editor_read_translation($language, 'core');
	$custom_existing_translations = array_intersect_key($custom_translations, $core_keys);
	
	if (!empty($core_translations)) {
		// in case language is not yet translated
		$custom_count = count(array_diff_assoc($custom_existing_translations, $core_translations));
	} else {
		$custom_count = count($custom_existing_translations);
	}
		
	$result = [
		'total' => count($core_keys), // number of keys in en
		'exists' => 0, // number of translated keys (including runtime and custom translations) in loaded translations
		'invalid' => 0, // number of translations with an issue in them
		'custom' => $custom_count, // number of translations made with translation editor of keys that still exist
		'translated' => count($custom_translations), // number of translations in the plugin language file that no longer exist in the en.php
		'garbage' => $garbage_count, // number of translations that have been made, but keys no longer exist
	];
	
	if (array_key_exists($language, $loaded_translations)) {
		$result['exists'] = $result['total'] - count($missing_translations);
		
		foreach ($core_keys as $key => $value) {
			if (translation_editor_get_invalid_parameters($value, (string) elgg_extract($key, $loaded_translations[$language], ''))) {
				$result['invalid']++;
			}
		}
	}
	
	return $result;
}

/**
 * Returns (cached) stats for plugin translations
 *
 * @param \ElggPlugin $plugin              plugin to get the stats for
 * @param array       $loaded_translations currently loaded translations
 * @param string      $language            language to get the stats for
 *
 * @return array
 */
function translation_editor_get_plugin_statistics(\ElggPlugin $plugin, array $loaded_translations, string $language): array {
	$plugin_id = $plugin->getID();
	
	$language_file = $plugin->getPath() . 'languages' . DIRECTORY_SEPARATOR . 'en.php';
	if (!file_exists($language_file)) {
		return [];
	}
	
	$plugin_keys = Includer::includeFile($language_file);
	if (!is_array($plugin_keys)) {
		elgg_log("Please update the language file [en.php] of '{$plugin_id}' to return an array", 'WARNING');
		return [];
	}
	
	$plugin_translations = [];
	$garbage_count = 0;
	$plugin_language_file = $plugin->getPath() . 'languages' . DIRECTORY_SEPARATOR . $language . '.php';
	if (file_exists($plugin_language_file)) {
		$plugin_translations = Includer::includeFile($plugin_language_file);
		if (!is_array($plugin_translations)) {
			elgg_log("Please update the language file [{$language}.php] of '{$plugin_id}' to return an array", 'WARNING');
			$plugin_translations = [];
		} else {
			// cleanup plugin translations
			$garbage_count = array_diff_key($plugin_translations, $plugin_keys);
			$plugin_translations = array_intersect_key($plugin_translations, $plugin_keys);
		}
	}
	
	$missing_translations = array_diff_key($plugin_keys, $loaded_translations[$language]);
	$custom_translations = translation_editor_read_translation($language, $plugin_id);
	$custom_existing_translations = array_intersect_key($custom_translations, $plugin_keys);
	
	if (!empty($plugin_translations)) {
		// in case language is not yet translated
		$custom_count = count(array_diff_assoc($custom_existing_translations, $plugin_translations));
	} else {
		$custom_count = count($custom_existing_translations);
	}
	
	$result = [
		'total' => count($plugin_keys), // number of keys in en
		'exists' => 0, // number of translated keys (including runtime and custom translations) in loaded translations
		'invalid' => 0, // number of translations with an issue in them
		'custom' => $custom_count, // number of translations made with translation editor of keys that still exist
		'translated' => count($custom_translations), // number of translations in the plugin language file that no longer exist in the en.php
		'garbage' => $garbage_count, // number of translations that have been made, but keys no longer exist
	];
	
	if (array_key_exists($language, $loaded_translations)) {
		$result['exists'] = $result['total'] - count($missing_translations);
		
		foreach ($plugin_keys as $key => $value) {
			if (translation_editor_get_invalid_parameters($value, (string) elgg_extract($key, $loaded_translations[$language], ''))) {
				$result['invalid']++;
			}
		}
	}
	
	return $result;
}

/**
 * Returns translation data for a specific plugin
 *
 * @param string $current_language which language to return
 * @param string $plugin           for which plugin do you want the translations
 *
 * @return null|array
 */
function translation_editor_get_plugin(string $current_language, string $plugin): ?array {
	if (empty($current_language) || empty($plugin)) {
		return null;
	}

	if ($plugin == 'core') {
		// Core translation
		$plugin_language_path = Paths::elgg() . 'languages' . DIRECTORY_SEPARATOR;
	} else {
		// plugin translations
		$plugin_object = elgg_get_plugin_from_id($plugin);
		if (!$plugin_object instanceof \ElggPlugin) {
			return null;
		}
		
		$plugin_language_path = $plugin_object->getPath() . 'languages' . DIRECTORY_SEPARATOR;
	}
	
	$translator = elgg()->translator;
	
	$translator->loadTranslations($current_language);
	
	translation_editor_load_translations($current_language);
	
	$result = [
		'total' => 0
	];
	
	$backup_full = $translator->getLoadedTranslations();
	
	// Fetch translations
	if (file_exists("{$plugin_language_path}en.php")) {
		$plugin_keys = Includer::includeFile("{$plugin_language_path}en.php");
		if (!is_array($plugin_keys)) {
			elgg_log("Please update the language file of '{$plugin}' to return an array", 'WARNING');
			return null;
		}
		
		$key_count = count($plugin_keys);
		
		if (array_key_exists($current_language, $backup_full)) {
			$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
		} else {
			$exists_count = 0;
		}
		
		$result['total'] = $key_count;
		$result['exists'] = $exists_count;
		$result['en'] = $plugin_keys;
		$result['current_language'] = array_intersect_key(elgg_extract($current_language, $backup_full, []), $plugin_keys);
		$result['original_language'] = [];
		if (file_exists("{$plugin_language_path}{$current_language}.php")) {
			$result['original_language'] = Includer::includeFile("{$plugin_language_path}{$current_language}.php");
		}
		
		$result['custom'] = translation_editor_read_translation($current_language, $plugin);
	}
	
	return $result;
}

/**
 * Compare the provided translations to filter out the custom translations
 *
 * @param array $translated      the provided translations
 * @param array $plugin_original the original language keys/values of the plugin
 *
 * @return false|array
 */
function translation_editor_compare_translations(array $translated, array $plugin_original): ?array {
	if (empty($translated)) {
		return null;
	}
	
	$result = [];
	
	foreach ($translated as $key => $value) {
		if (elgg_is_empty(trim($value))) {
			// no need to return empty values
			continue;
		}
		
		if (!isset($plugin_original[$key])) {
			// not yet translated
			$result[$key] = $value;
			continue;
		}
		
		$original_value = $plugin_original[$key];
		
		$original = translation_editor_clean_line_breaks(html_entity_decode($original_value, ENT_NOQUOTES, 'UTF-8'));
		$new = translation_editor_clean_line_breaks(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'));
		
		// if original string contains beginning/trailing spaces (eg ' in the group '),
		// don't trim translated
		$trim_needed = (strlen($original) === strlen(trim($original)));
		if ($trim_needed) {
			$new = trim($new);
		}
		
		if ($original !== $new && strlen($new) > 0) {
			$result[$key] = $new;
		}
	}
	
	return $result;
}

/**
 * Replace different line endings with the ones used by the current OS
 *
 * @param string $string the text to replace the line endings in
 *
 * @return string
 */
function translation_editor_clean_line_breaks(string $string): string {
	return preg_replace("/(\r\n)|(\n|\r)/", PHP_EOL, $string);
}

/**
 * Write the custom translation for a plugin to disk
 *
 * @param string $current_language the language for the translations
 * @param string $plugin           the translated plugin
 * @param array  $translations     the translations
 *
 * @return false|int
 */
function translation_editor_write_translation(string $current_language, string $plugin, array $translations) {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->saveTranslations($translations);
	} catch (InvalidArgumentException $e) {
		elgg_log($e);
	}
	
	return false;
}

/**
 * Read the custom translations from disk
 *
 * @param string $current_language the language to fetch
 * @param string $plugin           the plugin to fetch
 *
 * @return array
 */
function translation_editor_read_translation(string $current_language, string $plugin): array {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->readTranslations() ?: [];
	} catch (InvalidArgumentException $e) {
		elgg_log($e);
	}
	
	return [];
}

/**
 * Load all the custom translations into the running translations
 *
 * @param string $current_language the language to load (defaults to the language of the current user)
 *
 * @return void
 */
function translation_editor_load_translations(string $current_language = ''): void {
	if (empty($current_language)) {
		$current_language = elgg_get_current_language();
	}
	
	// load translations
	// using elgg_get_system_cache() to bypass enabled setting
	$cache = elgg_get_system_cache();
	$translations = $cache->load("translation_editor_merged_{$current_language}");
	if (!is_array($translations)) {
		// cache was reset rebuild it
		$translations = translation_editor_merge_translations($current_language);
	}
	
	if (!empty($translations)) {
		// need to make sure translations are loaded in order to append/override existing translations
		elgg_language_key_exists('ensure_translations_loaded', $current_language);
		
		elgg()->translator->addTranslation($current_language, $translations);
	}
}

/**
 * Remove the custom translations for a plugin
 *
 * @param string $current_language the language to remove
 * @param string $plugin           the plugin to remove
 *
 * @return bool
 */
function translation_editor_delete_translation(string $current_language, string $plugin): bool {
	try {
		$translation = new \ColdTrick\TranslationEditor\PluginTranslation($plugin, $current_language);
		return $translation->removeTranslations();
	} catch (InvalidArgumentException $e) {
		elgg_log($e);
	}
	
	return false;
}

/**
 * Custom version of get_language_completeness() to give better results
 *
 * @param string $current_language the language to check
 *
 * @return float|null
 * @see get_language_completeness()
 */
function translation_editor_get_language_completeness(string $current_language): ?float {
	if (empty($current_language) || ($current_language == 'en')) {
		return null;
	}
	
	$plugins = translation_editor_get_plugins($current_language);
	if (empty($plugins)) {
		return (float) 0;
	}
		
	$english_count = 0;
	$current_count = 0;
	
	foreach ($plugins as $plugin) {
		$english_count += $plugin['total'];
		$current_count += $plugin['exists'];
	}
	
	return round(($current_count / $english_count) * 100, 2);
}

/**
 * Check if the provided user is a translation editor
 *
 * @param int $user_guid the user to check (defaults to current user)
 *
 * @return bool
 */
function translation_editor_is_translation_editor(int $user_guid = 0): bool {
	static $editors_cache;
	
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	$user = get_user($user_guid);
	if (!$user instanceof \ElggUser) {
		return false;
	}
	
	if ($user->isAdmin()) {
		return true;
	}
	
	// preload all editors
	if (!isset($editors_cache)) {
		$editors_cache = [];
		
		$guids = elgg_get_entities([
			'type' => 'user',
			'limit' => false,
			'metadata_name_value_pairs' => [
				'name' => 'translation_editor',
				'value' => true,
			],
			'callback' => function ($row) {
				return (int) $row->guid;
			},
		]);
		if (!empty($guids)) {
			$editors_cache = $guids;
		}
	}
	
	// is the user an editor or an admin
	return in_array($user_guid, $editors_cache);
}

/**
 * Search for a translation
 *
 * @param string $query    the text to search for
 * @param string $language the language to search in (defaults to English)
 *
 * @return array|null
 */
function translation_editor_search_translation(string $query, string $language = 'en'): ?array {
	$plugins = translation_editor_get_plugins($language);
	if (empty($plugins)) {
		return null;
	}
	
	$found = [];
	foreach ($plugins as $plugin => $data) {
		$translations = translation_editor_get_plugin($language, $plugin);
		if (empty($translations) || empty(elgg_extract('total', $translations))) {
			continue;
		}
		
		foreach ($translations['en'] as $key => $value) {
			if (stristr($key, $query) || stristr($value, $query) || (array_key_exists($key, $translations['current_language']) && stristr($translations['current_language'][$key], $query))) {
				if (!array_key_exists($plugin, $found)) {
					$found[$plugin] = [
						'en' => [],
						'current_language' => [],
					];
				}
				
				$found[$plugin]['en'][$key] = $value;
				if (array_key_exists($key, $translations['current_language'])) {
					$found[$plugin]['original_language'][$key] = elgg_extract($key, $translations['original_language']);
					$found[$plugin]['current_language'][$key] = $translations['current_language'][$key];
				}
			}
		}
	}
	
	return $found ?: null;
}

/**
 * Merge all custom translations into a single file for performance
 *
 * @param string $language the language to merge
 *
 * @return false|array
 */
function translation_editor_merge_translations(string $language = ''): ?array {
	if (empty($language)) {
		$language = elgg_get_current_language();
	}
	
	if (empty($language)) {
		return null;
	}
	
	$translations = [];
	
	// get core translations
	$core = translation_editor_read_translation($language, 'core');
	if (!empty($core)) {
		$translations = $core;
	}
		
	// process all plugins
	$plugins = elgg_get_plugins();
	if (!empty($plugins)) {
		foreach ($plugins as $plugin) {
			// add plugin translations
			$plugin_translation = translation_editor_read_translation($language, $plugin->getID());
			if (!empty($plugin_translation)) {
				$translations += $plugin_translation;
			}
		}
	}
	
	// write merged to cache
	// using elgg_get_system_cache() to bypass enabled setting
	$cache = elgg_get_system_cache();
	$cache->save("translation_editor_merged_{$language}", $translations);
	
	// clear system cache
	$cache->delete("{$language}.lang");
	
	// let others know this happened
	elgg_trigger_event('language:merge', 'translation_editor', $language);
	
	return $translations;
}

/**
 * Parses a string meant for printf and returns an array of found parameters
 *
 * @param string $string the string to search parameters for
 * @param bool   $count  return the count of the parameters (default = true)
 *
 * @return array
 */
function translation_editor_get_string_parameters(string $string, bool $count = true): array {
	$valid = '/%(?:\d+\$)?[-+]?(?:[ 0]|\'.)?a?\d*(?:\.\d*)?[%bcdeEufFgGosxX]/';
	
	$result = [];
	
	if (!empty($string)) {
		$matches = [];
		$string = preg_replace('/^[^%]*/', '', $string);
		if (!empty($string) && preg_match_all($valid, $string, $matches)) {
			$result = $matches[0];
		}
	}
	
	return $count ? count($result) : $result;
}

/**
 * Returns an array of invalid or missing parameters in one of the translations
 *
 * @param string $value            original value
 * @param string $translated_value translated value
 *
 * @return array
 */
function translation_editor_get_invalid_parameters(string $value, string $translated_value): array {
	if (empty($value) || empty($translated_value)) {
		return [];
	}
	
	$params = translation_editor_get_string_parameters($value, false);
	$translated_params = translation_editor_get_string_parameters($translated_value, false);
	
	return array_diff($params, $translated_params) + array_diff($translated_params, $params);
}

/**
 * Get available languages on the system
 *
 * Used for caching purpose
 *
 * @see elgg()->translator->getAvailableLanguages()
 * @return array
 */
function translation_editor_get_available_languages(): array {
	static $result;
	
	if (isset($result)) {
		return $result;
	}
	
	$result = elgg()->translator->getAvailableLanguages();
	
	return $result;
}

/**
 * Log the last time an import was executed
 *
 * @param string $language the imported language
 *
 * @return void
 * @interal
 */
function translation_editor_log_last_import(string $language): void {
	$plugin = elgg_get_plugin_from_id('translation_editor');
	$key = "last_import_{$language}";
	
	$plugin->$key = json_encode([
		'actor' => elgg_get_logged_in_user_entity()->username,
		'time' => time(),
	]);
}

/**
 * Get the last import information
 *
 * @param string $language the language to check for
 *
 * @return array
 * @interal
 */
function translation_editor_get_last_import(string $language): array {
	$plugin = elgg_get_plugin_from_id('translation_editor');
	$key = "last_import_{$language}";
	
	$result = $plugin->$key;
	if (empty($result)) {
		return [];
	}
	
	$result = json_decode($result, true);
	$result['user'] = elgg_get_user_by_username($result['actor']);
	
	return $result;
}
