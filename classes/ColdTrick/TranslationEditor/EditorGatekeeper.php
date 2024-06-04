<?php

namespace ColdTrick\TranslationEditor;

use Elgg\Request;
use Elgg\Exceptions\HttpException;
use Elgg\Router\Middleware\Gatekeeper;

/**
 * Gatekeeper to only allow translation editors view a page
 */
class EditorGatekeeper extends Gatekeeper {
	
	/**
	 * Editor gatekeeper
	 *
	 * @param Request $request the page request
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {
		parent::__invoke($request);
		
		$user_guid = $request->elgg()->session_manager->getLoggedInUserGuid();
		if (translation_editor_is_translation_editor($user_guid)) {
			return;
		}
		
		throw new HttpException(elgg_echo('translation_editor:gatekeeper'), ELGG_HTTP_FORBIDDEN);
	}
}
