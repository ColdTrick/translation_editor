<?php

namespace ColdTrick\TranslationEditor;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
		
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::boot()
	 */
	public function boot() {
		$this->loadCustomTranslations();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		// extend JS/CSS
		elgg_extend_view('css/elgg', 'css/translation_editor/site.css');
		
		$this->registerPluginHooks();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::upgrade()
	 */
	public function upgrade() {
		Upgrade::cleanupCustomTranslations();
	}
	
	/**
	 * Load the custom translations for the current user
	 *
	 * @return void
	 */
	protected function loadCustomTranslations() {
		
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
	
	/**
	 * Register plugin hook handles
	 *
	 * @return void
	 */
	protected function registerPluginHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('register', 'menu:page', __NAMESPACE__ . '\PageMenu::register');
		$hooks->registerHandler('register', 'menu:site', __NAMESPACE__ . '\SiteMenu::register');
		$hooks->registerHandler('register', 'menu:title', __NAMESPACE__ . '\TitleMenu::register');
		$hooks->registerHandler('register', 'menu:user_hover', __NAMESPACE__ . '\UserHoverMenu::register');
	}
}
