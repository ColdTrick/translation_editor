<?php
/**
 * All the event callback function are bundled here
 */

/**
 * Invalidate some language caching when upgrading the system
 *
 * @param string $event  'upgrade'
 * @param string $type   'system'
 * @param null   $object not relavant
 *
 * @return void
 */
function translation_editor_upgrade_event($event, $type, $object) {
	
	if (defined("UPGRADING") && (UPGRADING == "upgrading")) {
		// call action hook function to avoid coding the same thing twice
		translation_editor_actions_hook("action", "upgrading", true, null);
	}
}
