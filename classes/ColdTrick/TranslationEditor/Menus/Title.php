<?php
namespace ColdTrick\TranslationEditor\Menus;

/**
 * Add menu items to the site menu
 */
class Title {
	
	/**
	 * Add menu items to the title menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:title'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_in_context('translation_editor')) {
			return;
		}
			
		$current_language = get_input('current_language');
		$plugin_id = get_input('plugin_id');
		
		$return = $hook->getValue();
		// show import/export buttons only on language page (not on plugins)
		if (elgg_is_admin_logged_in() && $current_language && empty($plugin_id)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-import',
				'text' => elgg_echo('import'),
				'icon' => 'file-import',
				'href' => "translation_editor/import/{$current_language}",
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 210,
			]);
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-export',
				'text' => elgg_echo('export'),
				'icon' => 'file-export',
				'href' => "translation_editor/export/{$current_language}",
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 220,
			]);
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-snapshots',
				'text' => elgg_echo('translation_editor:snapshots'),
				'icon' => 'eye',
				'href' => elgg_http_add_url_query_elements("ajax/view/translation_editor/snapshots", [
					'language' => $current_language,
				]),
				'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
				'priority' => 230,
			]);
		}
		
		// download button only on plugin page
		if (!empty($plugin_id)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-merge',
				'text' => elgg_echo('download'),
				'icon' => 'download',
				'title' => elgg_echo('translation_editor:plugin_list:merge'),
				'href' => "action/translation_editor/merge?current_language={$current_language}&plugin={$plugin_id}",
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 300,
				'is_action' => true,
			]);
		}
		
		return $return;
	}
	
	/**
	 * Add language selector menu items to the title menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:title'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerLanguageSelector(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('translation_editor')) {
			return;
		}
		
		$return = $hook->getValue();
		
		// language selector
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation-editor-show-language-selector',
			'text' => elgg_echo('translation_editor:show_language_selector'),
			'href' => false,
			'icon' => 'angle-double-down',
			'link_class' => 'elgg-button elgg-button-action elgg-toggle',
			'priority' => 200,
			'data-toggle-selector' => '#translation-editor-language-selection, .elgg-menu-title li[class*="language-selector"] a',
			'data-toggle-slide' => 0,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation-editor-hide-language-selector',
			'text' => elgg_echo('translation_editor:hide_language_selector'),
			'href' => false,
			'icon' => 'angle-double-up',
			'link_class' => 'elgg-button elgg-button-action elgg-toggle',
			'priority' => 201,
			'style' => 'display: none;', // needed to prevent misallignment
			'data-toggle-selector' => '#translation-editor-language-selection, .elgg-menu-title li[class*="language-selector"] a',
			'data-toggle-slide' => 0,
		]);

		return $return;
	}
}
