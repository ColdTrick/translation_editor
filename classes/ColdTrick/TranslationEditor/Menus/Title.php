<?php
namespace ColdTrick\TranslationEditor\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the title menu
 */
class Title {
	
	/**
	 * Add menu items to the title menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!elgg_in_context('translation_editor')) {
			return null;
		}
		
		$current_language = get_input('current_language');
		$plugin_id = get_input('plugin_id');
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		// show import/export buttons only on language page (not on plugins)
		if (elgg_is_admin_logged_in() && $current_language && empty($plugin_id)) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-import',
				'icon' => 'file-import',
				'text' => elgg_echo('import'),
				'href' => elgg_generate_url('default:translation_editor:import', [
					'current_language' => $current_language,
				]),
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 210,
			]);
			
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-export',
				'icon' => 'file-export',
				'text' => elgg_echo('export'),
				'href' => elgg_generate_url('default:translation_editor:export', [
					'current_language' => $current_language,
				]),
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 220,
			]);
			$return[] = \ElggMenuItem::factory([
				'name' => 'translation-editor-snapshots',
				'icon' => 'eye',
				'text' => elgg_echo('translation_editor:snapshots'),
				'href' => elgg_http_add_url_query_elements('ajax/view/translation_editor/snapshots', [
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
				'icon' => 'download',
				'text' => elgg_echo('download'),
				'title' => elgg_echo('translation_editor:plugin_list:merge'),
				'href' => elgg_generate_action_url('translation_editor/merge', [
					'current_language' => $current_language,
					'plugin' => $plugin_id,
				]),
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 300,
			]);
		}
		
		return $return;
	}
	
	/**
	 * Add language selector menu items to the title menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title'
	 *
	 * @return null|MenuItems
	 */
	public static function registerLanguageSelector(\Elgg\Event $event): ?MenuItems {
		if (!elgg_in_context('translation_editor')) {
			return null;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		// language selector
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation-editor-show-language-selector',
			'icon' => 'angle-double-down',
			'text' => elgg_echo('translation_editor:show_language_selector'),
			'href' => false,
			'link_class' => 'elgg-button elgg-button-action elgg-toggle',
			'priority' => 200,
			'data-toggle-selector' => '#translation-editor-language-selection, .elgg-menu-title li[class*="language-selector"] a',
			'data-toggle-slide' => 0,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'translation-editor-hide-language-selector',
			'icon' => 'angle-double-up',
			'text' => elgg_echo('translation_editor:hide_language_selector'),
			'href' => false,
			'link_class' => 'elgg-button elgg-button-action elgg-toggle',
			'priority' => 201,
			'style' => 'display: none;', // needed to prevent misalignment
			'data-toggle-selector' => '#translation-editor-language-selection, .elgg-menu-title li[class*="language-selector"] a',
			'data-toggle-slide' => 0,
		]);

		return $return;
	}
}
