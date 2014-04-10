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
	
	// invalidate site cache
	translation_editor_invalidate_site_cache();
}
