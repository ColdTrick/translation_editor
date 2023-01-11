<?php

namespace ColdTrick\TranslationEditor\Rest;

use ColdTrick\TranslationEditor\PluginTranslation;
use Elgg\Exceptions\InvalidArgumentException;

class GetTranslations {
	
	/**
	 * Get the custom translations
	 *
	 * @param string $language the language to get the translations for
	 * @param array  $plugins  the list of the plugins to get the translations for
	 *
	 * @return \GenericResult
	 */
	public function __invoke(string $language, array $plugins): \GenericResult {
		$available_languages = translation_editor_get_available_languages();
		if (!in_array($language, $available_languages)) {
			return new \ErrorResult(elgg_echo('translation_editor:api:get_translations:error:language'));
		}
		
		$result = [];
		foreach ($plugins as $plugin) {
			if ($plugin !== 'core' && !elgg_get_plugin_from_id($plugin) instanceof \ElggPlugin) {
				// plugin doesn't exist on this installation
				continue;
			}
			
			try {
				$translations = new PluginTranslation($plugin, $language);
			} catch (InvalidArgumentException $e) {
				continue;
			}
			
			$result[$plugin] = $translations->readTranslations();
		}
		
		return new \SuccessResult($result);
	}
}
