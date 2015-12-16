<?php

namespace ColdTrick\TranslationEditor;

/**
 * The pagehandler for nice url's
 *
 * @package    ColdTrick
 * @subpackage TranslationEditor
 */
class PageHandler {
	
	/**
	 * The page handler for the nice url's of this plugin
	 *
	 * @param array $page the url elements
	 *
	 * @return bool
	 */
	public static function translationEditor($page) {
		
		$include_file = false;
		$base_path = elgg_get_plugins_path() . 'translation_editor/pages/';
		
		switch ($page[0]) {
			case 'search':
				$include_file = "{$base_path}search.php";
				
				break;
			default:
				if (empty($page[0])) {
					break;
				}
				
				// set language
				set_input('current_language', $page[0]);
				
				// set (optional) plugin_id
				if (!empty($page[1])) {
					set_input('plugin', $page[1]);
				}
				
				$include_file = "{$base_path}index.php";
				break;
		}
		
		if (!empty($include_file)) {
			include($include_file);
			return true;
		}
		
		$current_language = get_current_language();
		forward("translation_editor/{$current_language}");
		
		return true;
	}
}
