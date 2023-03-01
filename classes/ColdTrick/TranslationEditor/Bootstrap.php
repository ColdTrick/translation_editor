<?php

namespace ColdTrick\TranslationEditor;

use Elgg\DefaultPluginBootstrap;

/**
 * Plugin bootstrap
 */
class Bootstrap extends DefaultPluginBootstrap {
		
	/**
	 * {@inheritDoc}
	 */
	public function boot() {
		$this->loadCustomTranslations();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function upgrade() {
		Upgrade::cleanupCustomTranslations();
	}
	
	/**
	 * Load the custom translations for the current user
	 *
	 * @return void
	 */
	protected function loadCustomTranslations(): void {
		$translator = $this->elgg()->translator;
		
		$user_language = $translator->getCurrentLanguage();
		$elgg_default_language = 'en';
		
		$load_languages = [
			$user_language,
			$elgg_default_language,
		];
		$load_languages = array_unique($load_languages);
		
		$allowed_languages = $translator->getAllowedLanguages();
		foreach ($load_languages as $language) {
			if (!in_array($language, $allowed_languages)) {
				continue;
			}
			
			// add custom translations
			translation_editor_load_translations($language);
		}
	}
}
