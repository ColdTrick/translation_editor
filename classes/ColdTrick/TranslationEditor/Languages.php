<?php

namespace ColdTrick\TranslationEditor;

/**
 * Language related functions
 */
class Languages {
	
	/**
	 * Add menu items to the page menu
	 *
	 * @param \Elgg\Hook $hook 'languages', 'translations'
	 *
	 * @return null|array
	 */
	public static function registerCustomLanguages(\Elgg\Hook $hook): ?array {
		
		$return = $hook->getValue();
		
		$custom_languages = elgg_get_plugin_setting('custom_languages', 'translation_editor');
		if (empty($custom_languages)) {
			return null;
		}
		
		$custom_languages = explode(',', $custom_languages);
		foreach ($custom_languages as $lang) {
			if (in_array($lang, $return)) {
				continue;
			}
			
			$return[] = $lang;
		}
		
		return $return;
	}
}
