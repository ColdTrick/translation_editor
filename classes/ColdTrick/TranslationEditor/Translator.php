<?php

namespace ColdTrick\TranslationEditor;

class Translator {
	
	/**
	 * Remove disabled languages
	 *
	 * @param \Elgg\Hook $hook 'languages', 'translations'
	 *
	 * @return void|array
	 */
	public static function removeLanguages(\Elgg\Hook $hook) {
		
		if (elgg_in_context('translation_editor')) {
			return;
		}
		
		$disabled_languages = translation_editor_get_disabled_languages();
		if (empty($disabled_languages)) {
			return;
		}
		
		$result = $hook->getValue();
		
		foreach ($disabled_languages as $language) {
			$key = array_search($language, $result);
			if ($key === false) {
				continue;
			}
			
			unset($result[$key]);
		}
		
		return $result;
	}
}
