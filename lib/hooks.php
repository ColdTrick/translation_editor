<?php
/**
 * All plugin hook callback functions are bundled in this file
 */

/**
 * Add menu items to the usericon dropdown
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover'
 * @param ElggMenuItem[] $return the current menu items
 * @param array          $params provided params to see who's dropdown menu we're handling
 *
 * @return ElggMenuItem[]
 */
function translation_editor_user_hover_menu($hook, $type, $return, $params) {
	
	if (elgg_is_admin_logged_in() && !empty($params) && is_array($params)) {
		$user = elgg_extract("entity", $params);
	
		if (!empty($user) && elgg_instanceof($user, "user") && !$user->isAdmin()) {
			// TODO: replace with a single toggle editor action?
			if (translation_editor_is_translation_editor($user->getGUID())) {
				$url = "action/translation_editor/unmake_translation_editor?user=" . $user->getGUID();
				$title = elgg_echo("translation_editor:action:unmake_translation_editor");
			} else {
				$url = "action/translation_editor/make_translation_editor?user=" . $user->getGUID();
				$title = elgg_echo("translation_editor:action:make_translation_editor");
			}
				
			$return[] = ElggMenuItem::factory(array(
				"name" => "translation_editor",
				"text" => $title,
				"href" => $url,
				"section" => "admin",
				"confirm" => elgg_echo("question:areyousure")
			));
		}
	}
	
	return $return;
}

/**
 * Listen to some plugin actions in order to reset the translation files
 *
 * @param string $hook   'action'
 * @param string $type   different plugin related actions
 * @param bool   $return true, return false to stop the action
 * @param null   $params no params
 *
 * @return void
 */
function translation_editor_actions_hook($hook, $type, $return, $params) {
	$allowed_actions = array(
		"admin/plugins/activate",
		"admin/plugins/deactivate",
		"admin/plugins/activate_all",
		"admin/plugins/deactivate_all",
		"admin/plugins/set_priority",
		"upgrading" // not actualy an action but comes from events.php
	);
	
	if (!empty($type) && in_array($type, $allowed_actions)) {
		// make sure we have all translations
		translation_editor_reload_all_translations();
		
		$languages = get_installed_translations();
		if (!empty($languages) && is_array($languages)) {
			foreach ($languages as $key => $desc) {
				$site = elgg_get_site_entity();
				remove_private_setting($site->getGUID(), "te_last_update_" . $key);
			}
		}
	}
}

/**
 * Add menu items to the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $return current menu items
 * @param array          $params provided params
 *
 * @return ElggMenuItem[]
 */
function translation_editor_page_menu($hook, $type, $return, $params) {
	
	if (elgg_is_admin_logged_in() && elgg_in_context("admin")) {
		$return[] = ElggMenuItem::factory(array(
			"name" => "translation_editor",
			"href" => "translation_editor",
			"text" => elgg_echo("translation_editor:menu:title"),
			"parent_name" => "appearance",
			"section" => "configure"
		));
	}
	
	return $return;
}

/**
 * Add menu items to the site menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:site'
 * @param ElggMenuItem[] $return current menu items
 * @param array          $params provided params
 *
 * @return ElggMenuItem[]
 */
function translation_editor_site_menu($hook, $type, $return, $params) {
	
	if (translation_editor_is_translation_editor()) {
		$return[] = ElggMenuItem::factory(array(
			"name" => "translation_editor",
			"text" => elgg_echo("translation_editor:menu:title"),
			"href" => "translation_editor"
		));
	}
	
	return $return;
}
